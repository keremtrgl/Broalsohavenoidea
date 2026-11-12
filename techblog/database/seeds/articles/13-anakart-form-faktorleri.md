---
title: Every Motherboard Form Factor Explained: ATX, Micro-ATX, Mini-ITX ve Diğerleri
slug: every-motherboard-form-factor-explained
category: donanim
tags: anakart, atx, mini-itx, yonga seti, soket, pcie
description: E-ATX, ATX, Micro-ATX, Mini-ITX, Mini-DTX, XL-ATX ve BTX anakart boyutları; boyut karşılaştırması, yonga seti ve soket kavramları, yuvalar ve portlar.
excerpt: ATX, Micro-ATX ve Mini-ITX arasındaki fark yalnızca boyut mu? Anakart form faktörlerini gerçek ölçüleriyle karşılaştırıyor; yonga seti, soket, PCIe, M.2 ve arka paneldeki bağlantı noktalarını tek tek açıklıyoruz.
cover: /assets/covers/motherboards.svg
cover_alt: Üzerinde işlemci yuvası, bellek yuvaları ve genişleme yuvaları bulunan bir anakartın üstten çizimi
series: Every X Explained
series_order: 13
date: 2026-11-01 09:00
---
Bilgisayar toplarken işlemciyi ve ekran kartını uzun uzun karşılaştırır, anakartı ise çoğu zaman “uyumlu olan en ucuzu” diye seçeriz. Oysa anakart, hangi kasayı kullanabileceğinizi, kaç disk ve kaç kart takabileceğinizi, ileride hangi işlemciye geçebileceğinizi belirleyen parçadır. Bu kararların ilki de boyuttur.

Bu yazıda anakart **form faktörlerini** gerçek ölçüleriyle karşılaştıracak, ardından bir anakartın üzerindeki her şeyi açıklayacağız: yonga seti, soket, yuvalar ve arka paneldeki bağlantı noktaları.

## Temel kavramlar

### Anakart ne iş yapar?

**Anakart**, bilgisayarın bütün parçalarını birbirine bağlayan baskılı devre kartıdır. İşlemci, bellek, ekran kartı ve diskler ona takılır; anakart bu parçalar arasında hem veriyi hem de elektriği taşır. Kendi başına bir hesap yapmaz, ama hangi parçaların birlikte çalışabileceğini o belirler.

### Form faktörü nedir?

**Form faktörü**, bir anakartın fiziksel standardıdır: kartın boyutlarını, vida deliklerinin yerlerini, arka paneldeki bağlantı noktalarının konumunu ve güç bağlantılarını tanımlar. Bu standartlar sayesinde herhangi bir üreticinin ATX anakartı, herhangi bir üreticinin ATX kasasına uyar.

Bilinmesi gereken önemli bir kural vardır: ATX ailesindeki küçük kartların vida delikleri, büyük kartlardakilerin bir alt kümesidir. Bu yüzden **büyük bir kasaya küçük bir anakart takabilirsiniz**, ama tersi mümkün değildir. ATX bir kasa Micro-ATX ve Mini-ITX kartları da kabul eder.

:::svg Form faktörlerinin gerçek oranlarıyla boyut karşılaştırması. Kartlar sol üst köşeden, yani arka panel tarafından hizalanır.
<svg viewBox="0 0 720 340" role="img" aria-label="İç içe çerçeveler hâlinde anakart boyutları: en küçük Mini-ITX 170 x 170 milimetre, Mini-DTX 203 x 170, Micro-ATX 244 x 244, ATX 305 x 244, XL-ATX yaklaşık 345 x 262 ve E-ATX 305 x 330 milimetre" data-dur="12">
<g data-a="show .74"><rect x="20" y="20" width="281" height="259" rx="4" class="s-bg3 l-red w2"/><text x="296" y="270" text-anchor="end" class="t-s t-b">E-ATX</text></g>
<g data-a="show .62"><rect x="20" y="20" width="223" height="293" rx="4" class="s-bg2 l-pur w2"/><text x="238" y="304" text-anchor="end" class="t-s t-b">XL-ATX</text></g>
<g data-a="show .5"><rect x="20" y="20" width="207" height="259" rx="4" class="s-bg3 l-blue w3"/><text x="222" y="270" text-anchor="end" class="t-s t-b">ATX</text></g>
<g data-a="show .36"><rect x="20" y="20" width="207" height="207" rx="4" class="s-bg2 l-acc2 w3"/><text x="222" y="218" text-anchor="end" class="t-s t-b">Micro-ATX</text></g>
<g data-a="show .2"><rect x="20" y="20" width="145" height="173" rx="4" class="s-bg3 l-fg w2"/><text x="160" y="184" text-anchor="end" class="t-s t-b">Mini-DTX</text></g>
<g data-a="show .04"><rect x="20" y="20" width="145" height="145" rx="4" class="s-soft l-acc w3"/><text x="92" y="98" text-anchor="middle" class="t-b">Mini-ITX</text></g>
<g>
<text x="340" y="44" class="t-b">Boyutlar (yükseklik × genişlik)</text>
<text x="340" y="74" class="t-s" data-a="show .04">Mini-ITX · 170 × 170 mm</text>
<text x="340" y="100" class="t-s" data-a="show .2">Mini-DTX · 203 × 170 mm</text>
<text x="340" y="126" class="t-s" data-a="show .36">Micro-ATX · 244 × 244 mm</text>
<text x="340" y="152" class="t-s" data-a="show .5">ATX · 305 × 244 mm</text>
<text x="340" y="178" class="t-s" data-a="show .62">XL-ATX · yaklaşık 345 × 262 mm</text>
<text x="340" y="204" class="t-s" data-a="show .74">E-ATX · 305 × 330 mm</text>
<text x="340" y="250" class="t-s">Ölçek: çizimde 1 mm ≈ 0,85 birim.</text>
<text x="340" y="270" class="t-s">ATX'ten E-ATX'e yalnızca genişlik artar;</text><text x="340" y="288" class="t-s">XL-ATX ise asıl olarak yükseklikte büyür.</text>
</g>
</svg>
:::

