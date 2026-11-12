---
title: Every Web Browser Explained: Tarayıcılar, Motorları ve Gizlilik Karşılaştırması
slug: every-web-browser-explained
category: ag-ve-internet
tags: tarayıcı, chrome, firefox, safari, gizlilik, blink, webkit
description: Chrome, Firefox, Safari, Edge, Opera, Brave, Vivaldi, Tor ve Samsung Internet: tarayıcı motorları (Blink, Gecko, WebKit), gizlilik ve başarım karşılaştırması.
excerpt: Chrome, Firefox, Safari, Edge, Opera, Brave, Vivaldi, Tor ve Samsung Internet arasındaki gerçek fark nedir? Tarayıcı motorlarını, sayfanın ekrana nasıl çizildiğini ve gizlilik yaklaşımlarını karşılaştırıyoruz.
cover: /assets/covers/web-browsers.svg
cover_alt: Üst üste binmiş tarayıcı pencereleri ve adres çubuklarından oluşan çizim
series: Every X Explained
series_order: 4
date: 2026-10-23 09:00
---
Günün büyük bölümünü bir tarayıcının içinde geçiriyoruz: e-posta, bankacılık, alışveriş, belgeler, video… Buna rağmen çoğumuz tarayıcıyı cihazla birlikte geleni kullanarak “seçiyoruz”. Oysa tarayıcılar birbirinden hem içlerindeki motor hem de verilerinize yaklaşımları bakımından ciddi biçimde ayrılır.

Bu yazıda önce bir tarayıcının içinde ne olduğunu ve bir web sayfasının ekrana nasıl çizildiğini anlatacağız. Ardından dokuz tarayıcıyı tek tek ele alıp motor, gizlilik ve başarım açısından karşılaştıracağız.

## Temel kavramlar

### Tarayıcı nedir, motor nedir?

**Tarayıcı**, web sunucularından HTML, CSS ve JavaScript dosyalarını alıp bunları görebildiğiniz, tıklayabildiğiniz bir sayfaya dönüştüren programdır. Bu dönüşümü yapan çekirdek bileşene **tarayıcı motoru** (rendering engine) denir. Adres çubuğu, sekmeler, yer imleri ve ayarlar gibi gördüğünüz her şey ise motorun çevresine giydirilmiş **kabuktur**.

Bir tarayıcının içinde aslında iki motor vardır:

- **İşleme (render) motoru** HTML ve CSS'i okuyup sayfayı ekrana çizer.
- **JavaScript motoru** sayfadaki betikleri çalıştırır.

Bugün kullanımda üç büyük motor ailesi kalmıştır:

| İşleme motoru | JavaScript motoru | Geliştiren | Kullanan tarayıcılar |
|---|---|---|---|
| Blink | V8 | Google (Chromium projesi) | Chrome, Edge, Opera, Brave, Vivaldi, Samsung Internet |
| Gecko | SpiderMonkey | Mozilla | Firefox, Tor Browser |
| WebKit | JavaScriptCore | Apple | Safari ve iOS'taki tarayıcılar |

Bu tablo, tarayıcı dünyasının en önemli gerçeğini gösterir: görünürde onlarca tarayıcı vardır, ama çoğu aynı motoru, yani Blink'i kullanır. Chrome, Edge, Opera, Brave ve Vivaldi'nin hepsi açık kaynaklı **Chromium** projesinin üzerine kuruludur.

### Motorların soy ağacı

Motorlar birbirinden bağımsız doğmadı. 1998'de KDE projesi Linux için **KHTML** motorunu yazdı. Apple 2001'de KHTML'i çatallayarak Safari için **WebKit**'i geliştirdi. Google, Chrome'u 2008'de WebKit ile yayımladı; 2013'te ise WebKit'i çatallayarak **Blink**'i oluşturdu. Aynı yıl Opera kendi motoru Presto'yu, 2020'de Microsoft kendi motoru EdgeHTML'i bırakıp Blink'e geçti. Gecko ise ayrı bir koldur: 1990'ların Netscape tarayıcısından Mozilla'ya uzanır.

