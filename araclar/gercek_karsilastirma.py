#!/usr/bin/env python3
"""Sonnet 5.5 ile Fable 5.2'yi aynı görevlerde GERÇEKTEN karşılaştırır.

Ne yapar?
  * Her görevi iki modele de (varsayılan) 3 kez sorar.
  * Süreyi, token sayılarını ve liste fiyatlarıyla maliyeti ölçer.
  * Cevabı kontrol edilebilen görevlerde (sayısal sonuç, kod testleri)
    doğruluğu otomatik puanlar. Kod görevlerinde modelin yazdığı kodu
    gerçekten çalıştırıp testlerden geçirir.
  * Sonucu assets/js/gercek-sonuclar.js dosyasına yazar. Site bu dosyayı
    görünce Bölüm 19'daki temsilî örneklerin yerine gerçek sonuçları gösterir.

Kurulum ve çalıştırma:
  pip install anthropic
  export ANTHROPIC_API_KEY=...        # kendi anahtarın
  python3 araclar/gercek_karsilastirma.py            # 3 tekrar
  python3 araclar/gercek_karsilastirma.py --tekrar 1 # daha ucuz deneme

Yaklaşık maliyet: 8 görev × 2 model × 3 tekrar. Görevler kısa olduğu için
toplam genelde birkaç dolar civarındadır; betik sonunda gerçek tutarı yazar.

Notlar:
  * Kod görevlerinde modelin ürettiği kod bu bilgisayarda, ayrı bir Python
    sürecinde ve 10 saniye zaman sınırıyla çalıştırılır.
  * Bilerek "fallbacks" (ret durumunda başka modele geçme) KULLANILMIYOR:
    karşılaştırmada her cevabın gerçekten adı geçen modelden gelmesi gerekir.
    Bir istek reddedilirse sonuçta "ret" olarak görünür.
"""
import argparse
import datetime as dt
import json
import os
import re
import subprocess
import sys
import tempfile
import time

try:
    import anthropic
except ImportError:
    sys.exit("Önce kur: pip install anthropic")

MODELLER = {
    "sonnet": {"id": "claude-sonnet-5-5", "ad": "Sonnet 5.5", "girdi": 2.0, "cikti": 10.0},
    "fable": {"id": "claude-fable-5-2", "ad": "Fable 5.2", "girdi": 8.0, "cikti": 40.0},
}

# ---------------------------------------------------------------- kontrolcüler

def son_satir(metin):
    satirlar = [s.strip() for s in metin.strip().splitlines() if s.strip()]
    return satirlar[-1] if satirlar else ""


def sayi_kontrol(beklenen):
    def kontrol(metin):
        sayilar = re.findall(r"-?\d+(?:[.,]\d+)?", son_satir(metin).replace("−", "-"))
        if not sayilar:
            return False, "son satırda sayı yok"
        deger = float(sayilar[-1].replace(",", "."))
        return abs(deger - beklenen) < 1e-9, f"son satır: {sayilar[-1]}"
    return kontrol


def metin_kontrol(beklenen):
    def kontrol(metin):
        satir = son_satir(metin).replace(" ", "")
        return beklenen in satir, f"son satır: {son_satir(metin)[:40]}"
    return kontrol


def cumle_sayisi_kontrol(n):
    def kontrol(metin):
        cumleler = [c for c in re.split(r"(?<=[.!?])\s+", metin.strip()) if c]
        return len(cumleler) == n, f"{len(cumleler)} cümle"
    return kontrol


def kod_kontrol(fonksiyon, testler):
    def kontrol(metin):
        bloklar = re.findall(r"```(?:python)?\n(.*?)```", metin, re.S)
        if not bloklar:
            return False, "kod bloğu yok"
        kod = bloklar[0]
        dosya = kod + "\n\nimport json\ngecen = 0\n"
        for girdi, beklenen in testler:
            dosya += (
                f"try:\n    gecen += {fonksiyon}({girdi!r}) == {beklenen!r}\n"
                "except Exception:\n    pass\n"
            )
        dosya += "print(gecen)\n"
        with tempfile.TemporaryDirectory() as d:
            yol = os.path.join(d, "deneme.py")
            with open(yol, "w", encoding="utf-8") as f:
                f.write(dosya)
            try:
                cikti = subprocess.run([sys.executable, "-I", yol], capture_output=True,
                                       text=True, timeout=10, cwd=d)
                gecen = int(cikti.stdout.strip().splitlines()[-1])
            except Exception:
                gecen = 0
        return gecen == len(testler), f"{gecen}/{len(testler)} test"
    return kontrol

