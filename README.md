# Yapay Zekânın İçi

Bir dil modelinin nasıl çalıştığını, nasıl eğitildiğini, nasıl “düşündüğünü”, araçları nasıl kullandığını, efor ayarının neyi değiştirdiğini ve Fable ile Sonnet arasındaki farkı sade bir Türkçeyle, oynanabilir animasyonlarla anlatan statik site.

## Dosya yapısı

```
index.html            Ana sayfa: 5 kısım, 21 bölüm
sozluk.html           Alt sayfa: aranabilir sözlük (50 terim)
kaynaklar.html        Alt sayfa: kaynaklar; kesin / temsilî / hesaplanan bilgilerin ayrımı
assets/css/site.css   Bütün sayfaların ortak stili (açık ve koyu tema)
assets/js/ornekler.js Örnek verileri: model karşılaştırmaları, araç senaryoları,
                      hakem oyunu, Anayasal YZ ve pekiştirme görevleri
assets/js/site.js     Ana sayfadaki deneylerin mantığı
assets/js/sozluk.js   Sözlük sayfasındaki arama
```

Yeni bir örnek eklemek için `assets/js/ornekler.js` içindeki ilgili listeye bir nesne eklemen yeterli; kodu değiştirmen gerekmez.

## Kullanım

Derleme ya da kurulum gerekmez. Klasörü olduğu gibi bir web sunucusuna (ya da kendi sitende bir alt klasöre) kopyala; `index.html` açılış sayfasıdır. Bağlantılar göreli olduğu için alt klasörde de çalışır. Yazı tipleri Google Fonts'tan yüklenir; internet yoksa sistem yazı tiplerine düşer.

## Not

Deneylerdeki olasılıklar, eğriler, örnek model cevapları ve başarı oranları temsilîdir ve sayfada öyle işaretlenmiştir. Model fiyatları Kasım 2026 Anthropic API liste fiyatlarıdır. Ayrıntılar `kaynaklar.html` sayfasında.