:::svg Tarayıcı motorlarının soy ağacı: KHTML'den WebKit, WebKit'ten Blink doğdu. Gecko ayrı bir koldan, Netscape'ten gelir.
<svg viewBox="0 0 720 320" role="img" aria-label="Motor soy ağacı: 1998'de KHTML, 2001'de ondan çatallanan WebKit, 2013'te WebKit'ten çatallanan Blink. Blink'i Chrome, Edge, Opera, Brave, Vivaldi ve Samsung Internet; WebKit'i Safari; Netscape kökenli Gecko'yu Firefox ve Tor Browser kullanır" data-dur="12">
<path d="M130 70h110M370 70h110M610 70v0" class="l-mut w2" fill="none"/>
<g data-a="show .02"><rect x="20" y="42" width="110" height="56" rx="10" class="s-bg3 l-fg w2"/><text x="75" y="66" text-anchor="middle" class="t-b">KHTML</text><text x="75" y="86" text-anchor="middle" class="t-s">1998 · KDE</text></g>
<g data-a="show .14"><path d="M130 70h110m-10-6 10 6-10 6" class="l-fg w2 rc" fill="none"/><rect x="240" y="42" width="130" height="56" rx="10" class="s-bg3 l-blue w3"/><text x="305" y="66" text-anchor="middle" class="t-b">WebKit</text><text x="305" y="86" text-anchor="middle" class="t-s">2001 · Apple</text></g>
<g data-a="show .28"><path d="M370 70h110m-10-6 10 6-10 6" class="l-fg w2 rc" fill="none"/><rect x="480" y="42" width="130" height="56" rx="10" class="s-soft l-acc w3"/><text x="545" y="66" text-anchor="middle" class="t-b">Blink</text><text x="545" y="86" text-anchor="middle" class="t-s">2013 · Google</text></g>
<g data-a="show .4"><path d="M305 98v34" class="l-blue w2" fill="none"/><rect x="255" y="132" width="100" height="34" rx="8" class="s-blue"/><text x="305" y="154" text-anchor="middle" class="t-s t-w t-b">Safari</text></g>
<g data-a="show .5"><path d="M545 98v20M430 118h270M430 118v14M505 118v14M580 118v14M655 118v14M700 118v14" class="l-acc w2" fill="none"/>
<rect x="398" y="132" width="64" height="34" rx="8" class="s-acc"/><text x="430" y="154" text-anchor="middle" class="t-s t-ink t-b">Chrome</text>
<rect x="473" y="132" width="64" height="34" rx="8" class="s-acc"/><text x="505" y="154" text-anchor="middle" class="t-s t-ink t-b">Edge</text>
<rect x="548" y="132" width="64" height="34" rx="8" class="s-acc"/><text x="580" y="154" text-anchor="middle" class="t-s t-ink t-b">Opera</text>
<rect x="623" y="132" width="64" height="34" rx="8" class="s-acc"/><text x="655" y="154" text-anchor="middle" class="t-s t-ink t-b">Brave</text></g>
<g data-a="show .6"><path d="M505 166v20M580 166v20" class="l-acc w2" fill="none"/><rect x="455" y="186" width="100" height="34" rx="8" class="s-acc"/><text x="505" y="208" text-anchor="middle" class="t-s t-ink t-b">Vivaldi</text><rect x="565" y="186" width="135" height="34" rx="8" class="s-acc"/><text x="632" y="208" text-anchor="middle" class="t-s t-ink t-b">Samsung Internet</text></g>
<g data-a="show .72"><rect x="20" y="246" width="130" height="56" rx="10" class="s-bg3 l-fg w2"/><text x="85" y="270" text-anchor="middle" class="t-b">Netscape</text><text x="85" y="290" text-anchor="middle" class="t-s">1994</text><path d="M150 274h90m-10-6 10 6-10 6" class="l-fg w2 rc" fill="none"/><rect x="240" y="246" width="130" height="56" rx="10" class="s-bg3 l-acc2 w3"/><text x="305" y="270" text-anchor="middle" class="t-b">Gecko</text><text x="305" y="290" text-anchor="middle" class="t-s">1998 · Mozilla</text></g>
<g data-a="show .84"><path d="M370 274h40M410 258v32M410 258h20M410 290h20" class="l-acc2 w2" fill="none"/><rect x="430" y="242" width="110" height="32" rx="8" class="s-acc2"/><text x="485" y="263" text-anchor="middle" class="t-s t-w t-b">Firefox</text><rect x="430" y="278" width="110" height="32" rx="8" class="s-acc2"/><text x="485" y="299" text-anchor="middle" class="t-s t-w t-b">Tor Browser</text></g>
</svg>
:::

### Bir sayfa ekrana nasıl çizilir?

Motorların hepsi aşağı yukarı aynı adımları izler. Bu sürece **işleme hattı** (rendering pipeline) denir:

1. **Ayrıştırma:** HTML okunur ve sayfadaki öğelerin ağaç yapısı olan **DOM** oluşturulur. CSS okunur ve stil kurallarının ağacı olan **CSSOM** oluşturulur.
2. **Stil hesaplama:** İki ağaç birleştirilerek her görünür öğenin hangi stile sahip olacağı belirlenir.
3. **Yerleşim (layout):** Her öğenin sayfadaki konumu ve boyutu hesaplanır.
4. **Boyama (paint):** Öğeler katmanlar hâlinde piksellere dönüştürülür.
5. **Birleştirme (composite):** Katmanlar ekran kartında üst üste bindirilir ve görüntü ekrana gönderilir.

JavaScript bu hattı her an tetikleyebilir: bir öğenin genişliğini değiştirirseniz yerleşim, rengini değiştirirseniz boyama yeniden yapılır. Yalnızca `transform` ve `opacity` değişiklikleri son adımda, ekran kartında halledilebilir; akıcı animasyonların bu iki özellikle yapılmasının nedeni budur.