# ---------------------------------------------------------------- görevler

PARAGRAF = (
    "Bir dil modeli cevap üretirken iki farklı iş yapar. Önce kullanıcının "
    "mesajındaki bütün token'ları aynı anda, paralel olarak işler; bu aşamaya "
    "ön doldurma denir ve çipler bu işte çok hızlıdır. Ardından cevabı token "
    "token üretir; her yeni token bir öncekine bağlı olduğu için bu aşama sırayla "
    "ilerler ve her adımda modelin bütün parametrelerinin bellekten okunması "
    "gerekir. Bu yüzden çıktı üretmek, girdiyi okumaktan hem daha yavaş hem de "
    "daha pahalıdır ve servisler çıktı token'larını daha yüksek fiyatlandırır."
)

GOREVLER = [
    {"id": "eposta", "ad": "E-posta yazmak",
     "istek": "Ekibe, perşembe 14.00'teki haftalık toplantının cumaya ertelendiğini kibarca bildiren kısa bir e-posta yaz."},
    {"id": "ozet", "ad": "Özetlemek",
     "istek": "Aşağıdaki paragrafı tam olarak iki cümleyle özetle. Sadece özeti yaz.\n\n" + PARAGRAF,
     "kontrol": cumle_sayisi_kontrol(2), "kontrol_adi": "tam iki cümle mi?"},
    {"id": "ceviri", "ad": "Deyim çevirisi",
     "istek": "“Damlaya damlaya göl olur” deyimini İngilizceye, anlamını koruyarak çevir. Önce çeviriyi, sonra tek cümlelik bir açıklama yaz."},
    {"id": "yuzde", "ad": "Yüzde tuzağı",
     "istek": "Bir ürünün fiyatına önce %20 zam, ardından %20 indirim yapıldı. Son fiyat ilk fiyata göre yüzde kaç değişti? Kısaca açıkla ve son satıra sadece sayıyı yaz (ör. -3).",
     "kontrol": sayi_kontrol(-4), "kontrol_adi": "cevap −4 mü?"},
    {"id": "kod_duzelt", "ad": "Kod düzeltmesi", "kod": True,
     "istek": "Bu Python fonksiyonu bazı durumlarda yanlış sonuç veriyor ya da hata fırlatıyor. Düzelt. Sadece düzeltilmiş fonksiyonu tek bir ```python bloğunda ver.\n\n"
              "def ikinci_en_buyuk(sayilar):\n    \"\"\"Listedeki ikinci en büyük FARKLI sayıyı döndürür; yoksa None döndürür.\"\"\"\n    sirali = sorted(sayilar)\n    return sirali[-2]",
     "kontrol": kod_kontrol("ikinci_en_buyuk", [([3, 1, 2], 2), ([5, 5, 4], 4), ([7], None), ([], None), ([-1, -1], None), ([2, 2, 3, 3], 2), ([10, -2, 10, 9], 9)]),
     "kontrol_adi": "7 testin hepsi geçti mi?"},
    {"id": "kod_yaz", "ad": "Kod yazmak", "kod": True,
     "istek": "Roma rakamıyla yazılmış bir sayıyı (ör. 'MCMXCIV') tam sayıya çeviren `roma_to_int(s)` adlı bir Python fonksiyonu yaz. Sadece kodu tek bir ```python bloğunda ver.",
     "kontrol": kod_kontrol("roma_to_int", [("III", 3), ("IV", 4), ("IX", 9), ("LVIII", 58), ("XL", 40), ("MCMXCIV", 1994), ("MMXXVI", 2026)]),
     "kontrol_adi": "7 testin hepsi geçti mi?"},
    {"id": "sayma", "ad": "Zor sayma",
     "istek": "1'den 1000'e kadar (1000 dahil) kaç tam sayının rakamları toplamı tam olarak 10'dur? Kısaca açıkla ve son satıra sadece sayıyı yaz.",
     "kontrol": sayi_kontrol(63), "kontrol_adi": "cevap 63 mü?"},
    {"id": "olasilik", "ad": "Zor olasılık",
     "istek": "Bir ailenin iki çocuğu var. Çocuklardan en az biri salı günü doğmuş bir erkek. İki çocuğun da erkek olma olasılığı nedir? Kısaca açıkla ve son satıra sadece kesri yaz (ör. 1/2).",
     "kontrol": metin_kontrol("13/27"), "kontrol_adi": "cevap 13/27 mi?"},
]