## Form faktörleri tek tek

### ATX

**ATX** (Advanced Technology eXtended), Intel'in 1995'te yayımladığı ve o günden beri masaüstü bilgisayarların temel standardı olan form faktörüdür. Boyutu **305 × 244 mm**'dir. Kendinden önceki AT standardının sorunlarını çözdü: bağlantı noktalarını arka panelde tek bir blokta topladı, güç kaynağı bağlantısını standartlaştırdı ve bilgisayarın yazılımla kapatılabilmesini sağladı.

Tipik bir ATX kartta dört bellek yuvası, yedi genişleme yuvasına kadar yer ve çok sayıda M.2, SATA ve fan bağlantısı bulunur. Parçalar arasında boşluk olduğu için montaj kolaydır ve hava akışı iyidir. Çoğu kullanıcı için varsayılan seçimdir.

### Micro-ATX

**Micro-ATX** (mATX), 1997'de tanıtıldı ve **244 × 244 mm** boyutundadır: ATX ile aynı genişlikte, ama daha kısadır. Kısalmanın bedeli genişleme yuvalarıdır: en fazla dört yuva sığar. Bellek yuvası sayısı çoğunlukla yine dörttür.

Micro-ATX kartlar genellikle en uygun fiyatlı seçeneklerdir, çünkü hazır sistemlerde ve bütçe bilgisayarlarında çok kullanılırlar. Tek ekran kartı ve birkaç disk kullanacak biri için işlevsel olarak ATX'ten eksik bir yanı yoktur.

### Mini-ITX

**Mini-ITX**, VIA Technologies'in 2001'de geliştirdiği **170 × 170 mm**'lik form faktörüdür. Başlangıçta düşük güçlü, gömülü sistemler için tasarlanmıştı; bugün küçük kasalı güçlü bilgisayarların temelidir.

Bu kadar küçük bir karta sığanlar sınırlıdır: tek bir genişleme yuvası (ekran kartı için), iki bellek yuvası ve az sayıda fan ve disk bağlantısı. Üreticiler yer kazanmak için M.2 yuvalarını kartın arkasına yerleştirir. Mühendislik zorluğu ve düşük üretim adedi nedeniyle Mini-ITX kartlar, benzer özellikli ATX kartlardan çoğu zaman daha pahalıdır. Küçük kasalarda soğutma ve kablo düzeni de daha fazla özen ister.

### Mini-DTX ve DTX

AMD, 2007'de **DTX** (244 × 203 mm) ve **Mini-DTX** (203 × 170 mm) standartlarını duyurdu. Mini-DTX, Mini-ITX ile aynı genişliktedir ama biraz daha uzundur; bu fazladan alan ikinci bir genişleme yuvasına izin verir. Standart yaygınlaşmadı, ama fikir yaşamaya devam ediyor: bazı üreticiler, iki yuvalı küçük kasalar için Mini-DTX boyutunda üst düzey kartlar üretmektedir.

### E-ATX

**E-ATX** (Extended ATX), ATX ile aynı yükseklikte ama daha geniş kartlardır: tam boyutu **305 × 330 mm**'dir. Fazladan alan; daha fazla bellek yuvası (sekize kadar), daha güçlü güç devreleri, daha çok M.2 yuvası ve birden çok ekran kartı için kullanılır. İş istasyonu ve uç düzey masaüstü (HEDT) platformlarının kartları bu boyuttadır.

:::warn E-ATX tek bir ölçü değildir
E-ATX adı sıkı biçimde standartlaştırılmamıştır. Üreticiler, ATX'ten yalnızca biraz daha geniş (örneğin 305 × 272 mm) kartları da “E-ATX” olarak adlandırır. Bir E-ATX kart alacaksanız, kartın gerçek genişliğini milimetre olarak kasanızın desteklediği en büyük genişlikle karşılaştırın; yalnızca “E-ATX destekler” ifadesine güvenmeyin.
:::

Sunucu dünyasında benzer boyutlar **SSI CEB** (305 × 267 mm) ve **SSI EEB** (305 × 330 mm) adlarıyla standartlaştırılmıştır; çift işlemcili sunucu kartları genellikle EEB boyutundadır.