:::svg İşleme hattı: HTML ve CSS ayrıştırılır, stil hesaplanır, öğeler yerleştirilir, boyanır ve katmanlar birleştirilerek ekrana gönderilir.
<svg viewBox="0 0 720 230" role="img" aria-label="Tarayıcının işleme hattı: HTML'den DOM, CSS'ten CSSOM oluşur; ikisi stil hesaplamada birleşir; ardından yerleşim, boyama ve birleştirme adımları gelir ve sonuç ekrana çıkar" data-dur="9">
<rect x="20" y="30" width="90" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="65" y="58" text-anchor="middle" class="t-m">HTML</text>
<rect x="20" y="150" width="90" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="65" y="178" text-anchor="middle" class="t-m">CSS</text>
<rect x="150" y="30" width="90" height="46" rx="8" class="s-soft l-acc w2"/><text x="195" y="58" text-anchor="middle" class="t-b">DOM</text>
<rect x="150" y="150" width="90" height="46" rx="8" class="s-soft l-acc w2"/><text x="195" y="178" text-anchor="middle" class="t-b">CSSOM</text>
<rect x="280" y="90" width="90" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="325" y="118" text-anchor="middle" class="t-b">Stil</text>
<rect x="400" y="90" width="90" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="445" y="118" text-anchor="middle" class="t-b">Yerleşim</text>
<rect x="520" y="90" width="80" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="560" y="118" text-anchor="middle" class="t-b">Boyama</text>
<rect x="630" y="90" width="80" height="46" rx="8" class="s-bg3 l-fg w2"/><text x="670" y="118" text-anchor="middle" class="t-b">Birleştir</text>
<path d="M110 53h40M110 173h40M240 53l40 50M240 173l40-50M370 113h30M490 113h30M600 113h30" class="l-mut w2" fill="none"/>
<circle cx="114" cy="53" r="7" class="s-acc" data-a="move 32 0 0 .12"/><circle cx="114" cy="173" r="7" class="s-acc2" data-a="move 32 0 0 .12"/>
<circle cx="244" cy="58" r="7" class="s-acc" data-a="move 32 40 .14 .28"/><circle cx="244" cy="168" r="7" class="s-acc2" data-a="move 32 -40 .14 .28"/>
<circle cx="374" cy="113" r="7" class="s-blue" data-a="move 22 0 .32 .44"/><circle cx="494" cy="113" r="7" class="s-blue" data-a="move 22 0 .5 .62"/><circle cx="604" cy="113" r="7" class="s-blue" data-a="move 22 0 .68 .8"/>
<rect x="280" y="90" width="90" height="46" rx="8" class="s-none l-acc w3" data-a="show .28 .46"/><rect x="400" y="90" width="90" height="46" rx="8" class="s-none l-acc w3" data-a="show .44 .64"/><rect x="520" y="90" width="80" height="46" rx="8" class="s-none l-acc w3" data-a="show .62 .82"/><rect x="630" y="90" width="80" height="46" rx="8" class="s-none l-acc w3" data-a="show .8 1"/>
<text x="325" y="160" text-anchor="middle" class="t-s">hangi kural?</text><text x="445" y="160" text-anchor="middle" class="t-s">nerede, ne boyda?</text><text x="560" y="160" text-anchor="middle" class="t-s">pikseller</text><text x="670" y="160" text-anchor="middle" class="t-s">ekran kartı</text>
</svg>
:::

### Çok süreçli mimari ve korumalı alan

Eski tarayıcılar tek bir süreç olarak çalışırdı; bir sekme çöktüğünde bütün tarayıcı kapanırdı. Modern tarayıcılar işi süreçlere böler: arayüzü yöneten bir **tarayıcı süreci**, her site için ayrı **işleyici (renderer) süreçleri**, bir **ağ süreci** ve bir **GPU süreci**.

Bunun iki faydası vardır. Birincisi kararlılıktır: bir sekme çökerse diğerleri etkilenmez. İkincisi güvenliktir: işleyici süreçleri **korumalı alanda** (sandbox) çalışır; yani dosyalarınıza ya da işletim sistemine doğrudan erişemezler. Kötü niyetli bir sayfa motorda bir açık bulsa bile önce bu duvarı da aşmak zorundadır. Bedeli bellektir: her süreç kendi belleğini kullanır, bu yüzden çok sekme açmak RAM tüketir.

