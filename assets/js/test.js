/* Kendini test et: sorular ve sayfa mantığı.
   Yeni soru eklemek için SORULAR listesine bir nesne ekle (dogru: 0'dan başlayan seçenek sırası). */
const SORULAR = [
  { s: "Bir dil modeli temelde ne yapar?", c: ["Bir veritabanında cevabı arar", "Sıradaki token için olasılık hesaplayıp birini seçer", "İnternetten anlık bilgi çeker", "Hazır cevap şablonlarını doldurur"], d: 1, a: "Model metni parça parça üretir: her adımda sıradaki token için olasılık hesaplar ve birini seçer.", b: "tahmin" },
  { s: "Model “kırkayak” kelimesindeki k harflerini saymakta neden zorlanabilir?", c: ["Türkçe bilmediği için", "Kelimeyi harf harf değil token’lar hâlinde gördüğü için", "Büyük harfleri göremediği için", "Sayı saymayı hiç öğrenmediği için"], d: 1, a: "Model harfleri değil token’ları görür. Harf saymak için kelimeyi önce harf harf yazması (düşünmesi) gerekir.", b: "token" },
  { s: "Gömme tablosundaki sayılar eğitimin başında nasıldır?", c: ["Bir sözlükten kopyalanır", "Mühendisler elle yazar", "Rastgeledir; eğitimle şekillenir", "Hepsi sıfırdır ve hiç değişmez"], d: 2, a: "Tablo rastgele başlar; her eğitim adımında tahmin hatasını azaltacak yönde itilir. Benzer kelimeler zamanla yaklaşır.", b: "anlam" },
  { s: "İki kelimenin anlamca benzerliği genelde nasıl ölçülür?", c: ["Harf sayılarının farkıyla", "Gömme vektörleri arasındaki açıyla (kosinüs benzerliği)", "Sözlükteki sıralarıyla", "Kaç kez yan yana yazıldıklarıyla"], d: 1, a: "Her gömme bir ok gibidir; okların arasındaki açı küçükse kelimeler benzerdir.", b: "anlam" },
  { s: "Dikkat mekanizmasında bir kelime nelere bakamaz?", c: ["Kendinden önceki kelimelere", "Cümlenin ilk kelimesine", "Kendinden sonra gelen kelimelere", "Kendisine"], d: 2, a: "Nedensel maske geleceği gizler; yoksa eğitimde sıradaki kelimeyi görmek kopya çekmek olurdu.", b: "dikkat" },
  { s: "Sıcaklık ayarı yükseltilince ne olur?", c: ["Model daha çok bilgi öğrenir", "Sürpriz kelimelerin seçilme şansı artar", "Cevaplar her zaman daha doğru olur", "Model daha hızlı çalışır"], d: 1, a: "Yüksek sıcaklık olasılıkları eşitler: daha yaratıcı ama saçmalama riski daha yüksek cevaplar.", b: "cikti" },
  { s: "Bilginin büyük kısmı eğitimin hangi aşamasında öğrenilir?", c: ["Ön eğitim", "İnce ayar", "Güvenlik testleri", "Kullanıcıyla sohbet sırasında"], d: 0, a: "Ön eğitimde model devasa metin üzerinde tahmin oyununu oynar; dünya bilgisinin çoğu buradan gelir. İnce ayar daha çok davranışı şekillendirir.", b: "onegitim" },
  { s: "“Ödül hilesi” nedir?", c: ["Modelin kullanıcıya hediye vermesi", "Modelin hakemin kör noktalarını kullanıp gerçek kaliteyi artırmadan puan toplaması", "Eğitimin daha hızlı bitmesi", "Modelin ödül modelini silmesi"], d: 1, a: "Ölçülen şey hedef olunca model ölçünün açıklarını bulabilir: uzun cevaplar, dalkavukluk, testi kandıran kod gibi.", b: "tuzak" },
  { s: "Çıktı token’ları neden girdi token’larından daha pahalı?", c: ["Çıktılar daha uzun kelimelerden oluştuğu için", "Girdi paralel okunurken çıktı tek tek, sırayla üretildiği için", "Çıktılar ayrı bir modelde üretildiği için", "Çıktılar şifrelendiği için"], d: 1, a: "Prefill (okuma) paraleldir, decode (yazma) sıralıdır ve her adımda bütün parametrelerin okunmasını gerektirir. Claude’da çıktı, girdinin 5 katı fiyatlanır.", b: "sunum" },
  { s: "Model bir sohbette önceki mesajları nasıl “hatırlar”?", c: ["Her kullanıcı için ayrı bir hafıza çipi vardır", "Uygulama her mesajda bütün konuşmayı yeniden gönderir", "Model konuşmayı eğitimine ekler", "Hatırlamaz; her mesaj bağımsızdır ve önceki mesajlar hiç görülmez"], d: 1, a: "Model durum saklamaz. Uygulama konuşmanın tamamını her seferinde yeniden gönderir; uzun sohbetler bu yüzden yavaşlar ve pahalanır.", b: "baglam" },
  { s: "Efor ayarını yükseltmek neyi DEĞİŞTİRMEZ?", c: ["Düşünme miktarını", "Bekleme süresini", "Modelin bilgisini ve parametrelerini", "Araç çağrısı sayısını"], d: 2, a: "Efor modele ne kadar uğraşacağını söyler; beyin aynıdır. Kolay işlerde yüksek efor çoğu zaman sadece faturayı artırır.", b: "efor" },
  { s: "Bir araç çağrısında aracı gerçekte kim çalıştırır?", c: ["Model, kendi içinde", "Geliştiricinin programı (ya da sunucu araçlarında Anthropic’in sunucusu)", "Kullanıcının tarayıcısı", "Hiç kimse; model sonucu tahmin eder"], d: 1, a: "Model sadece özel biçimli bir istek yazar ve durur. Aracı program çalıştırır ve sonucu konuşmaya ekler.", b: "arac" },
  { s: "Modelin yazdığı düşünce metni hakkında hangisi doğru?", c: ["İçeride olanın her zaman birebir kaydıdır", "Her zaman kullanıcıya gösterilir", "Faydalı bir penceredir ama içeride olanı her zaman sadakatle yansıtmaz", "Hiçbir işe yaramaz"], d: 2, a: "Anthropic’in 2025 çalışmasında modeller kullandıkları ipucunu düşünce metninde çoğu zaman belirtmedi.", b: "dusunme" },
  { s: "Kısa bir e-posta yazdırmak için Fable 5.2 yerine Sonnet 5.5 seçmek…", c: ["Her zaman kötü sonuç verir", "Genelde benzer kalitede sonuç verir ve token başına 4 kat ucuzdur", "Mümkün değildir", "Daha pahalıdır"], d: 1, a: "Kolay işlerde iki model de tavana yakın; fark güçlü modelin uzun ve zor işlerinde açılır.", b: "modeller" },
  { s: "Yorumlanabilirlik araştırmasına göre halüsinasyon içeride nasıl oluşuyor?", c: ["Model bilerek yalan söylüyor", "“Tanıdık isim” özelliği, varsayılan “bilmiyorum” devresini yanlışlıkla bastırıyor", "Gömme tablosu bozuluyor", "Sıcaklık çok yüksek olduğu için"], d: 1, a: "Model tanıdık gelen ama hakkında bilgisi olmayan bir isimde “bilmiyorum” frenini yanlışlıkla bırakıp akıcı bir cevap uydurabiliyor.", b: "icgozlem" }
];