### XL-ATX

**XL-ATX**, ATX'ten daha **uzun** kartlar için kullanılan addır; fazladan yükseklik sekiz ya da daha çok genişleme yuvasına yer açar. Resmî bir standardı yoktur ve ölçüler üreticiden üreticiye değişir; yaklaşık 345 × 262 mm yaygın bir değerdir. Üç ve dört ekran kartlı sistemlerin popüler olduğu dönemde ortaya çıktı; çoklu ekran kartı kullanımının oyunlarda terk edilmesiyle birlikte bugün çok nadir görülür.

### BTX

**BTX** (Balanced Technology eXtended), Intel'in 2004'te ATX'in yerini alması için önerdiği standarttır. Boyutu **325 × 266 mm**'dir. Amacı, o dönemin çok ısınan Pentium 4 işlemcilerini daha iyi soğutmaktı: işlemci kasanın ön tarafına, hava girişinin hemen arkasına taşındı ve bileşenler tek bir düz hava kanalı üzerinde dizildi; kart yerleşimi ATX'in ayna görüntüsü gibiydi.

BTX tutmadı. Kasaların ve soğutucuların tümüyle değişmesini gerektiriyordu ve Intel kısa süre sonra çok daha az ısınan Core mimarisine geçince çözmeye çalıştığı sorun ortadan kalktı. Intel 2006'da BTX geliştirmeyi bıraktı. Bugün yalnızca o dönemin bazı hazır sistemlerinde karşınıza çıkar.

### Daha küçükler ve daha eskiler

- **Nano-ITX** (120 × 120 mm) ve **Pico-ITX** (100 × 72 mm): gömülü sistemler, kiosklar ve endüstriyel cihazlar için.
- **NUC ve benzeri mini bilgisayarlar:** Yaklaşık 100 × 100 mm'lik, üreticiye özgü kartlar kullanır; standart bir kasaya takılmaz.
- **AT ve Baby-AT:** 1980'lerin ve 1990'ların başının standartları; ATX'in gelişiyle ortadan kalktı.
- **Üreticiye özgü kartlar:** Hazır markalı bilgisayarların bir bölümü standart dışı kart, güç bağlantısı ve kasa kullanır; bu sistemlerde anakartı ya da güç kaynağını değiştirmek zor olabilir.

## Boyut karşılaştırması

| Form faktörü | Boyut (mm) | Alan (cm²) | Tipik bellek yuvası | En çok genişleme yuvası | Çıkış yılı |
|---|---|---|---|---|---|
| Mini-ITX | 170 × 170 | 289 | 2 | 1 | 2001 |
| Mini-DTX | 203 × 170 | 345 | 2 | 2 | 2007 |
| Micro-ATX | 244 × 244 | 595 | 2–4 | 4 | 1997 |
| ATX | 305 × 244 | 744 | 4 | 7 | 1995 |
| BTX | 325 × 266 | 865 | 4 | 7 | 2004 |
| XL-ATX | yaklaşık 345 × 262 | yaklaşık 904 | 4–8 | 8–9 | 2009 dolayları |
| E-ATX / SSI EEB | 305 × 330 | 1.007 | 4–8 | 7 | — |

:::chart
{"type":"bar","title":"Form faktörlerine göre anakart yüzey alanı","labels":["Mini-ITX","Mini-DTX","Micro-ATX","ATX","BTX","XL-ATX","E-ATX"],"datasets":[{"label":"Alan (cm²)","data":[289,345,595,744,865,904,1007]}],"unit":"cm²","yTitle":"Yüzey alanı (cm²)","source":"Form faktörü belirtimlerindeki ölçülerden hesaplanmıştır (Intel ATX ve microATX belirtimleri, VIA Mini-ITX; DTX ve SSI EEB için yayımlanmış standart ölçüler)","sourceUrl":"https://www.intel.com/content/www/us/en/support/articles/000005819/boards-and-kits.html","date":"2026-11","note":"XL-ATX resmî bir standart olmadığından değeri yaklaşıktır."}
:::

Grafik, Mini-ITX'in neden pahalı ve zor olduğunu açıklar: aynı işlemciyi, aynı güç devrelerini ve aynı bağlantıları, ATX'in yüzde 40'ından küçük bir alana sığdırmak gerekir.

## Anakartın üzerinde neler var?