:::svg Her site kendi korumalı sürecinde çalışır. Bir sekme çöktüğünde diğer sekmeler ve tarayıcının kendisi çalışmaya devam eder.
<svg viewBox="0 0 720 260" role="img" aria-label="Tarayıcı süreci üç ayrı işleyici süreciyle iletişim kurar. Ortadaki sekme çöker ve kırmızıya döner; diğer iki sekme ile ağ ve GPU süreçleri çalışmayı sürdürür" data-dur="9">
<rect x="250" y="16" width="220" height="50" rx="10" class="s-soft l-acc w2"/><text x="360" y="46" text-anchor="middle" class="t-b">Tarayıcı süreci (arayüz)</text>
<path d="M360 66v24M130 90h460M130 90v24M360 90v24M590 90v24" class="l-mut w2" fill="none"/>
<g><rect x="40" y="114" width="180" height="84" rx="10" class="s-bg3 l-fg w2"/><text x="130" y="142" text-anchor="middle" class="t-b">Sekme 1</text><text x="130" y="162" text-anchor="middle" class="t-s">işleyici süreci</text><text x="130" y="182" text-anchor="middle" class="t-s t-acc">çalışıyor</text></g>
<g><rect x="270" y="114" width="180" height="84" rx="10" class="s-bg3 l-fg w2"/><text x="360" y="142" text-anchor="middle" class="t-b">Sekme 2</text><text x="360" y="162" text-anchor="middle" class="t-s">işleyici süreci</text><text x="360" y="182" text-anchor="middle" class="t-s t-acc" data-a="show 0 .4">çalışıyor</text></g>
<g data-a="show .4"><rect x="270" y="114" width="180" height="84" rx="10" class="s-none l-red w4"/><path d="M340 206l40 40M380 206l-40 40" class="l-red w4 rc"/><text x="470" y="232" class="t-s t-red t-b">çöktü</text></g>
<g><rect x="500" y="114" width="180" height="84" rx="10" class="s-bg3 l-fg w2"/><text x="590" y="142" text-anchor="middle" class="t-b">Sekme 3</text><text x="590" y="162" text-anchor="middle" class="t-s">işleyici süreci</text><text x="590" y="182" text-anchor="middle" class="t-s t-acc">çalışıyor</text></g>
<rect x="34" y="108" width="192" height="96" rx="14" class="s-none l-acc w2 dash"/><rect x="494" y="108" width="192" height="96" rx="14" class="s-none l-acc w2 dash"/>
<text x="130" y="226" text-anchor="middle" class="t-s">korumalı alan</text><text x="590" y="226" text-anchor="middle" class="t-s">korumalı alan</text>
<circle cx="130" cy="100" r="6" class="s-acc" data-a="pulse 1.5 1.4"/><circle cx="590" cy="100" r="6" class="s-acc" data-a="pulse 1.5 1.4"/>
<text x="20" y="46" class="t-s">ağ süreci · GPU süreci</text>
</svg>
:::

### İzleme, çerezler ve parmak izi

Gizlilik karşılaştırmasını anlamak için üç kavram gerekir:

- **Üçüncü taraf çerezi:** Ziyaret ettiğiniz siteye değil, o sayfaya gömülü başka bir alan adına (örneğin bir reklam ağına) ait çerezdir. Aynı reklam ağı binlerce sitede bulunduğu için sizi siteden siteye izleyebilir.
- **İzleyici (tracker):** Davranışınızı kaydetmek için sayfalara gömülen betik ve görünmez görsellerdir.
- **Parmak izi (fingerprinting):** Çerez kullanmadan, tarayıcınızın özelliklerini (ekran boyutu, yüklü yazı tipleri, grafik kartı ayrıntıları) birleştirerek sizi tanımaya çalışan yöntemdir. Engellenmesi çerezden çok daha zordur.

:::svg Üçüncü taraf çerezi: aynı reklam ağı farklı sitelerde aynı kimliği okuyarak ziyaretleri birleştirir. İzleme koruması bu isteği engeller.
<svg viewBox="0 0 720 270" role="img" aria-label="Kullanıcı önce bir haber sitesini, sonra bir alışveriş sitesini ziyaret eder. Her iki sayfa da aynı reklam ağına istek gönderir ve ağ, aynı çerez kimliğiyle iki ziyareti birleştirir. Koruma açıldığında bu istekler engellenir" data-dur="12">
<rect x="20" y="30" width="170" height="70" rx="10" class="s-bg3 l-fg w2"/><text x="105" y="60" text-anchor="middle" class="t-b">haber-sitesi.example</text><text x="105" y="82" text-anchor="middle" class="t-s">1. ziyaret</text>
<rect x="20" y="170" width="170" height="70" rx="10" class="s-bg3 l-fg w2"/><text x="105" y="200" text-anchor="middle" class="t-b">magaza.example</text><text x="105" y="222" text-anchor="middle" class="t-s">2. ziyaret</text>
<rect x="500" y="90" width="200" height="90" rx="10" class="s-bg3 l-red w2"/><text x="600" y="120" text-anchor="middle" class="t-b">reklam-agi.example</text><text x="600" y="142" text-anchor="middle" class="t-m">kimlik: u-48213</text>
<path d="M190 65 500 120M190 205 500 150" class="l-line w2" fill="none"/>
<circle cx="196" cy="66" r="8" class="s-red" data-a="move 296 52 .03 .2"/>
<text x="600" y="164" text-anchor="middle" class="t-s" data-a="show .2 .5">haber okudu</text>
<circle cx="196" cy="204" r="8" class="s-red" data-a="move 296 -52 .25 .42"/>
<text x="600" y="164" text-anchor="middle" class="t-s t-red t-b" data-a="show .42 .56">haber okudu + ayakkabı baktı</text>
<g data-a="show .6"><path d="M345 76c22 10 34 10 50 4v42c0 30-22 46-50 58-28-12-50-28-50-58V80c16 6 28 6 50-4z" class="s-acc"/><path d="M322 126l16 16 30-34" class="l-fg w4 rc" fill="none"/><text x="345" y="206" text-anchor="middle" class="t-s t-b">izleme koruması açık</text></g>
<circle cx="196" cy="66" r="8" class="s-red" data-a="move 110 20 .64 .76"/><circle cx="196" cy="204" r="8" class="s-red" data-a="move 110 -20 .78 .9"/>
<text x="600" y="206" text-anchor="middle" class="t-s t-acc t-b" data-a="show .9">istekler engellendi</text>
</svg>
:::