# ---------------------------------------------------------------- çalıştırma

def sor(client, model, istek, effort):
    t0 = time.time()
    yanit = client.messages.create(
        model=model["id"],
        max_tokens=16000,
        thinking={"type": "adaptive", "display": "summarized"},
        output_config={"effort": effort},
        messages=[{"role": "user", "content": istek}],
    )
    sure = time.time() - t0
    metin = "".join(b.text for b in yanit.content if b.type == "text")
    dusunce = "".join(b.thinking for b in yanit.content if b.type == "thinking")
    girdi, cikti = yanit.usage.input_tokens, yanit.usage.output_tokens
    maliyet = girdi / 1e6 * model["girdi"] + cikti / 1e6 * model["cikti"]
    return {
        "metin": metin.strip(), "dusunce": dusunce.strip(), "sure": round(sure, 1),
        "girdi": girdi, "cikti": cikti, "maliyet": round(maliyet, 5),
        "stop": yanit.stop_reason, "model_id": yanit.model,
    }


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--tekrar", type=int, default=3)
    ap.add_argument("--effort", default="high", choices=["low", "medium", "high", "xhigh", "max"])
    ap.add_argument("--cikti", default=os.path.join(os.path.dirname(__file__), "..", "assets", "js", "gercek-sonuclar.js"))
    args = ap.parse_args()

    client = anthropic.Anthropic()
    sonuc = {"tarih": dt.date.today().isoformat(), "effort": args.effort, "tekrar": args.tekrar, "gorevler": []}
    toplam = 0.0

    for g in GOREVLER:
        kayit = {"id": g["id"], "ad": g["ad"], "istek": g["istek"], "kod": g.get("kod", False),
                 "kontrol_adi": g.get("kontrol_adi")}
        for anahtar, model in MODELLER.items():
            denemeler = []
            for i in range(args.tekrar):
                print(f"{g['ad']:<16} {model['ad']:<11} deneme {i + 1}/{args.tekrar} …", end=" ", flush=True)
                try:
                    d = sor(client, model, g["istek"], args.effort)
                except anthropic.APIError as e:
                    print("HATA:", e)
                    denemeler.append({"hata": str(e)[:200]})
                    continue
                if d["stop"] == "refusal":
                    d["dogru"], d["kontrol"] = False, "ret"
                elif "kontrol" in g:
                    d["dogru"], d["kontrol"] = g["kontrol"](d["metin"])
                toplam += d["maliyet"]
                print(f"{d['sure']} sn, {d['cikti']} çıktı token, ${d['maliyet']:.4f}", d.get("kontrol", ""))
                denemeler.append(d)
            kayit[anahtar] = denemeler
        sonuc["gorevler"].append(kayit)

    sonuc["toplam_maliyet"] = round(toplam, 4)
    with open(args.cikti, "w", encoding="utf-8") as f:
        f.write("/* Bu dosya araclar/gercek_karsilastirma.py tarafından üretildi. Elle düzenleme. */\n")
        f.write("window.GERCEK = " + json.dumps(sonuc, ensure_ascii=False, indent=1) + ";\n")
    print(f"\nYazıldı: {os.path.normpath(args.cikti)}  ·  toplam maliyet ≈ ${toplam:.2f}")


if __name__ == "__main__":
    main()