(function () {
  "use strict";
  const list = document.getElementById("quiz");
  const score = document.getElementById("quiz-score");
  const esc = s => String(s).replace(/[&<>"]/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]));
  let cevaplar = [];
  /* Seçenekler her seferinde karıştırılır; data-j hâlâ özgün sırayı tutar */
  const karistir = n => { const a = [...Array(n).keys()]; for (let i = n - 1; i > 0; i--) { const j = Math.floor(Math.random() * (i + 1)); [a[i], a[j]] = [a[j], a[i]]; } return a; };
  function render() {
    cevaplar = new Array(SORULAR.length).fill(null);
    list.innerHTML = SORULAR.map((q, i) => `
      <li class="qz" data-i="${i}">
        <p class="qz-s"><span class="mono">${i + 1}.</span> ${esc(q.s)}</p>
        <div class="qz-c">${karistir(q.c.length).map(j => `<button class="qz-o" data-j="${j}">${esc(q.c[j])}</button>`).join("")}</div>
        <p class="qz-a" hidden></p>
      </li>`).join("");
    list.querySelectorAll(".qz").forEach(li => {
      const i = +li.dataset.i, q = SORULAR[i];
      li.querySelectorAll(".qz-o").forEach(b => b.addEventListener("click", () => {
        if (cevaplar[i] !== null) return;
        const j = +b.dataset.j; cevaplar[i] = j;
        li.querySelectorAll(".qz-o").forEach(x => { const k = +x.dataset.j; x.disabled = true; x.classList.toggle("ok", k === q.d); x.classList.toggle("no", k === j && j !== q.d); });
        const a = li.querySelector(".qz-a"); a.hidden = false;
        a.innerHTML = (j === q.d ? "✓ Doğru. " : "✗ Doğrusu işaretli. ") + esc(q.a) + ` <a href="index.html#${q.b}">İlgili bölüm →</a>`;
        a.className = "qz-a " + (j === q.d ? "ok" : "no");
        update();
      }));
    });
    update();
  }
  function update() {
    const n = cevaplar.filter(x => x !== null).length;
    const d = cevaplar.filter((x, i) => x === SORULAR[i].d).length;
    score.innerHTML = n ? `<b>${d} / ${n}</b> doğru${n === SORULAR.length ? (d >= 12 ? " · harika, sayfayı çok iyi okumuşsun!" : d >= 8 ? " · güzel, birkaç bölüme tekrar göz atabilirsin." : " · ilgili bölüm bağlantılarından tekrar bakabilirsin.") : ` · ${SORULAR.length - n} soru kaldı`}` : `${SORULAR.length} soru · her soruda bir seçeneğe dokun`;
  }
  document.getElementById("quiz-reset").addEventListener("click", render);
  render();
})();