## Tarayıcılar tek tek

### Google Chrome

2008'de yayımlanan Chrome, hız ve sadelik vaadiyle birkaç yılda pazarın lideri oldu. Blink motorunu ve V8 JavaScript motorunu kullanır; her ikisi de açık kaynaklı Chromium projesinin parçasıdır. Web standartlarına desteği en geniş, geliştirici araçları en gelişmiş tarayıcılardan biridir ve eklenti mağazası en zengin olanıdır.

Zayıf yanı gizliliktir. Chrome, geliri reklamcılığa dayanan bir şirketin ürünüdür; varsayılan ayarlarda izleyicileri engellemez ve Google hesabınızla sıkı biçimde bütünleşir. Bellek kullanımı da çok sekmeyle yüksektir.

### Mozilla Firefox

2004'te çıkan Firefox, kâr amacı gütmeyen Mozilla Vakfı'na bağlı Mozilla Corporation tarafından geliştirilir ve Blink ya da WebKit kullanmayan tek büyük tarayıcıdır. Gecko motoru ve SpiderMonkey JavaScript motoruyla çalışır. **Gelişmiş İzleme Koruması** varsayılan olarak açıktır ve bilinen izleyicileri engeller; **Toplam Çerez Koruması** her sitenin çerezlerini ayrı bir kavanozda tutar.

Firefox'un varlığı yalnızca kullanıcıları için değil, web'in kendisi için de önemlidir: tek bir motorun egemen olduğu bir web'de standartları fiilen o motorun sahibi belirler. Bazı siteler yalnızca Chrome'da denendiği için nadiren uyum sorunu yaşanabilir.

### Apple Safari

2003'te tanıtılan Safari, WebKit ve JavaScriptCore motorlarını kullanır ve yalnızca Apple cihazlarında çalışır. Donanımla sıkı bütünleşmesi sayesinde MacBook'larda pil ömrü konusunda genellikle en verimli tarayıcıdır. **Akıllı İzleme Önleme** (ITP) üçüncü taraf çerezlerini varsayılan olarak engeller.

Safari'nin iki eleştirilen yanı vardır: yeni web özelliklerini çoğu zaman rakiplerinden geç destekler ve eklenti seçeneği sınırlıdır. Ayrıca iOS'ta bütün tarayıcılar uzun yıllar boyunca WebKit kullanmak zorundaydı; yani iPhone'daki Chrome ve Firefox aslında farklı kabuklu WebKit'ti. Avrupa Birliği'nde Dijital Pazarlar Yasası sonrasında, iOS 17.4 ile birlikte başka motorlara izin verilmiştir.

### Microsoft Edge

Edge 2015'te Internet Explorer'ın yerini almak üzere kendi motoru EdgeHTML ile çıktı. Microsoft Ocak 2020'de onu Chromium tabanlı olarak yeniden yayımladı. Bugünkü Edge, Chrome ile aynı motoru kullanır ve Chrome eklentilerini çalıştırır. Windows ile gelir; dikey sekmeler, uyuyan sekmeler ve PDF araçları gibi eklemeleri vardır. Üç kademeli izleme önleme özelliği varsayılan olarak “dengeli” düzeydedir. Eleştirilen yanı, Microsoft'un kendi servislerini (Bing, Copilot, alışveriş araçları) arayüzde yoğun biçimde öne çıkarmasıdır.

### Opera

1995'te Norveç'te doğan Opera, sekmeli gezinme ve hızlı arama gibi bugün standart olan pek çok özelliğin öncüsüdür. 2013'te kendi motoru Presto'yu bırakıp Blink'e geçti. Yerleşik reklam engelleyici, kenar çubuğundaki mesajlaşma uygulamaları ve ücretsiz bir vekil sunucu özelliği sunar. Bu özellik “VPN” adıyla anılsa da yalnızca tarayıcı trafiğini kapsar. Şirket 2016'da bir Çinli yatırımcı grubuna satılmıştır; gizlilik politikasını değerlendirirken bunu bilmek isteyebilirsiniz. Oyunculara yönelik Opera GX sürümü de vardır.

### Brave