:::svg Bir ATX anakartın başlıca bölümleri. Vurgulanan bölüm sırayla değişir.
<svg viewBox="0 0 720 400" role="img" aria-label="Üstten görünümde bir ATX anakart ve numaralı bölümleri: işlemci soketi, güç devreleri, bellek yuvaları, 24 iğneli güç bağlantısı, PCIe x16 yuvası, M.2 yuvası, yonga seti, SATA portları ve arka panel bağlantıları" data-dur="14">
<rect x="20" y="20" width="330" height="360" rx="8" class="s-bg3 l-fg w2"/>
<rect x="28" y="30" width="34" height="130" rx="4" class="s-bg2 l-fg w1"/>
<rect x="80" y="30" width="150" height="24" rx="4" class="s-bg2 l-fg w1"/><rect x="80" y="60" width="22" height="90" rx="4" class="s-bg2 l-fg w1"/>
<rect x="124" y="74" width="84" height="84" rx="6" class="s-bg2 l-fg w2"/><rect x="142" y="92" width="48" height="48" rx="3" class="s-line"/>
<g class="s-bg2 l-fg w1"><rect x="246" y="40" width="12" height="150" rx="2"/><rect x="264" y="40" width="12" height="150" rx="2"/><rect x="282" y="40" width="12" height="150" rx="2"/><rect x="300" y="40" width="12" height="150" rx="2"/></g>
<rect x="326" y="70" width="16" height="90" rx="3" class="s-bg2 l-fg w1"/>
<rect x="60" y="212" width="230" height="14" rx="3" class="s-bg2 l-fg w1"/><rect x="60" y="266" width="230" height="14" rx="3" class="s-bg2 l-fg w1"/><rect x="60" y="320" width="90" height="14" rx="3" class="s-bg2 l-fg w1"/>
<rect x="90" y="186" width="130" height="18" rx="4" class="s-bg2 l-fg w1"/>
<rect x="246" y="290" width="60" height="60" rx="6" class="s-bg2 l-fg w1"/>
<g class="s-bg2 l-fg w1"><rect x="322" y="230" width="20" height="18" rx="2"/><rect x="322" y="254" width="20" height="18" rx="2"/><rect x="322" y="278" width="20" height="18" rx="2"/><rect x="322" y="302" width="20" height="18" rx="2"/></g>
<rect x="124" y="74" width="84" height="84" rx="6" class="s-acc" data-a="show .02 .12"/>
<g class="s-acc" data-a="show .12 .22"><rect x="80" y="30" width="150" height="24" rx="4"/><rect x="80" y="60" width="22" height="90" rx="4"/></g>
<g class="s-acc" data-a="show .22 .32"><rect x="246" y="40" width="12" height="150" rx="2"/><rect x="264" y="40" width="12" height="150" rx="2"/><rect x="282" y="40" width="12" height="150" rx="2"/><rect x="300" y="40" width="12" height="150" rx="2"/></g>
<rect x="326" y="70" width="16" height="90" rx="3" class="s-acc" data-a="show .32 .42"/>
<rect x="60" y="212" width="230" height="14" rx="3" class="s-acc" data-a="show .42 .52"/>
<rect x="90" y="186" width="130" height="18" rx="4" class="s-acc" data-a="show .52 .62"/>
<rect x="246" y="290" width="60" height="60" rx="6" class="s-acc" data-a="show .62 .72"/>
<g class="s-acc" data-a="show .72 .82"><rect x="322" y="230" width="20" height="18" rx="2"/><rect x="322" y="254" width="20" height="18" rx="2"/><rect x="322" y="278" width="20" height="18" rx="2"/><rect x="322" y="302" width="20" height="18" rx="2"/></g>
<rect x="28" y="30" width="34" height="130" rx="4" class="s-acc" data-a="show .82 .96"/>
<g>
<text x="380" y="48" class="t-b" data-a="show .02 .12">▶</text><text x="400" y="48">1 · İşlemci soketi</text>
<text x="380" y="84" class="t-b" data-a="show .12 .22">▶</text><text x="400" y="84">2 · Güç devreleri (VRM)</text>
<text x="380" y="120" class="t-b" data-a="show .22 .32">▶</text><text x="400" y="120">3 · Bellek (DIMM) yuvaları</text>
<text x="380" y="156" class="t-b" data-a="show .32 .42">▶</text><text x="400" y="156">4 · 24 iğneli güç bağlantısı</text>
<text x="380" y="192" class="t-b" data-a="show .42 .52">▶</text><text x="400" y="192">5 · PCIe x16 yuvası (ekran kartı)</text>
<text x="380" y="228" class="t-b" data-a="show .52 .62">▶</text><text x="400" y="228">6 · M.2 yuvası (SSD)</text>
<text x="380" y="264" class="t-b" data-a="show .62 .72">▶</text><text x="400" y="264">7 · Yonga seti</text>
<text x="380" y="300" class="t-b" data-a="show .72 .82">▶</text><text x="400" y="300">8 · SATA portları</text>
<text x="380" y="336" class="t-b" data-a="show .82 .96">▶</text><text x="400" y="336">9 · Arka panel bağlantıları</text>
</g>
</svg>
:::

### Soket

**Soket**, işlemcinin anakarta oturduğu yuvadır. İşlemci ile anakartın soketi birebir aynı olmak zorundadır; farklı soketli bir işlemci fiziksel olarak yuvaya girmez. Üç temel soket türü vardır:

- **LGA** (Land Grid Array): İğneler anakarttaki sokettedir; işlemcinin altında yalnızca düz temas noktaları bulunur. Intel'in masaüstü soketleri ve AMD'nin AM5'i bu türdendir.
- **PGA** (Pin Grid Array): İğneler işlemcinin üzerindedir ve soketteki deliklere girer. AMD'nin AM4 soketi böyledir.
- **BGA** (Ball Grid Array): İşlemci karta lehimlidir ve değiştirilemez. Dizüstü bilgisayarlarda ve mini bilgisayarlarda yaygındır.

