# Yapay Zekânın İçi

Bir dil modelinin nasıl çalıştığını, nasıl eğitildiğini, nasıl “düşündüğünü”, araçları nasıl kullandığını, efor ayarının neyi değiştirdiğini ve Fable ile Sonnet arasındaki farkı sade bir Türkçeyle, oynanabilir animasyonlarla anlatan statik site.

## Dosya yapısı

```
index.html                    Ana sayfa: 5 kısım, 21 bölüm
sozluk.html                   Alt sayfa: aranabilir sözlük (50 terim)
kaynaklar.html                Alt sayfa: kaynaklar; kesin / temsilî / hesaplanan bilgilerin ayrımı
test.html                     Alt sayfa: 15 soruluk "Kendini test et"
assets/css/site.css           Bütün sayfaların ortak stili (açık ve koyu tema)
assets/js/ornekler.js         Örnek verileri: anlam haritası, eğitim örnekleri, model karşılaştırmaları,
                              düşünme ve efor örnekleri, araç senaryoları, hakem oyunu
assets/js/gercek-sonuclar.js  Gerçek Sonnet / Fable karşılaştırma sonuçları (betik doldurur)
assets/js/site.js             Ana sayfadaki deneylerin mantığı
assets/js/sozluk.js           Sözlük sayfasındaki arama
assets/js/test.js             Test soruları ve mantığı
araclar/gercek_karsilastirma.py  İki modeli gerçekten karşılaştıran betik
```

Yeni bir örnek eklemek için `assets/js/ornekler.js` içindeki ilgili listeye bir nesne eklemen yeterli; kodu değiştirmen gerekmez.

## Gerçek model karşılaştırması

Bölüm 19'daki Sonnet 5.5 / Fable 5.2 örnekleri, `assets/js/gercek-sonuclar.js` boş olduğu sürece temsilîdir. Gerçek sonuçlarla doldurmak için:

```bash
pip install anthropic
export ANTHROPIC_API_KEY=...   # kendi anahtarın
python3 araclar/gercek_karsilastirma.py
```

Betik 8 görevi iki modele de 3'er kez sorar; süreyi, token'ı ve maliyeti ölçer, kontrol edilebilen görevlerde (sayısal sonuç, kod testleri) doğruluğu otomatik puanlar ve sonucu siteye yazar. Kod görevlerinde modelin yazdığı kod senin bilgisayarında ayrı bir süreçte, 10 saniyelik sınırla çalıştırılır. Daha ucuz bir deneme için `--tekrar 1` kullanabilirsin.

## Kullanım

Derleme ya da kurulum gerekmez. Klasörü olduğu gibi bir web sunucusuna (ya da kendi sitende bir alt klasöre) kopyala; `index.html` açılış sayfasıdır. Bağlantılar göreli olduğu için alt klasörde de çalışır. Yazı tipleri Google Fonts'tan yüklenir; internet yoksa sistem yazı tiplerine düşer.

## Not

Deneylerdeki olasılıklar, eğriler, örnek model cevapları ve başarı oranları temsilîdir ve sayfada öyle işaretlenmiştir. Model fiyatları Kasım 2026 Anthropic API liste fiyatlarıdır. Ayrıntılar `kaynaklar.html` sayfasında.