Eski Mozilla yöneticisi ve JavaScript'in yaratıcısı Brendan Eich'in kurduğu Brave'in ilk sürümü 2016'da, 1.0 sürümü 2019'da çıktı. Chromium tabanlıdır, dolayısıyla Chrome eklentileriyle uyumludur; ama Google'a bağlı servisler çıkarılmıştır. **Shields** özelliği reklamları, izleyicileri ve parmak izi girişimlerini kurulumdan itibaren engeller. İsteğe bağlı, kripto para ödüllü bir reklam sistemi ve Tor üzerinden çalışan özel pencereler sunar. Varsayılan ayarlarıyla en sıkı korumayı sunan ana akım tarayıcılardan biridir.

### Vivaldi

Opera'nın kurucularından Jon von Tetzchner'in 2016'da yayımladığı Vivaldi, özelleştirmeyi her şeyin önüne koyar. Chromium tabanlıdır; sekme yığınları, bölünmüş ekran, kenar panelleri, yerleşik e-posta istemcisi, takvim ve not defteri sunar. Neredeyse her düğmenin yeri ve her klavye kısayolu değiştirilebilir. Yerleşik reklam ve izleyici engelleyicisi vardır. Çok ayar sunması yeni kullanıcıyı başlangıçta yorabilir.

### Tor Browser

Tor Browser, Firefox'un uzun süreli destek sürümü üzerine kurulu, anonimlik için tasarlanmış bir tarayıcıdır. Trafiğinizi **Tor ağında** gönüllülerin işlettiği üç ayrı aktarıcıdan geçirir; her aktarıcı yalnızca bir önceki ve bir sonraki adımı bilir. Böylece ziyaret ettiğiniz site IP adresinizi, internet sağlayıcınız da hangi siteye gittiğinizi göremez. Parmak izine karşı bütün kullanıcıların aynı görünmesi hedeflenir; pencere boyutu bile standartlaştırılır.

Bedeli hızdır: üç aktarıcıdan geçen bağlantı belirgin biçimde yavaştır ve bazı siteler Tor trafiğini engeller. Gündelik kullanım için değil, anonimliğin gerçekten gerektiği durumlar için uygundur.

### Samsung Internet

Samsung'un Galaxy telefon ve tabletlerine yüklü gelen bu tarayıcı Chromium tabanlıdır. Mobil kullanım için tasarlanmış alt menü çubuğu, içerik engelleyici eklenti desteği ve akıllı izleme önleme özelliği vardır. Android'de Chrome'dan sonra en yaygın tarayıcılardan biridir.

### Kısaca diğerleri

- **Chromium:** Chrome'un Google servisleri çıkarılmış açık kaynaklı çekirdeğidir; kendi başına da kullanılabilir.
- **DuckDuckGo Browser:** İzleyici engellemeyi ve tek dokunuşla veri temizlemeyi öne çıkaran, mobilde ve masaüstünde sunulan bir tarayıcıdır.
- **LibreWolf:** Firefox'un telemetrisi kapatılmış, gizlilik ayarları sıkılaştırılmış bir çatalıdır.
- **Yandex Browser:** Chromium tabanlıdır; özellikle Rusya ve çevresinde yaygındır.

## Pazar payları

:::chart
{"type":"bar","horizontal":true,"title":"Dünya genelinde tarayıcı pazar payı (tüm platformlar)","labels":["Chrome","Safari","Edge","Firefox","Opera","Samsung Internet"],"datasets":[{"label":"Pazar payı","data":[68.79,16.59,5.36,2.57,2.16,2.05]}],"unit":"%","xTitle":"Sayfa görüntülemelerindeki pay (%)","source":"StatCounter Global Stats","sourceUrl":"https://gs.statcounter.com/browser-market-share","date":"Ekim 2026","note":"StatCounter, izleme kodu bulunan sitelerdeki sayfa görüntülemelerini ölçer; değerler yaklaşıktır."}
:::

Aynı veriyi motorlara göre gruplarsak tablo daha da çarpıcı hâle gelir. Chrome, Edge, Opera ve Samsung Internet'in paylarını topladığımızda Blink'in payı yüzde 78'i geçer.

:::chart
{"type":"doughnut","title":"Motor ailelerine göre yaklaşık pay","labels":["Blink (Chrome, Edge, Opera, Samsung Internet)","WebKit (Safari)","Gecko (Firefox)","Diğer"],"datasets":[{"label":"Pay","data":[78.36,16.59,2.57,2.48]}],"unit":"%","source":"StatCounter Global Stats verisinden hesaplanmıştır","sourceUrl":"https://gs.statcounter.com/browser-market-share","date":"Ekim 2026","note":"Yaklaşık değerlerdir: iOS'taki Chrome, AB dışında WebKit kullandığı hâlde burada Blink içinde sayılmıştır; Brave ve Vivaldi “Diğer” ya da Chrome içinde görünür."}
:::

## Gizlilik karşılaştırması

Aşağıdaki tablo, tarayıcıların **kurulduktan sonraki varsayılan ayarlarını** karşılaştırır. Hemen hepsinde ayarlar sıkılaştırılabilir ya da eklentiyle koruma eklenebilir.

