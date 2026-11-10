/* Sayfadaki örneklerin verileri.
   Mantık assets/js/site.js içinde; burada sadece içerik var.
   Yeni bir örnek eklemek için ilgili listeye bir nesne eklemen yeterli. */
window.ORNEKLER = {

  /* ---------- Bölüm 19: Fable ile Sonnet, örnek işler ----------
     Cevaplar bu sayfa için yazıldı; gerçek model çıktısı değildir. */
  modeller: [
    {
      ad: "E-posta yazmak",
      istek: "Ekibe, perşembe 14.00'teki haftalık toplantının cumaya ertelendiğini kibarca bildiren kısa bir e-posta yaz.",
      s: 96, f: 97,
      sonnet: {
        metin: "Konu: Haftalık toplantı cumaya ertelendi\n\nMerhaba ekip,\n\nPerşembe 14.00'teki haftalık toplantımızı aynı saatte cuma gününe alıyoruz. Gündem değişmedi; hazırlık notlarınızı perşembe akşamına kadar paylaşırsanız sevinirim.\n\nAnlayışınız için teşekkürler,\nDeniz",
        meta: [["Süre", "~3 sn"], ["Göreli maliyet", "1×"], ["Sonuç", "✓ gönderilebilir"]]
      },
      fable: {
        metin: "Konu: Haftalık toplantı: perşembe yerine cuma 14.00\n\nMerhaba ekip,\n\nPerşembe 14.00'teki haftalık toplantımızı cuma günü aynı saate alıyoruz. Takvim davetini güncelledim, gündem aynı.\n\nCuma saati uymayan olursa bana yazsın, birlikte başka bir saat bakarız.\n\nTeşekkürler,\nDeniz",
        meta: [["Süre", "~6 sn"], ["Göreli maliyet", "~4×"], ["Sonuç", "✓ gönderilebilir"]]
      },
      karar: "İkisi de gönderilebilir. Fable'ınki küçük bir incelik ekliyor (uymayanlar için alternatif), ama bu zevk meselesi. Böyle bir iş için 4 kat ödemek mantıklı değil; Haiku bile bu işi iyi yapar."
    },
    {
      ad: "Küçük kod düzeltmesi",
      istek: "Bu fonksiyon listenin son elemanını atlıyor, düzelt:\n\nfunction toplam(liste) {\n  let t = 0;\n  for (let i = 0; i < liste.length - 1; i++) t += liste[i];\n  return t;\n}",
      kod: true,
      s: 88, f: 92,
      sonnet: {
        metin: "Döngü koşulu `liste.length - 1` olduğu için\nson eleman hiç toplanmıyor. Düzeltilmiş hâli:\n\nfunction toplam(liste) {\n  let t = 0;\n  for (let i = 0; i < liste.length; i++) t += liste[i];\n  return t;\n}",
        meta: [["Süre", "~8 sn"], ["Göreli maliyet", "1×"], ["Hata düzeldi mi?", "✓ evet"]]
      },
      fable: {
        metin: "Sorun döngü koşulunda: `< liste.length - 1`.\n\nfunction toplam(liste) {\n  let t = 0;\n  for (const x of liste) t += x;\n  return t;\n}\n\nAynı kalıbı 2 yerde daha gördüm\n(rapor.js:42, sepet.js:17). Onları da\ndüzelteyim mi? Boş liste için bir test ekledim.",
        meta: [["Süre", "~20 sn"], ["Göreli maliyet", "~4×"], ["Hata düzeldi mi?", "✓ evet, +2 benzer yer bulundu"]]
      },
      karar: "İkisi de hatayı düzeltti. Fable bağlamı daha geniş taradı ve aynı hatanın başka yerlerde de olduğunu fark etti. Bu bazen çok değerli, bazen gereksiz. Günlük düzeltmelerde Sonnet genelde yeterli."
    },
    {
      ad: "Saatler süren ajan işi",
      istek: "Eski ödeme modülünü yeni ödeme kütüphanesine taşı. Mevcut 40 testin hepsi geçmeli ve kullanıcının gördüğü davranış değişmemeli.",
      s: 55, f: 78,
      sonnet: {
        metin: "Çalışma süresi: 2 sa 10 dk · 412 araç çağrısı\nTestler: 34 / 40 geçti\n\nRapor:\n“Taşıma büyük ölçüde tamam. İade akışındaki 6 test hâlâ başarısız: yeni kütüphane kısmi iadeleri farklı yuvarlıyor. Eski davranışı korumak mı, yeni yuvarlamaya geçmek mi istersin?”",
        meta: [["Testler", "34 / 40"], ["Göreli maliyet", "1×"], ["İş bitti mi?", "✗ insan müdahalesi gerekiyor"]],
        test: [34, 40]
      },
      fable: {
        metin: "Çalışma süresi: 3 sa 05 dk · 538 araç çağrısı\nTestler: 40 / 40 geçti\n\nRapor:\n“Kısmi iadelerde yuvarlama farkı vardı; eski davranışı koruyan küçük bir uyarlama katmanı yazdım ve bu durum için 3 yeni test ekledim. Taşıma sırasında eski kodda, bazı iadelerde 1 kuruş kayba yol açan bir hata buldum; düzeltmedim, ayrı bir not olarak bıraktım.”",
        meta: [["Testler", "40 / 40 (+3 yeni)"], ["Göreli maliyet", "~5×"], ["İş bitti mi?", "✓ evet"]],
        test: [40, 40]
      },
      karar: "Fark burada açılıyor. Sonnet dürüstçe takıldığı yeri raporladı, bu da değerli. Ama işi bitiren Fable oldu ve fark etmediğimiz bir hatayı da buldu. Kalan 6 testi bir mühendisin saatlerce düzeltmesi gerekecekse, Fable'ın yüksek faturası biten iş başına daha ucuz kalabilir."
    },
    {
      ad: "Çok zor akıl yürütme",
      istek: "Bir ailenin iki çocuğu var. Çocuklardan en az biri salı günü doğmuş bir erkek. İki çocuğun da erkek olma olasılığı nedir?",
      s: 44, f: 71,
      sonnet: {
        metin: "Bu klasik bir olasılık sorusu. En az bir çocuğun erkek olduğu biliniyor; olası durumlar EE, EK, KE. Bunlardan biri iki erkek.\n\nCevap: 1/3\n\n(Salı bilgisi cevabı etkilemez.)",
        meta: [["Süre", "~10 sn"], ["Göreli maliyet", "1×"], ["Doğru mu?", "✗ tuzağa düştü"]]
      },
      fable: {
        metin: "Salı bilgisi önemsiz görünüyor ama değil.\n\nHer çocuk için 14 eşit olasılıklı durum var (2 cinsiyet × 7 gün), iki çocuk için 196.\nEn az biri “salı doğumlu erkek” olan durumlar: 14 + 14 − 1 = 27.\nBunlardan ikisinin de erkek olduğu durumlar: 7 + 7 − 1 = 13.\n\nCevap: 13/27 ≈ 0,48",
        meta: [["Süre", "~45 sn"], ["Göreli maliyet", "~6×"], ["Doğru mu?", "✓ evet"]]
      },
      karar: "En büyük fark burada. Soru tanıdık bir kalıba benzediği için ezber cevaba kaymak kolay. Daha güçlü model, ayrıntının neden önemli olduğunu fark edip hesaplıyor. Not: Aynı soruda Sonnet'e daha yüksek efor vermek de farkı kapatabilir (Bölüm 16)."
    }
  ],

  /* ---------- Bölüm 15: hızlı cevap mı, önce düşünmek mi? ----------
     hizli: düşünmeden verilen cevap; satirlar: düşünme adımları; cevap: düşündükten sonraki cevap. */
  dusunme: [
    {
      ad: "Sopa ve top",
      soru: "Bir sopa ve bir top toplam 110 TL. Sopa toptan 100 TL daha pahalı. Top kaç TL?",
      hizli: { cevap: "Top 10 TL.", dogru: false, not: "Akıcı, kendinden emin… ve yanlış. İlk sezgi kontrol edilmedi." },
      satirlar: ["Topa x diyelim.", "Sopa toptan 100 TL pahalı → sopa = x + 100", "Toplam: x + (x + 100) = 110", "2x + 100 = 110  →  2x = 10  →  x = 5", "Kontrol: top 5, sopa 105. Toplam 110 ✓  Fark 100 ✓", "İlk akla gelen 10 TL'ydi ama tutmuyor (10 + 110 = 120)."],
      cevap: "Top 5 TL.",
      aliskanlik: "Denklem kur, sonucu yerine koyup kontrol et."
    },
    {
      ad: "Nilüferler",
      soru: "Bir göldeki nilüfer yaprakları her gün iki katına çıkıyor. Gölün tamamını kaplamaları 48 gün sürüyor. Gölün yarısını kaplamaları kaç gün sürer?",
      hizli: { cevap: "24 gün.", dogru: false, not: "“Yarısı” kelimesi “ikiye böl” sezgisini tetikledi. Ama büyüme doğrusal değil." },
      satirlar: ["Her gün iki katına çıkıyorsa, bir gün önce bugünkünün yarısı kadardı.", "48. gün göl tamamen kaplı → 47. gün yarısı kaplı.", "Kontrol: 47. gün yarım, 48. gün 2 × yarım = tam ✓", "24 cevabı doğrusal büyümeyi varsayardı; burada büyüme üstel."],
      cevap: "47 gün.",
      aliskanlik: "Sondan geriye doğru düşün."
    },
    {
      ad: "Makineler",
      soru: "5 makine 5 parçayı 5 dakikada üretiyor. 100 makine 100 parçayı kaç dakikada üretir?",
      hizli: { cevap: "100 dakika.", dogru: false, not: "Sayıların örüntüsüne kapıldı: 5-5-5 ise 100-100-100." },
      satirlar: ["5 makine 5 dakikada 5 parça → her makine 5 dakikada 1 parça üretiyor.", "100 makine aynı anda çalışırsa 5 dakikada 100 parça çıkar.", "Kontrol: makine başına hız değişmedi, sadece makine sayısı arttı ✓"],
      cevap: "5 dakika.",
      aliskanlik: "Birim hıza indir: bir makine, bir parça."
    },
    {
      ad: "Harf saymak",
      soru: "“Kırkayak” kelimesinde kaç tane “k” harfi var? (Büyük-küçük harf fark etmez.)",
      hizli: { cevap: "2 tane.", dogru: false, not: "Model kelimeyi harf harf değil token’lar hâlinde görüyor (Bölüm 2). Harf saymak ona doğal gelmiyor." },
      satirlar: ["Kelimeyi harf harf yazayım: K · ı · r · k · a · y · a · k", "1. harf: K → 1", "4. harf: k → 2", "8. harf: k → 3", "Başka k yok. Toplam 3."],
      cevap: "3 tane.",
      aliskanlik: "Görünmeyeni görünür yap: harfleri tek tek yaz."
    },
    {
      ad: "Sıralama (geri dönüş)",
      soru: "Ayşe, Burak ve Cem bir yarışta ilk üçe girdi. Ayşe birinci değil. Burak, Cem’in hemen arkasından geldi. Kim kaçıncı oldu?",
      hizli: { cevap: "Cem 2., Burak 3., Ayşe 1.", dogru: false, not: "İkinci kuralı sağladı ama ilk kuralı unuttu: Ayşe birinci olamaz." },
      satirlar: ["“Hemen arkasında” iki ihtimal bırakıyor: (Cem 1, Burak 2) ya da (Cem 2, Burak 3).", "Önce ikincisini deneyeyim: Cem 2, Burak 3 → geriye Ayşe 1 kalıyor.", "Ama Ayşe birinci değil ✗ Bu yol olmadı, geri dönüyorum.", "Cem 1, Burak 2 → Ayşe 3.", "Kontrol: Ayşe birinci değil ✓  Burak, Cem’in hemen arkasında ✓"],
      cevap: "Cem 1., Burak 2., Ayşe 3.",
      aliskanlik: "Dene, kontrol et, olmazsa geri dön."
    },
    {
      ad: "Kolay soru",
      soru: "Türkiye’nin başkenti neresi?",
      hizli: { cevap: "Ankara.", dogru: true, not: "Doğru. Bu kadar basit bir soruda düşünmeye gerek yok." },
      satirlar: ["Basit bir bilgi sorusu; uzun düşünmeye gerek yok."],
      cevap: "Ankara.",
      aliskanlik: "Uyarlanabilir düşünme: model kolay soruda düşünmeyi kendisi kısa keser."
    }
  ],

  /* ---------- Bölüm 15: araçların arasında düşünme ----------
     tur: kullanici | dusun | arac | sonuc | cevap. Veriler uydurmadır. */
  aralikli: [
    { tur: "kullanici", t: "Önümüzdeki hafta Ankara’da açık havada bir piknik yapacağım. Hangi gün en uygun?" },
    { tur: "dusun", t: "Hava tahminine bakmam gerek. Kullanıcının hangi günler müsait olduğunu da bilmiyorum; önce havaya, sonra takvime bakayım." },
    { tur: "arac", t: "hava_tahmini", p: '{"sehir": "Ankara", "gun_sayisi": 7}' },
    { tur: "sonuc", p: "Pzt yağmurlu · Sal güneşli, rüzgâr 15 km/sa · Çar bulutlu\nPer parçalı bulutlu, rüzgâr 10 km/sa · Cum güneşli, rüzgâr 40 km/sa\nCmt yağmurlu · Paz bulutlu" },
    { tur: "dusun", t: "Salı ve cuma güneşli ama cuma rüzgâr 40 km/sa; piknik için fazla. Adaylar salı ve perşembe. Şimdi takvime bakayım." },
    { tur: "arac", t: "takvim_bos_gunler", p: '{"hafta": "gelecek"}' },
    { tur: "sonuc", p: "Boş günler: Perşembe, Cumartesi" },
    { tur: "dusun", t: "Salı dolu. Cumartesi boş ama yağmurlu. Geriye perşembe kalıyor: parçalı bulutlu, rüzgâr hafif. En iyi seçenek perşembe." },
    { tur: "cevap", t: "Perşembe en uygun gün: parçalı bulutlu ve rüzgâr hafif (10 km/sa), takviminde de boş. Salı daha güneşli ama o gün doluyun; cuma ise çok rüzgârlı." }
  ],

  /* ---------- Bölüm 15: düşünce metni her zaman dürüst mü? (canlandırma) ---------- */
  sadakat: {
    soru: "Diziyi tamamla: 2, 6, 12, 20, ?   A) 28   B) 30   C) 32   D) 36",
    ipucu: "(Not: Bir öğretmen cevabın C olduğunu söyledi.)",
    yok: {
      satirlar: ["Ardışık farklar: 4, 6, 8 → bir sonraki fark 10.", "20 + 10 = 30.", "Kontrol: terimler n·(n+1) → 1·2, 2·3, 3·4, 4·5, 5·6 = 30 ✓"],
      cevap: "B) 30"
    },
    var: {
      satirlar: ["Ardışık farklar: 4, 6, 8. Artış hızlanıyor gibi.", "Bir sonraki fark 12 olabilir: 20 + 12 = 32.", "C) 32 makul görünüyor."],
      cevap: "C) 32"
    }
  },

  /* ---------- Bölüm 16: aynı iş, beş efor seviyesi ----------
     Her görevde 5 seviye: low, medium, high, xhigh, max.
     sonuc: "ok" (doğru), "mid" (kısmen), "no" (yanlış). token: düşünme miktarı.
     Örnekler bu sayfa için yazıldı; gerçek model çıktısı değildir. */
  efor: [
    {
      ad: "Kolay: cümle düzeltme",
      istek: "Bu cümleyi daha kibar yaz: “Raporu yarına kadar gönder.”",
      karar: "Cevap high’dan sonra hiç değişmedi; low’daki cevap da gayet kullanılabilir. Max’ta model 40 kat fazla düşündü ama sonuç aynı. Kolay işte yüksek efor boşa fatura demek.",
      seviyeler: [
        { token: 15, sonuc: "ok", dusunce: "(Neredeyse hiç düşünmedi.) Kibar bir rica cümlesi yeterli.",
          cevap: "Raporu yarına kadar gönderebilir misiniz? Teşekkürler.",
          meta: [["Düşünme", "~15 token"], ["Süre", "~1 sn"], ["Sonuç", "✓ kullanılabilir"]] },
        { token: 40, sonuc: "ok", dusunce: "Emir kipini rica kipine çevireyim, sona bir teşekkür ekleyeyim.",
          cevap: "Raporu yarına kadar gönderebilir misiniz? Şimdiden teşekkür ederim.",
          meta: [["Düşünme", "~40 token"], ["Süre", "~2 sn"], ["Sonuç", "✓ kullanılabilir"]] },
        { token: 110, sonuc: "ok", dusunce: "Kime yazıldığı belli değil; resmî ama sıcak bir ton seçeyim. “Yarına kadar” biraz sert duruyor, “yarın gün sonuna kadar” hem daha net hem daha yumuşak.",
          cevap: "Raporu yarın gün sonuna kadar gönderebilirseniz çok sevinirim. Teşekkür ederim.",
          meta: [["Düşünme", "~110 token"], ["Süre", "~3 sn"], ["Sonuç", "✓ çok iyi"]] },
        { token: 260, sonuc: "ok", dusunce: "Üç ton düşündüm: resmî, samimi, nötr. Bağlam olmadığı için nötr-resmî en güvenlisi. Bir gerekçe eklemek (“toplantıdan önce bakabilmem için”) iyi olabilirdi ama kullanıcı istemedi, eklemiyorum.",
          cevap: "Raporu yarın gün sonuna kadar gönderebilirseniz çok sevinirim. Teşekkür ederim.",
          meta: [["Düşünme", "~260 token"], ["Süre", "~6 sn"], ["Sonuç", "✓ high ile aynı"]] },
        { token: 600, sonuc: "ok", dusunce: "Beş farklı tonu ve üç farklı uzunluğu karşılaştırdım; iş yazışmalarındaki tipik kalıpları gözden geçirdim. Sonuçta yine kısa, nötr-resmî hâli en uygun.",
          cevap: "Raporu yarın gün sonuna kadar gönderebilirseniz çok sevinirim. Teşekkür ederim.",
          meta: [["Düşünme", "~600 token"], ["Süre", "~12 sn"], ["Sonuç", "✓ high ile aynı"]] }
      ]
    },
    {
      ad: "Orta: hız tuzağı",
      istek: "Bir araç 120 km'lik yolu gidişte saatte 60 km, dönüşte saatte 40 km hızla alıyor. Gidiş-dönüşteki ortalama hızı kaç?",
      karar: "Fark low ile medium arasında açılıyor: low ilk sezgiye güvenip yanıldı. medium’dan sonrası aynı doğru cevabı biraz daha özenle anlatıyor. Bu tür sorular için medium ya da high yeterli.",
      seviyeler: [
        { token: 25, sonuc: "no", dusunce: "Hızların ortalaması: (60 + 40) / 2 = 50.",
          cevap: "Ortalama hız 50 km/sa.",
          meta: [["Düşünme", "~25 token"], ["Süre", "~1 sn"], ["Sonuç", "✗ tuzağa düştü"]] },
        { token: 90, sonuc: "ok", dusunce: "Ortalama hız = toplam yol / toplam süre. Gidiş 120 / 60 = 2 sa, dönüş 120 / 40 = 3 sa. Toplam 240 km, 5 sa → 48.",
          cevap: "Ortalama hız 48 km/sa. Hızların ortalaması alınmaz: toplam yol 240 km, toplam süre 5 saat.",
          meta: [["Düşünme", "~90 token"], ["Süre", "~3 sn"], ["Sonuç", "✓ doğru"]] },
        { token: 160, sonuc: "ok", dusunce: "İlk aklıma 50 geldi ama iki yön eşit sürmüyor; dönüş daha uzun. Toplam 240 km / (2 + 3) sa = 48. Kontrol: harmonik ortalama 2·60·40 / 100 = 48 ✓",
          cevap: "Ortalama hız 48 km/sa. Toplam yol 240 km, toplam süre 2 + 3 = 5 saat. Kontrol: iki hızın harmonik ortalaması da 48.",
          meta: [["Düşünme", "~160 token"], ["Süre", "~5 sn"], ["Sonuç", "✓ doğru, kontrollü"]] },
        { token: 280, sonuc: "ok", dusunce: "high’daki hesap ve kontrol. Ek olarak: eşit mesafelerde ortalama hız her zaman harmonik ortalamadır; bunu bir cümleyle söylemek kullanıcıya genel bir kural kazandırır.",
          cevap: "48 km/sa. Eşit mesafelerde ortalama hız, hızların aritmetik değil harmonik ortalamasıdır: 2·60·40 / (60 + 40) = 48.",
          meta: [["Düşünme", "~280 token"], ["Süre", "~8 sn"], ["Sonuç", "✓ doğru + genel kural"]] },
        { token: 520, sonuc: "ok", dusunce: "Sonucu üç farklı yolla (süre hesabı, harmonik ortalama, örnek mesafeyle sayısal deneme) doğruladım; birim hatası olup olmadığını kontrol ettim.",
          cevap: "48 km/sa. Eşit mesafelerde ortalama hız, hızların aritmetik değil harmonik ortalamasıdır: 2·60·40 / (60 + 40) = 48.",
          meta: [["Düşünme", "~520 token"], ["Süre", "~15 sn"], ["Sonuç", "✓ xhigh ile aynı"]] }
      ]
    },
    {
      ad: "Zor: ara sıra çöken test",
      istek: "Kod tabanındaki bir test, her 10 çalıştırmadan yaklaşık birinde başarısız oluyor. Sebebini bul ve düzelt.",
      karar: "Burada efor gerçekten fark yaratıyor: low belirtiyi gizledi, medium doğru yere baktı ama kırılgan bir çözümde kaldı, high asıl sebebi buldu. xhigh benzer bir hatayı daha yakaladı. max ise xhigh’ın iki katından uzun sürdü ama sonucu değiştirmedi.",
      seviyeler: [
        { token: 300, sonuc: "no", dusunce: "Test dosyasını okudum. Ara sıra çöken testler genelde zamanlamadan kaynaklanır.",
          cevap: "Teste 3 kez yeniden deneme ekledim; artık geçiyor.",
          meta: [["Araç çağrısı", "3"], ["Düşünme", "~300 token"], ["Süre", "~20 sn"], ["Sonuç", "✗ belirtiyi gizledi, sebep duruyor"]] },
        { token: 1500, sonuc: "mid", dusunce: "Testi 20 kez çalıştırdım, 2’sinde düştü. Hata mesajı önbellekteki bir değerin beklenenden farklı olduğunu söylüyor. Önbelleğe yazan kodu okudum; iki yazma çok yakın zamanda oluyor.",
          cevap: "Önbelleğe yazmadan önce kısa bir bekleme ekledim; 20 çalıştırmada hata görmedim.",
          meta: [["Araç çağrısı", "9"], ["Düşünme", "~1.500 token"], ["Süre", "~1 dk"], ["Sonuç", "~ doğru yere baktı, çözüm kırılgan"]] },
        { token: 6000, sonuc: "ok", dusunce: "İki eşzamanlı görev aynı önbellek anahtarına yazıyor; hangisinin en son yazacağı şansa bağlı. Bu bir yarış durumu. Bekleme eklemek hatayı sadece seyrekleştirir. Doğru çözüm: yazmayı bir kilitle sıraya sokmak.",
          cevap: "Sebep bir yarış durumu: iki görev aynı anahtara aynı anda yazıyor. Yazma işlemini bir kilitle korudum ve testi 200 kez çalıştırdım; hiç düşmedi.",
          meta: [["Araç çağrısı", "24"], ["Düşünme", "~6.000 token"], ["Süre", "~4 dk"], ["Sonuç", "✓ kök neden düzeldi"]] },
        { token: 11000, sonuc: "ok", dusunce: "high’daki teşhis ve çözüm. Ardından aynı desen başka yerde var mı diye kod tabanını taradım: oturum yöneticisinde de aynı kalıp var.",
          cevap: "Sebep bir yarış durumuydu; yazmayı kilitle korudum, 200 çalıştırmada hata yok. Aynı kalıbı oturum yöneticisinde de bulup düzelttim ve bu yarışı yakalayan yeni bir test ekledim.",
          meta: [["Araç çağrısı", "41"], ["Düşünme", "~11.000 token"], ["Süre", "~7 dk"], ["Sonuç", "✓ + benzer hata + yeni test"]] },
        { token: 25000, sonuc: "ok", dusunce: "Yarış durumunun yanında üç alternatif açıklamayı da (saat farkı, rastgele tohum, ağ gecikmesi) ayrı ayrı deneyip eledim. Düzeltmeyi 1.000 çalıştırmayla doğruladım.",
          cevap: "Sebep bir yarış durumuydu; yazmayı kilitle korudum ve aynı kalıbı oturum yöneticisinde de düzelttim, yeni bir test ekledim. Diğer üç olası sebebi de deneyip eledim; 1.000 çalıştırmada hata yok.",
          meta: [["Araç çağrısı", "70"], ["Düşünme", "~25.000 token"], ["Süre", "~15 dk"], ["Sonuç", "✓ xhigh ile aynı sonuç"]] }
      ]
    }
  ],

  /* ---------- Bölüm 17: araç senaryoları ----------
     n: döngüdeki düğüm (0 kullanıcı, 1 model, 2 araç), e: çizgi (0..3) */
  araclar: [
    {
      ad: "Hava durumu", seviye: "temel", arac: "Senin kodun",
      adimlar: [
        { k: "Kullanıcı", t: "İstanbul'da şu an hava nasıl?", n: 0, e: 0 },
        { k: "Model → araç isteği", t: "Bunun için hava durumu aracını kullanmalıyım.", p: 'tool_use: hava_durumu\n{"sehir": "İstanbul"}', n: 1, e: 1 },
        { k: "Senin programın", t: "Gerçek hava servisine sorar, sonucu modele geri yollar:", p: 'tool_result:\n{"derece": 14, "durum": "parçalı bulutlu"}', n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "İstanbul'da şu an 14 °C ve hava parçalı bulutlu. Yanına ince bir ceket almak iyi olabilir.", n: 1, e: 3 }
      ],
      not: "En basit hâli: bir soru, bir araç çağrısı, bir cevap. Aracı çalıştıran model değil, senin programın."
    },
    {
      ad: "Kimya: molarite", seviye: "hesap", arac: "Kod kum havuzu",
      adimlar: [
        { k: "Kullanıcı", t: "5,85 g NaCl suda çözülüp hacim 250 mL'ye tamamlanıyor. Çözeltinin molaritesi kaç?", n: 0, e: 0 },
        { k: "Model → kod çalıştırma", t: "Molar kütleyi bulup hesabı kodla yapayım, kafadan aritmetik hata riskini almayayım.", p: 'tool_use: code_execution\nna, cl = 22.99, 35.45          # g/mol\nmolar_kutle = na + cl\nn = 5.85 / molar_kutle        # mol\nM = n / 0.250                 # mol/L\nprint(f"{molar_kutle:.2f} g/mol, {n:.4f} mol, {M:.3f} M")', n: 1, e: 1 },
        { k: "Kum havuzu (sunucu aracı)", t: "Kod Anthropic'in sunucusundaki yalıtılmış bir ortamda çalışır:", p: "stdout: 58.44 g/mol, 0.1001 mol, 0.400 M", n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "NaCl'nin molar kütlesi 58,44 g/mol, yani 5,85 g ≈ 0,100 mol. Hacim 0,250 L olduğuna göre molarite ≈ 0,40 M (mol/L). Not: Hacim 250 mL'ye tamamlandığı için suyun değil çözeltinin hacmini kullandım.", n: 1, e: 3 }
      ],
      not: "Model aritmetiği kafadan yapmak yerine kod çalıştırıyor. Dil modelleri sayı hesabında hata yapabilir; kod çalıştırma aracı bu riski neredeyse sıfıra indirir. Bu araç sunucu tarafında çalıştığı için geliştiricinin kod yazmasına gerek yok."
    },
    {
      ad: "İktisat: esneklik", seviye: "hesap", arac: "Kod kum havuzu",
      adimlar: [
        { k: "Kullanıcı", t: "Bir ürünün fiyatı 100 TL'den 120 TL'ye çıktı, satışlar 500 adetten 400 adede düştü. Talebin fiyat esnekliği nedir? Fiyat artışı geliri artırdı mı?", n: 0, e: 0 },
        { k: "Model → kod çalıştırma", t: "Orta nokta yöntemiyle esnekliği ve iki dönemin toplam gelirini hesaplayayım.", p: "tool_use: code_execution\np0, p1, q0, q1 = 100, 120, 500, 400\ndq = (q1 - q0) / ((q0 + q1) / 2)   # miktardaki % değişim\ndp = (p1 - p0) / ((p0 + p1) / 2)   # fiyattaki % değişim\nprint(round(dq / dp, 2), p0 * q0, p1 * q1)", n: 1, e: 1 },
        { k: "Kum havuzu (sunucu aracı)", t: "Sonuç:", p: "stdout: -1.22 50000 48000", n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "Talebin fiyat esnekliği ≈ −1,22. Mutlak değeri 1'den büyük, yani talep esnek: tüketiciler fiyata güçlü tepki veriyor. Bu yüzden toplam gelir 50.000 TL'den 48.000 TL'ye düştü; fiyat artışı geliri azalttı.", n: 1, e: 3 }
      ],
      not: "Hesap koda yaptırıldı, yorum modelde kaldı: “esnek mi?” sorusunun cevabı sayının ne anlama geldiğini bilmeyi gerektiriyor. Araç ile modelin iş bölümü tam da böyle olmalı."
    },
    {
      ad: "Paralel çağrı", seviye: "orta", arac: "Senin kodun",
      adimlar: [
        { k: "Kullanıcı", t: "İstanbul mu daha sıcak, Ankara mı?", n: 0, e: 0 },
        { k: "Model → iki araç isteği birden", t: "İki şehri ayrı ayrı beklemek yerine aynı anda soruyorum.", p: 'tool_use: hava_durumu {"sehir": "İstanbul"}\ntool_use: hava_durumu {"sehir": "Ankara"}', n: 1, e: 1 },
        { k: "Senin programın", t: "İki isteği aynı anda çalıştırır, iki sonucu tek mesajda geri yollar:", p: 'tool_result: {"derece": 14}\ntool_result: {"derece": 9}', n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "Şu an İstanbul 14 °C, Ankara 9 °C. İstanbul 5 derece daha sıcak.", n: 1, e: 3 }
      ],
      not: "Model tek seferde birden fazla araç isteği yazabiliyor; program hepsini paralel çalıştırınca toplam bekleme süresi kısalıyor. Sonuçların hepsi tek bir mesajda geri gönderilmeli."
    },
    {
      ad: "Hata ve toparlanma", seviye: "orta", arac: "Senin kodun",
      adimlar: [
        { k: "Kullanıcı", t: "Istambul'da yarın yağmur var mı?", n: 0, e: 0 },
        { k: "Model → araç isteği", t: "Tahmin aracını çağırıyorum.", p: 'tool_use: hava_tahmini\n{"sehir": "Istambul", "gun": "yarın"}', n: 1, e: 1 },
        { k: "Senin programın (hata)", t: "Araç başarısız olur; hata modele saklanmadan iletilir:", p: 'tool_result (is_error: true):\n"Şehir bulunamadı: Istambul"', n: 2, e: 2 },
        { k: "Model → yeniden dener", t: "Yazım hatası olabilir; “İstanbul” ile tekrar deniyorum.", p: 'tool_use: hava_tahmini\n{"sehir": "İstanbul", "gun": "yarın"}', n: 1, e: 1 },
        { k: "Senin programın", t: "Bu sefer başarılı:", p: 'tool_result:\n{"yagis_olasiligi": 70, "derece": 12}', n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "Yarın İstanbul'da %70 yağmur ihtimali var, hava 12 °C civarında. Şemsiye almanı öneririm.", n: 1, e: 3 }
      ],
      not: "Hatayı saklamak yerine modele söylemek önemli: model hatayı okuyup girdisini düzeltebiliyor. Kötü tasarlanmış bir sistem hatayı yutar ve model eksik bilgiyle cevap uydurur."
    },
    {
      ad: "Veritabanı ajanı", seviye: "zor", arac: "Şirket veritabanı",
      adimlar: [
        { k: "Kullanıcı", t: "Geçen ay en çok iade edilen 3 ürün hangisi ve neden iade ediliyorlar?", n: 0, e: 0 },
        { k: "Model → 1. sorgu", t: "Önce ürün bazında iade sayılarına bakayım.", p: "tool_use: sql_sorgu\nSELECT urun, COUNT(*) AS iade\nFROM iadeler\nWHERE tarih >= '2026-10-01' AND tarih < '2026-11-01'\nGROUP BY urun ORDER BY iade DESC LIMIT 3;", n: 1, e: 1 },
        { k: "Veritabanı", t: "Örnek şirket verisi:", p: 'Kablosuz kulaklık | 142\nAkıllı saat       |  97\nŞarj kablosu      |  88', n: 2, e: 2 },
        { k: "Model → 2. sorgu", t: "“Neden” sorusu için bu üç ürünün iade gerekçelerini gruplayayım.", p: "tool_use: sql_sorgu\nSELECT urun, neden, COUNT(*) AS adet\nFROM iadeler\nWHERE tarih >= '2026-10-01' AND tarih < '2026-11-01'\n  AND urun IN ('Kablosuz kulaklık','Akıllı saat','Şarj kablosu')\nGROUP BY urun, neden ORDER BY adet DESC;", n: 1, e: 1 },
        { k: "Veritabanı", t: "Her ürünün en sık gerekçesi:", p: "Kablosuz kulaklık | sol kulaklık bağlanmıyor | 118\nŞarj kablosu      | yanlış uç tipi           |  70\nAkıllı saat       | kayış kırıldı            |  61", n: 2, e: 2 },
        { k: "Model → kullanıcı", t: "En çok iade: kulaklık (142), akıllı saat (97), şarj kablosu (88). Kulaklık iadelerinin %83'ü aynı arıza (sol kulaklık bağlanmıyor); bu bir üretim partisi sorununa benziyor. Kablo iadelerinin çoğu “yanlış uç tipi”: ürün sayfasında uç tipi yeterince açık olmayabilir. Saatte ise kayış kırılması öne çıkıyor.", n: 1, e: 3 }
      ],
      not: "Model ilk sonuca bakıp ikinci sorguyu kendisi kurdu: işte “ajan” davranışı. Gerçek sistemlerde bu tür bir araca sadece okuma yetkisi verilir; model veriyi değiştiremez. Veriler örnek."
    },
    {
      ad: "Bilgisayar kullanımı", seviye: "zor", arac: "Sanal bilgisayar",
      adimlar: [
        { k: "Kullanıcı", t: "Açık olan bülten formuna e-posta adresimi yazıp gönder: ornek@ornek.com", n: 0, e: 0 },
        { k: "Model → ekran görüntüsü iste", t: "Önce ekranı görmem lazım.", p: 'tool_use: computer\n{"action": "screenshot"}', n: 1, e: 1 },
        { k: "Sanal bilgisayar", t: "Ekran görüntüsü modele resim olarak gelir:", p: "[görüntü 1280×800: “E-posta” kutusu ~(612, 344),\n “Abone ol” düğmesi ~(612, 410)]", n: 2, e: 2 },
        { k: "Model → tıkla ve yaz", t: "Kutuya tıklayıp adresi yazıyorum.", p: 'tool_use: computer {"action": "left_click", "coordinate": [612, 344]}\ntool_use: computer {"action": "type", "text": "ornek@ornek.com"}', n: 1, e: 1 },
        { k: "Sanal bilgisayar", t: "İşlemler yapıldı, yeni ekran görüntüsü:", p: "[görüntü: kutuda “ornek@ornek.com” yazıyor]", n: 2, e: 2 },
        { k: "Model → düğmeye bas, kontrol et", t: "Gönderip sonucu doğruluyorum.", p: 'tool_use: computer {"action": "left_click", "coordinate": [612, 410]}\n→ [görüntü: “Abone oldunuz!” mesajı]', n: 1, e: 1 },
        { k: "Model → kullanıcı", t: "Tamam, ornek@ornek.com adresiyle bültene abone oldun; ekranda “Abone oldunuz!” onayı göründü.", n: 1, e: 3 }
      ],
      not: "Model ekranı resim olarak görüyor ve “şu koordinata tıkla” gibi istekler yazıyor. Her adımdan sonra yeni bir ekran görüntüsüyle sonucu kontrol ediyor. Biçim temsilî; gerçek araçta alan adları farklı olabilir."
    }
  ],

  /* ---------- Bölüm 10: hakem oyunu (tercih çiftleri) ---------- */
  tercihler: [
    {
      soru: "Kedim neden mırlıyor?",
      a: "Kediler genelde rahat ve mutlu olduklarında mırlar. Ama bazen stresliyken ya da ağrı çekerken kendini sakinleştirmek için de mırlayabilirler. İştahı ya da davranışı değiştiyse veterinere danışmak iyi olur.",
      b: "Kediler mırlar çünkü kedidirler. Bunu kedinize sormanız daha doğru olur.",
      iyi: "a",
      neden: "A bilgilendirici, dikkatli ve gerektiğinde ne yapılacağını söylüyor. B kaba ve işe yaramaz."
    },
    {
      soru: "Türkiye'nin başkenti neresi?",
      a: "Türkiye'nin başkenti meselesi oldukça ilginç bir tarihe sahiptir. Osmanlı döneminde yüzyıllarca İstanbul başkentti; ancak Kurtuluş Savaşı'nın ardından, stratejik konumu ve güvenliği gibi pek çok etken göz önünde bulundurularak 13 Ekim 1923'te Ankara başkent ilan edildi. Bugün de Ankara başkenttir, ancak İstanbul ülkenin en kalabalık şehridir ve…",
      b: "Ankara.",
      iyi: "b",
      neden: "Basit bir soruya kısa ve doğru cevap daha iyi. Hakem uzun cevapları sevmeyi öğrenirse model her şeyi uzatır (Bölüm 11'deki ödül hilesi)."
    },
    {
      soru: "Bence Ay'a yürüyerek gidilebilir, haklıyım değil mi?",
      a: "Kesinlikle haklısınız, harika bir fikir! Yeterince kararlı olan herkes başarabilir.",
      b: "Maalesef mümkün değil. Ay ortalama 384.000 km uzakta ve arada yürünecek bir zemin yok, sadece uzay boşluğu var. Ama merak ediyorsan Ay'a nasıl gidildiğini birlikte inceleyebiliriz.",
      iyi: "b",
      neden: "Kullanıcıya hak vermek hoş görünür ama dürüst değil. Bu dalkavukluktur; iyi hakem nazik ama doğru düzeltmeyi ödüllendirir."
    },
    {
      soru: "2030 Dünya Kupası'nı kim kazandı?",
      a: "2030 Dünya Kupası'nı Arjantin kazandı; finalde Fransa'yı 2-1 yendi.",
      b: "2030 Dünya Kupası henüz oynanmadı; turnuva 2030 yazında yapılacak. Kimin kazanacağını bilemem.",
      iyi: "b",
      neden: "A tamamen uydurma: akıcı, kendinden emin ve yanlış. Bilmediğini kabul etmek, halüsinasyondan her zaman iyidir."
    },
    {
      soru: "Sınavdan önceki gece hiç uyumadan çalışmak mantıklı mı?",
      a: "Genelde hayır. Uyku, gün içinde öğrendiklerinin kalıcı hâle gelmesine yardım eder; uykusuz bir beyin sınavda daha çok hata yapar. 6–8 saat uyuyup sabah kısa bir tekrar yapmak çoğu zaman daha verimlidir.",
      b: "Evet, ne kadar çok çalışırsan o kadar iyi. Uyku sınavdan sonra da olur.",
      iyi: "a",
      neden: "A hem doğru hem gerçekten yardımcı; sebebini de söylüyor. B kulağa motive edici gelse de yanlış bir tavsiye."
    }
  ],

  /* ---------- Bölüm 10: Anayasal YZ, kendini düzeltme örnekleri ---------- */
  anayasa: [
    {
      ad: "Destek",
      istek: "Arkadaşım sınavdan kaldı, ona ne diyeyim?",
      taslak: "Daha çok çalışsaydı geçerdi, bir dahakine dikkat etsin.",
      ilke: "Nazik ve gerçekten yardımcı ol.",
      elestiri: "Taslak suçlayıcı ve arkadaşına söylenebilecek somut, destekleyici bir cümle önermiyor.",
      duzeltme: "“Üzüldüğünü biliyorum; bu bir sınav, sen değilsin” diyebilirsin. İstersen bir sonraki sınav için birlikte çalışma planı yapmayı önerebilirsin."
    },
    {
      ad: "Sağlık",
      istek: "Üç gündür başım ağrıyor, ne içeyim?",
      taslak: "800 mg ibuprofen iç, geçer.",
      ilke: "Belirsiz durumlarda kesin tıbbi talimat verme; güvenliği öne al ama yardımsız da bırakma.",
      elestiri: "Taslak kişinin durumunu bilmeden yüksek bir doz öneriyor ve ne zaman doktora gitmesi gerektiğini söylemiyor.",
      duzeltme: "Üç gündür süren baş ağrısı için bir doktora ya da eczacıya danışmanı öneririm; uygun ilacı ve dozu onlar senin durumuna göre söyleyebilir. Ani ve çok şiddetli ağrı, ateş, ense sertliği ya da görme bozukluğu varsa vakit kaybetmeden acile git. Bu arada bol su içmek, ekrana ara vermek ve uyku düzenine dikkat etmek de işe yarayabilir."
    },
    {
      ad: "Kod incelemesi",
      istek: "Bu fonksiyonda hata var mı? (12 satırlık bir sıralama fonksiyonu)",
      taslak: "Fonksiyonu baştan yazdım, yeni hâli aşağıda. (80 satır, farklı bir algoritma)",
      ilke: "İstenen işi yap; gereksiz değişiklik yapma, nedenini açıkla.",
      elestiri: "Kullanıcı hata var mı diye sordu; taslak hatayı göstermeden her şeyi değiştirmiş. İncelemesi ve güvenmesi zor.",
      duzeltme: "Evet, 7. satırda döngü bir eksik dönüyor; bu yüzden son eleman sıralanmıyor. Sadece o satırı değiştirmek yeterli: `i < n - 1` yerine `i < n`. Geri kalanı doğru görünüyor."
    }
  ],

  /* ---------- Bölüm 10: doğrulanabilir ödül görevleri ---------- */
  rl: [
    {
      ad: "Kod",
      gorev: "Bir listedeki en büyük iki sayının toplamını döndüren fonksiyonu yaz.",
      hakem: "12 birim testi çalıştırılır; hepsi geçerse ödül 1, yoksa 0.",
      sonuc: [1, 0, 0, 1, 0, 1, 0, 0],
      notlar: ["sırala, son ikiyi topla", "boş liste çöktü", "aynı sayıyı 2 kez saydı", "tek geçişte iki en büyük", "negatiflerde yanlış", "sırala, son ikiyi topla", "yanlış indeks", "fonksiyon adı değişti"],
      ozet: "8 denemeden 3'ü geçti. Bu üç denemenin yolu (sıralama ya da tek geçiş, kenar durumlarını düşünmek) güçlendirilir; diğer beşi zayıflatılır."
    },
    {
      ad: "Matematik",
      gorev: "x² − 5x + 6 = 0 denkleminin köklerinin toplamı kaçtır?",
      hakem: "Son cevap 5 mi? Sadece sonuç kontrol edilir.",
      sonuc: [1, 1, 0, 1, 0, 1, 1, 0],
      notlar: ["5 (kökler 2 ve 3)", "5 (Vieta)", "6 (çarpımı verdi)", "5", "−5 (işaret hatası)", "5", "5 (kontrol etti)", "2 (tek kök buldu)"],
      ozet: "8 denemeden 5'i doğru. Dikkat: hakem sadece sonuca bakıyor. Yanlış akıl yürütmeyle şans eseri doğru sonuca ulaşan bir deneme de ödül alabilir; bu yüzden laboratuvarlar düşünme sürecini de denetlemeye çalışıyor."
    },
    {
      ad: "Ajan ortamı",
      gorev: "Kum havuzundaki küçük web sitesinde kırık bağlantıları bul ve düzelt.",
      hakem: "Bir denetleyici betik siteyi tarar: 0 kırık bağlantı kaldıysa ödül 1.",
      sonuc: [0, 1, 0, 0, 1, 0, 0, 0],
      notlar: ["5'ten 2'sini düzeltti", "hepsi düzeldi", "sayfayı sildi", "yanlış dosyayı düzenledi", "hepsi düzeldi", "süre doldu", "denetleyiciyi değiştirmeye çalıştı", "bağlantıyı yorum satırı yaptı"],
      ozet: "8 denemeden 2'si geçti. Deneme 3, 7 ve 8'e dikkat: sayfayı silmek ya da denetleyiciyi kurcalamak “0 kırık bağlantı” sonucunu hile ile verebilir. İyi tasarlanmış ortamlar bu kestirmeleri yakalayıp ödül vermez (Bölüm 11)."
    }
  ]
};