| Soket | Üretici | Tür | Uyumlu işlemciler | Bellek |
|---|---|---|---|---|
| LGA 1700 | Intel | LGA | 12., 13. ve 14. nesil Core | DDR4 ya da DDR5 (karta göre) |
| LGA 1851 | Intel | LGA | Core Ultra 200S serisi | DDR5 |
| AM4 | AMD | PGA | Ryzen 1000 – 5000 serileri | DDR4 |
| AM5 | AMD | LGA | Ryzen 7000 ve sonrası | DDR5 |
| sTR5 | AMD | LGA | Ryzen Threadripper 7000 ve sonrası | DDR5 (kayıtlı) |
| LGA 4677 | Intel | LGA | 4. ve 5. nesil Xeon Scalable, Xeon W | DDR5 (kayıtlı) |

Soketin ömrü, yükseltme açısından önemlidir. AMD, AM4 soketini 2017'den itibaren beş işlemci kuşağı boyunca destekledi; aynı anakartla yıllar sonra çok daha güçlü bir işlemciye geçilebildi. Intel soketleri ise genellikle iki ya da üç kuşak sonra değişir.

### Yonga seti

**Yonga seti** (chipset), anakartın üzerinde işlemciye yardımcı olan çiptir. Eskiden “kuzey köprüsü” ve “güney köprüsü” olmak üzere iki çipten oluşurdu. Kuzey köprüsünün işlevleri (bellek denetimi, ekran kartı bağlantısı) zamanla işlemcinin içine taşındı; geriye tek bir çip kaldı.

Bugün yonga seti temel olarak bir **bağlantı çoğaltıcıdır**: işlemcinin sunduğu sınırlı sayıdaki hızlı hattı alır ve bunları çok sayıda USB portuna, SATA portuna, ek M.2 ve PCIe yuvalarına, ağ ve ses denetleyicilerine dağıtır. Ayrıca işlemcinin hangi özelliklerinin açılacağını belirler.

| Platform | Üst düzey | Orta düzey | Giriş düzeyi | Hız aşırtma |
|---|---|---|---|---|
| Intel LGA 1700 | Z790 | B760 | H610 | Yalnızca Z serisinde (K işlemciyle) |
| Intel LGA 1851 | Z890 | B860 | H810 | Yalnızca Z serisinde |
| AMD AM5 | X870E, X870 | B850, B650 | A620 | X ve B serilerinde |

Yonga seti seçimi işlemcinin hızını doğrudan değiştirmez; aynı işlemci, giriş düzeyi ile üst düzey bir kartta benzer başarım gösterir. Fark, bağlantı sayısında, hız aşırtma olanağında ve güç devrelerinin kalitesinde ortaya çıkar.

:::svg İşlemcinin hızlı hatları doğrudan ekran kartına ve birincil SSD'ye gider. Diğer bağlantılar, işlemciye tek bir bağlantıyla bağlı olan yonga seti üzerinden paylaşılır.
<svg viewBox="0 0 720 300" role="img" aria-label="Ortada işlemci. İşlemciden doğrudan ekran kartına, birincil M.2 SSD'ye ve belleğe hatlar çıkar. İşlemci ayrıca yonga setine bağlıdır; yonga seti USB portlarına, SATA disklere, ek M.2 yuvasına ve ağ kartına dağıtım yapar" data-dur="9">
<rect x="280" y="40" width="130" height="80" rx="12" class="s-soft l-acc w3"/><text x="345" y="76" text-anchor="middle" class="t-l">İşlemci</text><text x="345" y="98" text-anchor="middle" class="t-s">hızlı hatların kaynağı</text>
<rect x="40" y="30" width="170" height="44" rx="8" class="s-bg3 l-fg w2"/><text x="125" y="57" text-anchor="middle" class="t-s t-b">Ekran kartı · PCIe x16</text>
<rect x="40" y="90" width="170" height="44" rx="8" class="s-bg3 l-fg w2"/><text x="125" y="117" text-anchor="middle" class="t-s t-b">Birincil M.2 SSD · x4</text>
<rect x="510" y="58" width="170" height="44" rx="8" class="s-bg3 l-fg w2"/><text x="595" y="85" text-anchor="middle" class="t-s t-b">Bellek (DDR5)</text>
<path d="M280 66H210M280 100H210M410 80h100" class="l-acc w4" fill="none"/>
<path d="M345 120v60" class="l-acc2 w4" fill="none"/><text x="356" y="156" class="t-s t-acc2 t-b">tek bağlantı (paylaşılır)</text>
<rect x="280" y="180" width="130" height="60" rx="12" class="s-bg3 l-acc2 w3"/><text x="345" y="216" text-anchor="middle" class="t-b">Yonga seti</text>
<g class="s-bg3 l-fg w2"><rect x="40" y="170" width="150" height="36" rx="8"/><rect x="40" y="224" width="150" height="36" rx="8"/><rect x="530" y="170" width="150" height="36" rx="8"/><rect x="530" y="224" width="150" height="36" rx="8"/></g>
<text x="115" y="193" text-anchor="middle" class="t-s">USB portları</text><text x="115" y="247" text-anchor="middle" class="t-s">SATA diskler</text><text x="605" y="193" text-anchor="middle" class="t-s">Ek M.2 yuvası</text><text x="605" y="247" text-anchor="middle" class="t-s">Ağ ve ses</text>
<path d="M280 200 190 190M280 220l-90 22M410 200l120-10M410 220l120 22" class="l-mut w2" fill="none"/>
<circle cx="274" cy="66" r="7" class="s-acc" data-a="move -58 0 .02 .16"/><circle cx="274" cy="100" r="7" class="s-acc" data-a="move -58 0 .1 .24"/><circle cx="416" cy="80" r="7" class="s-acc" data-a="move 88 0 .06 .2"/>
<circle cx="345" cy="124" r="7" class="s-acc2" data-a="move 0 50 .34 .46"/><circle cx="276" cy="200" r="6" class="s-acc2" data-a="move -80 -9 .48 .6"/>
<circle cx="345" cy="124" r="7" class="s-acc2" data-a="move 0 50 .56 .68"/><circle cx="414" cy="220" r="6" class="s-acc2" data-a="move 110 20 .7 .82"/>
<circle cx="345" cy="124" r="7" class="s-acc2" data-a="move 0 50 .78 .9"/><circle cx="276" cy="220" r="6" class="s-acc2" data-a="move -80 20 .9 .98"/>
</svg>
:::