| Tarayıcı | Motor | Varsayılan izleyici engelleme | Üçüncü taraf çerezleri (varsayılan) | Parmak izi koruması | Açık kaynak | Geliştiren |
|---|---|---|---|---|---|---|
| Chrome | Blink | Yok | İzin verilir | Yok | Çekirdek (Chromium) | Google |
| Firefox | Gecko | Var (Gelişmiş İzleme Koruması) | Site başına yalıtılır | Kısmi | Evet | Mozilla |
| Safari | WebKit | Var (Akıllı İzleme Önleme) | Engellenir | Kısmi | Motor (WebKit) | Apple |
| Edge | Blink | Var (“dengeli” düzey) | Bilinen izleyiciler engellenir | Yok | Çekirdek (Chromium) | Microsoft |
| Opera | Blink | İsteğe bağlı (kapalı gelir) | İzin verilir | Yok | Çekirdek (Chromium) | Opera |
| Brave | Blink | Var (Shields) | Engellenir | Var (rastgeleleştirme) | Evet | Brave Software |
| Vivaldi | Blink | İsteğe bağlı | İzin verilir | Yok | Büyük bölümü | Vivaldi Technologies |
| Tor Browser | Gecko | Var | Oturum sonunda silinir | Güçlü (tek tip görünüm) | Evet | Tor Project |
| Samsung Internet | Blink | Var (akıllı izleme önleme) | Bilinen izleyiciler engellenir | Yok | Çekirdek (Chromium) | Samsung |

:::info Gizli sekme sizi görünmez yapmaz
Gizli (özel) gezinme modu yalnızca o oturumun geçmişini, çerezlerini ve form verilerini cihazınıza kaydetmez. İnternet sağlayıcınız, işvereniniz ve ziyaret ettiğiniz siteler sizi görmeye devam eder. Ağ düzeyinde gizlilik için VPN ya da Tor gerekir.
:::

## Başarım: hangisi daha hızlı?

Tarayıcı hızı üç ayrı şeydir: sayfaların yüklenme süresi, sayfa içi etkileşimlerin akıcılığı ve kaynak (bellek, pil) kullanımı. Bunları ölçmek için kullanılan başlıca kıyaslamalar şunlardır:

- **Speedometer 3:** Gerçek web uygulamalarına benzeyen iş yüklerinde tepki hızını ölçer. Apple, Google, Mozilla ve Microsoft'un birlikte geliştirdiği ortak bir ölçüttür.
- **JetStream 2:** JavaScript ve WebAssembly'nin ham hesaplama hızını ölçer.
- **MotionMark:** Grafik ve animasyon başarımını ölçer.

Bu yazıda bilerek tek bir “hız sıralaması” vermiyoruz. Çünkü skorlar cihaza, işletim sistemine ve tarayıcı sürümüne göre değişir; bir Mac'te Safari öne çıkarken aynı testte bir Windows bilgisayarda Chrome ya da Edge öne çıkabilir. Üstelik üç motor da yıllardır aynı kıyaslamalara göre geliştirildiği için aralarındaki fark gündelik kullanımda çoğu zaman hissedilmez. Kendi cihazınızda [browserbench.org](https://browserbench.org/) üzerinden bu testleri birkaç dakikada çalıştırabilirsiniz.

Pratikte farkı belirleyen genellikle şunlardır:

| Etken | Etkisi |
|---|---|
| Reklam ve izleyici engelleme | Engellenen her istek sayfayı hafifletir; koruması açık tarayıcılar çoğu sitede daha hızlı açılır |
| Açık sekme ve eklenti sayısı | Her biri bellek ve işlemci kullanır; tarayıcıdan bağımsız olarak yavaşlatır |
| Platform bütünleşmesi | Safari macOS'ta, Edge Windows'ta pil ve bellek yönetiminde avantajlıdır |
| Uyuyan sekmeler | Edge, Chrome, Opera ve Vivaldi kullanılmayan sekmeleri askıya alarak belleği boşaltır |

## Hangisini seçmeliyim?

| Önceliğiniz | Makul seçim | Neden |
|---|---|---|
| Sorunsuz uyumluluk, en geniş eklenti seçeneği | Chrome | Siteler en çok onunla denenir |
| Gizlilik, ama ayarla uğraşmadan | Brave | Engelleme kurulumdan itibaren açık |
| Bağımsız motor, açık kaynak, dengeli gizlilik | Firefox | Blink dışındaki tek büyük seçenek |
| Mac, iPhone ve iPad arasında bütünlük, pil ömrü | Safari | Apple ekosistemiyle eşitleme |
| Windows ile bütünleşme, iş kullanımı | Edge | Sistemle gelir, Chrome eklentileri çalışır |
| Her ayrıntıyı özelleştirmek | Vivaldi | Arayüzün tamamı ayarlanabilir |
| Yerleşik araçlar (kenar çubuğu, vekil sunucu) | Opera | Ek kurulum gerektirmez |
| Gerçek anonimlik | Tor Browser | Trafik üç aktarıcıdan geçer |
| Galaxy telefon | Samsung Internet | Tek elle kullanıma uygun, içerik engelleyici desteği |