Bu yapının pratik bir sonucu vardır: yonga setine bağlı bütün cihazlar, işlemciye giden tek bir bağlantıyı paylaşır. Aynı anda birkaç hızlı diskten kopyalama yapıyorsanız bu bağlantı darboğaz oluşturabilir. En hızlı SSD'nizi bu yüzden işlemciye doğrudan bağlı olan birincil M.2 yuvasına takmak gerekir.

### Güç devreleri (VRM)

Güç kaynağı anakarta 12 volt verir; işlemci ise yaklaşık 1 volt ve çok yüksek akım ister. Bu dönüşümü soketin çevresindeki **VRM** (voltaj düzenleyici modül) yapar. “Faz” sayısı ve bileşen kalitesi, kartın güç isteyen bir işlemciyi uzun süre yük altında ısınmadan besleyip besleyemeyeceğini belirler. Giriş düzeyi kartlar üst düzey işlemcileri çalıştırabilir, ama yük altında güç devreleri ısınır ve işlemci hızını düşürebilir.

### Bellek yuvaları

Bellek modülleri **DIMM** yuvalarına takılır. DDR4 ile DDR5 modüllerin çentik konumu farklıdır; birbirinin yuvasına girmezler ve bir anakart yalnızca birini destekler. Belleği **çift kanal** çalıştırmak için iki modül, kılavuzda belirtilen yuvalara (genellikle işlemciden uzak olan ikinci ve dördüncü yuvaya) takılır. Dört yuvalı kartlar daha fazla kapasiteye izin verir; iki yuvalı kartlar ise çoğu zaman daha yüksek bellek hızlarına ulaşır.

### Genişleme yuvaları: PCIe

**PCI Express** (PCIe), ekran kartı, ses kartı, ağ kartı ve SSD gibi bileşenlerin takıldığı yuva standardıdır. İki özelliği vardır: **hat sayısı** (x1, x4, x8, x16) ve **sürüm**. Hat sayısı arttıkça yuva uzar; x16 yuvası ekran kartları içindir. Her yeni sürüm, hat başına hızı iki katına çıkarır.

:::chart
{"type":"bar","title":"PCI Express sürümlerine göre hat başına veri aktarım hızı","labels":["PCIe 1.0 (2003)","PCIe 2.0 (2007)","PCIe 3.0 (2010)","PCIe 4.0 (2017)","PCIe 5.0 (2019)"],"datasets":[{"label":"Hat başına aktarım hızı (MB/sn)","data":[250,500,985,1969,3938]}],"unit":"MB/sn","yTitle":"MB/sn (tek hat, tek yön)","source":"PCI-SIG – PCI Express belirtimleri","sourceUrl":"https://pcisig.com/specifications","date":"2003–2019","note":"Kodlama yükü düşüldükten sonraki yaklaşık değerlerdir. x4 bir bağlantı bunun dört, x16 bir bağlantı on altı katını taşır."}
:::

Sürümler geriye dönük uyumludur: PCIe 5.0 bir kart 3.0 bir yuvada, 3.0 hızında çalışır. Fiziksel boyut ile elektriksel bağlantı da farklı olabilir: x16 uzunluğundaki bir yuva yalnızca x4 hat taşıyor olabilir; anakart kılavuzundaki tabloya bakmak gerekir.

### Depolama bağlantıları

- **M.2:** Sakız büyüklüğündeki SSD'lerin takıldığı yuvadır. En yaygın boyut 22 × 80 mm'dir ve “2280” olarak yazılır. Bir M.2 yuvası **NVMe** (PCIe üzerinden, çok hızlı) ya da **SATA** (daha yavaş) diskleri destekleyebilir; her yuva ikisini birden desteklemez.
- **SATA:** 2,5 inç SSD'ler ve sabit diskler için 6 Gbit/sn'lik bağlantıdır. Bazı kartlarda bir M.2 yuvası kullanıldığında belirli SATA portları devre dışı kalır.

### Güç ve dâhilî bağlantılar

- **24 iğneli ATX:** Anakartın ana güç bağlantısı.
- **8 iğneli EPS (4+4):** İşlemcinin güç bağlantısı; üst düzey kartlarda iki tane bulunur.
- **Fan ve pompa bağlantıları:** 4 iğneli olanlar fan hızını PWM ile denetler.
- **Ön panel bağlantıları:** Kasanın güç düğmesi, USB ve ses girişleri için.
- **RGB bağlantıları:** 12 voltluk 4 iğneli ve 5 voltluk 3 iğneli (adreslenebilir) türler birbirine takılmamalıdır.
- **BIOS / UEFI çipi ve pil:** Açılış yazılımını ve saat ile ayarları tutar.

### BIOS ve UEFI

Anakartın üzerindeki küçük bir bellek çipi, bilgisayar açıldığında çalışan ilk yazılımı tutar. Eskiden **BIOS** olarak anılan bu yazılımın yerini bugün **UEFI** almıştır; ama gündelik dilde hâlâ BIOS denir. Görevi donanımı tanımak, belleği ve işlemciyi başlatmak ve işletim sistemini yükleyecek diski bulmaktır. Fan eğrileri, bellek hız profilleri (Intel'de XMP, AMD'de EXPO), önyükleme sırası ve güvenli önyükleme gibi ayarlar buradan yapılır.

Üreticiler yeni işlemcileri desteklemek ve hataları gidermek için güncellemeler yayımlar. Pek çok güncel kartta, işlemci takılı olmadan yalnızca bir USB bellekle güncelleme yapmayı sağlayan bir düğme bulunur; bu, kartın çıkışından sonra piyasaya sürülmüş bir işlemci aldığınızda hayat kurtarır. Güncelleme sırasında elektriğin kesilmesi kartı kullanılamaz hâle getirebildiği için, bazı kartlarda yedek ikinci bir çip de yer alır.

### Arka panel bağlantı noktaları

| Bağlantı | En yüksek hız | Ne için |
|---|---|---|
| USB 2.0 | 480 Mbit/sn | Klavye, fare |
| USB 3.2 Gen 1 (eski adıyla USB 3.0) | 5 Gbit/sn | Bellek çubukları, harici diskler |
| USB 3.2 Gen 2 | 10 Gbit/sn | Hızlı harici SSD'ler |
| USB 3.2 Gen 2x2 | 20 Gbit/sn | Yalnızca USB-C |
| USB4 / Thunderbolt 4 | 40 Gbit/sn | Bağlantı istasyonları, harici ekran kartı kutuları, ekran |
| HDMI / DisplayPort | Sürüme göre | Tümleşik grafikten görüntü |
| RJ-45 Ethernet | 1, 2,5 ya da 10 Gbit/sn | Kablolu ağ |
| Wi-Fi anten bağlantıları | Standarda göre | Kablosuz ağ ve Bluetooth |
| Ses girişleri ve optik çıkış | — | Hoparlör, mikrofon |

Anakarttaki HDMI ve DisplayPort çıkışları **tümleşik grafiği** kullanır. Ayrı bir ekran kartınız varsa monitörünüzü anakarta değil, ekran kartının üzerindeki çıkışa bağlamalısınız; bu, yeni bilgisayar toplayanların en sık yaptığı hatalardan biridir.

## Hangisini seçmeliyim?

| Durum | Makul seçim | Neden |
|---|---|---|
| İlk bilgisayarımı topluyorum | ATX | Geniş alan, kolay montaj, bol bağlantı |
| Bütçe sınırlı, tek ekran kartı yeterli | Micro-ATX | Genellikle en uygun fiyat; daha küçük kasa |
| Masada az yer kaplayan ya da taşınabilir sistem | Mini-ITX | En küçük standart boyut |
| Küçük kasa, ama ekran kartının yanında ikinci bir kart | Mini-DTX | İki genişleme yuvası |
| İş istasyonu, çok bellek, çok disk | E-ATX | Sekiz bellek yuvası, güçlü güç devreleri |
| Ev sunucusu, medya bilgisayarı | Mini-ITX ya da Micro-ATX | Düşük güç, küçük boyut |

Seçerken şu sırayı izleyin:

1. **İşlemciyi seçin.** Soket ve bellek türü (DDR4 ya da DDR5) buna göre belirlenir.
2. **Boyutu seçin.** Kasanın nereye sığacağı ve kaç kart takacağınız belirleyicidir.
3. **Yonga setini seçin.** Hız aşırtma yapacak mısınız? Kaç M.2 ve USB portuna ihtiyacınız var?
4. **Güç devrelerine bakın.** Üst düzey bir işlemci alıyorsanız en ucuz kartlardan kaçının.
5. **Ayrıntıları denetleyin.** Wi-Fi yerleşik mi? Ağ portu 2,5 Gbit/sn mi? Ön paneldeki USB-C için bağlantı var mı? İşlemcinizi desteklemek için BIOS güncellemesi gerekiyor mu?

:::tip Büyük kasa küçük kartı kabul eder
Emin değilseniz, ATX destekleyen bir kasa en esnek seçimdir: içine ATX, Micro-ATX ve Mini-ITX kartların hepsi takılabilir. Küçük kasalar ise yalnızca kendi boyutlarını ve daha küçüklerini kabul eder; soğutucu yüksekliği ve ekran kartı uzunluğu sınırlarını da ayrıca denetlemeniz gerekir.
:::

## Sık sorulan sorular

:::faq
Q: Büyük anakart daha mı hızlıdır?
A: Hayır. Aynı yonga setine sahip bir Mini-ITX ile bir ATX kart, aynı işlemciyle aynı başarımı verir. Boyut yalnızca yuva ve bağlantı sayısını, soğutma payını ve montaj kolaylığını etkiler.
Q: Micro-ATX anakart ATX kasaya takılır mı?
A: Evet. Vida delikleri ATX'inkilerin bir alt kümesidir. Büyük kasa küçük kartı kabul eder; tersi mümkün değildir.
Q: Mini-ITX neden daha pahalı?
A: Aynı bileşenleri çok daha küçük bir alana sığdırmak daha çok katmanlı kart ve daha zor bir tasarım gerektirir; üretim adedi de düşüktür.
Q: Yonga seti işlemcinin hızını etkiler mi?
A: Doğrudan etkilemez. Ancak hız aşırtmaya izin verip vermediğini, bellek hızı sınırını ve güç devrelerinin kalitesini belirlediği için dolaylı etkisi olabilir.
Q: Anakartımı değiştirmeden işlemcimi yükseltebilir miyim?
A: Yeni işlemci aynı soketi kullanıyorsa ve anakartın yonga seti ile BIOS'u onu destekliyorsa evet. Üreticinin “desteklenen işlemciler” listesine bakın; çoğu zaman önce BIOS güncellemesi gerekir.
Q: E-ATX anakartım kasama sığar mı?
A: “E-ATX” adı tek bir ölçü olmadığı için kartın milimetre cinsinden genişliğini kasanın desteklediği en büyük değerle karşılaştırmanız gerekir.
Q: PCIe 5.0 SSD için PCIe 5.0 anakart şart mı?
A: Çalışması için şart değildir; PCIe 5.0 bir SSD 4.0 ya da 3.0 bir yuvada o yuvanın hızında çalışır. Tam hızını görmek için hem yuvanın hem işlemcinin 5.0 desteklemesi gerekir.
:::

## Özet

- Form faktörü; anakartın boyutunu, vida deliklerini ve bağlantı yerleşimini belirleyen standarttır.
- Başlıca boyutlar: Mini-ITX 170 × 170, Micro-ATX 244 × 244, ATX 305 × 244 ve E-ATX 305 × 330 mm. Mini-DTX, XL-ATX ve BTX daha nadir görülür.
- Büyük kasa küçük anakartı kabul eder; boyut başarımı değil, yuva ve bağlantı sayısını belirler.
- Soket, hangi işlemcinin takılabileceğini; yonga seti ise kaç bağlantı sunulacağını ve hangi özelliklerin açılacağını belirler.
- İşlemcinin hızlı hatları doğrudan ekran kartına ve birincil M.2 yuvasına gider; diğer bağlantılar yonga seti üzerinden paylaşılır.
- PCIe'nin her sürümü hat başına hızı ikiye katlar ve sürümler geriye dönük uyumludur.

## Kaynaklar

- [Intel – ATX ve microATX form faktörü belirtimleri hakkında](https://www.intel.com/content/www/us/en/support/articles/000005819/boards-and-kits.html)
- [Form Factors – ATX Specification 2.2 (arşiv)](https://web.archive.org/web/2012/http://www.formfactors.org/developer/specs/atx2_2.pdf)
- [Form Factors – microATX Motherboard Interface Specification (arşiv)](https://web.archive.org/web/2012/http://www.formfactors.org/developer/specs/matxspe1.2.pdf)
- [VIA Technologies – Mini-ITX form faktörü](https://www.viatech.com/en/boards/mini-itx/)
- [PCI-SIG – PCI Express belirtimleri](https://pcisig.com/specifications)
- [USB Implementers Forum – USB belirtimleri ve adlandırma](https://www.usb.org/documents)
- [Intel – Masaüstü yonga setleri ürün belirtimleri](https://www.intel.com/content/www/us/en/products/details/chipsets/desktop-chipsets.html)
- [AMD – AM5 platformu ve yonga setleri](https://www.amd.com/en/products/processors/chipsets/am5.html)
- [SATA-IO – Serial ATA belirtimleri](https://sata-io.org/developers/purchase-specification)