Tek bir tarayıcıya bağlı kalmak zorunda da değilsiniz. Pek çok kişi iş hesapları için bir tarayıcı, kişisel gezinme için bir başkasını kullanarak hem düzeni hem gizliliği artırır.

## Sık sorulan sorular

:::faq
Q: Chromium tabanlı tarayıcıların hepsi aynı mı?
A: Motorları aynıdır; bu yüzden siteler hepsinde aynı görünür ve Chrome eklentileri çalışır. Fark, motorun üzerine eklenenlerdedir: arayüz, eşitleme servisleri, varsayılan gizlilik ayarları ve şirketin veriyle ne yaptığı.
Q: Tarayıcı motorlarının azalması neden sorun?
A: Web standartları, birden çok bağımsız motorun aynı kurallara uymasıyla anlam kazanır. Tek bir motor egemen olursa siteler yalnızca onun davranışına göre yazılır ve standartları fiilen o motorun sahibi belirler.
Q: En güvenli tarayıcı hangisi?
A: Güncel tutulan bütün büyük tarayıcılar korumalı alan ve otomatik güncelleme sunar. Güvenlik açısından en önemli etken tarayıcıyı güncel tutmak ve güvenmediğiniz eklentileri kurmamaktır. Gizlilik ise ayrı bir konudur.
Q: Reklam engelleyici kullanmalı mıyım?
A: Reklam ve izleyici engelleme sayfaları hızlandırır, veri kullanımını azaltır ve kötü amaçlı reklamlara karşı korur. Öte yandan pek çok site gelirini reklamdan sağlar; desteklemek istediğiniz sitelerde engelleyiciyi kapatabilirsiniz.
Q: iPhone'da Chrome kullanmak Safari kullanmaktan farklı mı?
A: Avrupa Birliği dışında iOS'taki bütün tarayıcılar WebKit motorunu kullanır; yani sayfalar aynı motorla çizilir. Fark arayüzde, eşitlemede ve ek özelliklerdedir.
Q: Tarayıcım neden bu kadar çok RAM kullanıyor?
A: Her sekme ve eklenti güvenlik ve kararlılık için ayrı bir süreçte çalışır. Ayrıca modern web sayfaları başlı başına birer uygulamadır. Kullanmadığınız sekmeleri kapatmak ya da uyuyan sekme özelliğini açmak en etkili çözümdür.
Q: Parolalarımı tarayıcıya kaydetmek güvenli mi?
A: Tarayıcıların yerleşik parola yöneticileri, aynı parolayı her yerde kullanmaktan çok daha güvenlidir. Cihazınızın kilidinin güçlü olması ve eşitleme hesabınızda iki adımlı doğrulamanın açık olması gerekir.
:::

## Özet

- Bir tarayıcı, işleme motoru ile JavaScript motorunun çevresine kurulmuş bir kabuktur.
- Üç motor ailesi kalmıştır: Blink (Chrome, Edge, Opera, Brave, Vivaldi, Samsung Internet), Gecko (Firefox, Tor) ve WebKit (Safari).
- Sayfa; ayrıştırma, stil, yerleşim, boyama ve birleştirme adımlarından geçerek ekrana çizilir.
- Çok süreçli mimari ve korumalı alan, kararlılık ve güvenlik sağlar; bedeli bellek kullanımıdır.
- Gizlilikte asıl fark varsayılan ayarlardadır: Brave, Firefox, Safari ve Tor izlemeyi kutudan çıktığı gibi sınırlar; Chrome sınırlamaz.
- Hız farkları cihaza göre değişir ve gündelik kullanımda çoğu zaman küçüktür; engellenen izleyiciler çoğu zaman motor farkından daha büyük etki yapar.

## Kaynaklar

- [StatCounter Global Stats – Browser Market Share Worldwide](https://gs.statcounter.com/browser-market-share)
- [Chromium Projesi – Blink (Rendering Engine)](https://www.chromium.org/blink/)
- [Chromium Blog – “Blink: A rendering engine for the Chromium project” (2013)](https://blog.chromium.org/2013/04/blink-rendering-engine-for-chromium.html)
- [Chrome for Developers – Inside look at modern web browser](https://developer.chrome.com/blog/inside-browser-part1)
- [MDN Web Docs – How browsers work](https://developer.mozilla.org/en-US/docs/Web/Performance/Guides/How_browsers_work)
- [Mozilla Support – Enhanced Tracking Protection in Firefox](https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop)
- [WebKit – Tracking Prevention in WebKit](https://webkit.org/tracking-prevention/)
- [Microsoft Edge Blog – “Microsoft Edge: Making the web better through more open source collaboration” (2018)](https://blogs.windows.com/windowsexperience/2018/12/06/microsoft-edge-making-the-web-better-through-more-open-source-collaboration/)
- [Brave – Shields belgeleri](https://brave.com/shields/)
- [Tor Project – Tor Browser Kullanım Kılavuzu](https://tb-manual.torproject.org/)
- [BrowserBench – Speedometer, JetStream ve MotionMark](https://browserbench.org/)
- [Apple Developer – Using alternative browser engines in the European Union](https://developer.apple.com/support/alternative-browser-engines/)
