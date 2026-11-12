---
title: Every Mobile Network Explained: 1G'den 5G'ye Mobil Şebekeler
slug: every-mobile-network-explained
category: ag-ve-internet
tags: 5g, 4g, lte, mobil şebeke, gsm, mmwave
description: 1G, 2G (GSM/EDGE), 3G (UMTS/HSPA), 4G/LTE, LTE-A ve 5G (sub-6 ve mmWave): hız, gecikme ve frekans karşılaştırması; animasyon ve grafiklerle.
excerpt: Telefonunuzun köşesindeki E, H+, 4G, 4.5G ve 5G simgeleri ne anlama geliyor? 1G'den 5G'ye bütün mobil şebeke nesillerini; hız, gecikme ve frekans farklarıyla birlikte anlatıyoruz.
cover: /assets/covers/mobile-networks.svg
cover_alt: Sinyal dalgaları yayan baz istasyonu kulesi ve büyüyen sinyal çubukları çizimi
series: Every X Explained
series_order: 8
date: 2026-10-27 09:00
---
Telefonunuzun ekranının köşesinde küçük bir simge durur: bazen 4.5G, bazen H+, kırsalda belki yalnızca E. O simge, cihazınızın o anda hangi **nesil** mobil şebekeye bağlı olduğunu gösterir ve bir videonun anında mı yoksa bir dakikada mı açılacağını belirler. Kırk yılda, yalnızca cızırtılı ses taşıyabilen analog telsiz telefonlardan saniyede gigabitler taşıyan 5G'ye geldik.

Bu yazıda her nesli sırayla ele alacağız: ne getirdi, hangi teknolojiyi kullandı, ne kadar hızlıydı. Önce hepsinin ortak dayandığı kavramları açıklayalım.

## Temel kavramlar

### Hücresel ağ nedir?

Mobil şebekelere **hücresel** denmesinin nedeni, kapsama alanının **hücre** adı verilen bölgelere ayrılmasıdır. Her hücrenin ortasında bir **baz istasyonu** bulunur. Telefonunuz en güçlü sinyali aldığı baz istasyonuna radyo dalgalarıyla bağlanır; baz istasyonu da fiber ya da radyolink ile operatörün **çekirdek ağına**, oradan da internete ve diğer şebekelere bağlanır.

Bu yapının inceliği frekansların yeniden kullanılmasıdır: birbirine komşu olmayan hücreler aynı frekansları kullanabilir. Böylece sınırlı bir frekans aralığıyla milyonlarca aboneye hizmet verilir. Hareket hâlindeyken telefonunuz bir hücreden diğerine geçer; görüşmenin ya da veri bağlantısının kopmadan yeni baz istasyonuna aktarılmasına **el değiştirme** (handover) denir.

:::svg El değiştirme: telefon hareket ettikçe bağlantı, sinyali daha güçlü olan komşu baz istasyonuna kesintisiz aktarılır.
<svg viewBox="0 0 720 260" role="img" aria-label="İki komşu hücre ve ortalarında baz istasyonları. Soldan sağa hareket eden telefon önce birinci baz istasyonuna bağlıdır; hücre sınırını geçerken bağlantı ikinci baz istasyonuna aktarılır" data-dur="10">
<ellipse cx="210" cy="150" rx="190" ry="95" class="s-soft l-acc w2"/><ellipse cx="510" cy="150" rx="190" ry="95" class="s-bg3 l-blue w2"/>
<g><path d="M210 150V70M196 150l14-50 14 50z" class="l-fg w3 rc" fill="none"/><circle cx="210" cy="66" r="6" class="s-acc"/><circle cx="210" cy="66" r="26" class="s-none l-acc w2" data-a="wave 2.4"/><text x="210" y="176" text-anchor="middle" class="t-b">Baz istasyonu A</text></g>
<g><path d="M510 150V70M496 150l14-50 14 50z" class="l-fg w3 rc" fill="none"/><circle cx="510" cy="66" r="6" class="s-blue"/><circle cx="510" cy="66" r="26" class="s-none l-blue w2" data-a="wave 2.4 1.2"/><text x="510" y="176" text-anchor="middle" class="t-b">Baz istasyonu B</text></g>
<g data-a="move 460 0 .05 .9 show"><rect x="118" y="196" width="26" height="44" rx="5" class="s-fg"/><rect x="122" y="201" width="18" height="30" rx="2" class="s-bg2"/></g>
<path d="M210 72 160 196" class="l-acc w3 dash" fill="none" data-a="show .05 .45"/><path d="M210 72 330 196" class="l-acc w3 dash" fill="none" data-a="show .3 .5"/>
<path d="M510 72 400 196" class="l-blue w3 dash" fill="none" data-a="show .5 .72"/><path d="M510 72 560 196" class="l-blue w3 dash" fill="none" data-a="show .72 .95"/>
<text x="360" y="30" text-anchor="middle" class="t-s t-b" data-a="show .44 .6">el değiştirme: bağlantı A'dan B'ye aktarıldı</text>
<text x="360" y="252" text-anchor="middle" class="t-s">telefon hareket ediyor →</text>
</svg>
:::

### Frekans, bant genişliği ve kapsama

Mobil iletişim radyo dalgalarıyla yapılır ve her dalganın bir **frekansı** vardır; megahertz (MHz) ya da gigahertz (GHz) ile ölçülür. Frekans seçimi bir takastır:

- **Düşük frekanslar** (1 GHz'in altı) uzağa gider ve duvarlardan iyi geçer, ama bu bantlarda yer dar olduğu için taşınabilecek veri sınırlıdır.
- **Yüksek frekanslar** çok daha fazla veri taşıyabilir, ama kısa mesafede zayıflar ve engellerden kolay etkilenir.

Buradaki **bant genişliği**, bir operatörün kullandığı frekans dilimin genişliğidir (örneğin 20 MHz). Dilim ne kadar genişse aynı anda o kadar çok veri taşınır. Bir yolun şerit sayısı gibi düşünebilirsiniz.

:::svg Frekans ile kapsama arasındaki takas: düşük bant uzağa gider ama yavaştır; milimetre dalga çok hızlıdır ama yalnızca yakın çevreyi kapsar.
<svg viewBox="0 0 720 280" role="img" aria-label="Aynı baz istasyonundan yayılan üç bant: en geniş daire düşük bant, yaklaşık kilometrelerce kapsama ve düşük hız; ortadaki orta bant; en küçük daire milimetre dalga, yüzlerce metre kapsama ve çok yüksek hız" data-dur="8">
<circle cx="250" cy="150" r="124" class="s-bg3 l-blue w2" data-a="show .04"/><circle cx="250" cy="150" r="74" class="s-soft l-acc w2" data-a="show .34"/><circle cx="250" cy="150" r="28" class="s-acc2" data-a="show .64"/>
<path d="M250 150V96M240 150l10-34 10 34z" class="l-fg w3 rc" fill="none"/><circle cx="250" cy="92" r="5" class="s-fg"/>
<circle cx="250" cy="150" r="124" class="s-none l-blue w2" data-a="wave 3"/>
<g data-a="show .04"><path d="M374 150h66" class="l-blue w2"/><text x="448" y="56" class="t-b">Düşük bant (700–900 MHz)</text><text x="448" y="76" class="t-s">kilometrelerce kapsama, duvarı iyi geçer</text><text x="448" y="94" class="t-s">hız: düşük–orta</text><path d="M440 150V62" class="l-blue w2" fill="none"/></g>
<g data-a="show .34"><path d="M324 150h110" class="l-acc w2"/><text x="448" y="140" class="t-b">Orta bant (1,8–3,8 GHz)</text><text x="448" y="160" class="t-s">kapsama ile hız arasında denge</text><text x="448" y="178" class="t-s">5G'nin ana bandı (3,5 GHz)</text></g>
<g data-a="show .64"><path d="M270 170l164 50" class="l-acc2 w2"/><text x="448" y="224" class="t-b">Milimetre dalga (24 GHz ve üzeri)</text><text x="448" y="244" class="t-s">birkaç yüz metre; yaprak bile engeller</text><text x="448" y="262" class="t-s">hız: çok yüksek</text></g>
</svg>
:::

### Hız, gecikme ve “G” harfi

Bir mobil bağlantının iki ayrı ölçüsü vardır. **Hız** (bit/saniye), saniyede ne kadar veri aktarılabildiğidir. **Gecikme** (milisaniye), bir isteğin gidip yanıtının gelmesi için geçen süredir. Video izlemek hız, çevrim içi oyun ve görüntülü görüşme ise düşük gecikme ister.

“G”, *generation*, yani **nesil** sözcüğünün baş harfidir. Her nesil yaklaşık on yılda bir gelir ve yalnızca daha hızlı olmakla kalmaz, şebekenin çalışma biçimini kökten değiştirir. Nesillerin resmî gereksinimlerini Birleşmiş Milletler'e bağlı **ITU** belirler; teknik standartları ise operatör ve üreticilerin oluşturduğu **3GPP** birliği yazar.

:::warn Kuramsal hız ile gerçek hız aynı değildir
Bu yazıdaki tepe hızlar, standartların ideal koşullarda tek bir kullanıcı için tanımladığı **kuramsal üst sınırlardır**. Gerçekte hızınızı baz istasyonuna uzaklığınız, aynı hücredeki kullanıcı sayısı, operatörün elindeki frekans miktarı ve telefonunuzun modemi belirler; günlük hızlar çoğu zaman tepe değerin çok altındadır.
:::

## Nesiller tek tek

### 1G: analog ses

İlk ticari hücresel şebekeler 1979'da Japonya'da, 1981'de İskandinav ülkelerinde (**NMT**) ve 1983'te ABD'de (**AMPS**) açıldı. 1G tümüyle **analogdu**: sesiniz, tıpkı FM radyoda olduğu gibi, doğrudan radyo dalgasının üzerine bindirilirdi.

Bunun sonuçları ağırdı. Şifreleme yoktu; uygun bir alıcısı olan herkes görüşmeleri dinleyebilirdi. Ses kalitesi parazitliydi, kapasite düşüktü ve her ülke kendi standardını kullandığı için telefonlar sınır ötesinde çalışmazdı. Veri iletimi fiilen yoktu. Telefonlar ise tuğla büyüklüğündeydi.

### 2G: sayısal çağ, GSM ve SMS

1991'de Finlandiya'da ilk **GSM** görüşmesi yapıldı ve mobil iletişim **sayısal** hâle geldi: ses artık sayılara çevrilip sıkıştırılarak gönderiliyordu. Bu üç büyük kazanım getirdi: görüşmeler şifrelenebildi, aynı frekansa çok daha fazla kullanıcı sığdı ve **SMS** doğdu. İlk kısa mesaj Aralık 1992'de gönderildi. GSM ayrıca **SIM kartı** ve ülkeler arası **dolaşımı** (roaming) getirdi; kısa sürede dünya standardı oldu.

GSM, kullanıcıları zaman dilimlerine bölerek (**TDMA**) aynı frekansı paylaştırır. Başlangıçta veri için tasarlanmamıştı; bu eksik iki eklentiyle giderildi:

- **GPRS (2.5G):** Paket anahtarlamalı veriyi getirdi; yani bağlantı süresine göre değil, kullanılan veriye göre ücretlendirme mümkün oldu. Kuramsal tepe hızı yaklaşık 114 kbit/sn'dir. Telefonda **G** simgesiyle görünür.
- **EDGE (2.75G):** Daha verimli bir kodlamayla hızı yaklaşık 384 kbit/sn'ye çıkardı. Telefonda **E** simgesiyle görünür.

2G bugün hâlâ pek çok ülkede açıktır, çünkü çok geniş kapsama sunar ve sayaçlar, araç takip cihazları, ödeme terminalleri gibi milyonlarca basit cihaz ona bağlıdır.

### 3G: mobil internet

3G'nin hedefi, telefonu gerçek bir internet cihazına dönüştürmekti. İlk ticari şebeke 2001'de Japonya'da açıldı. Avrupa ve Türkiye'nin de kullandığı standart **UMTS**'tir ve **W-CDMA** adı verilen bir yönteme dayanır: kullanıcılar zaman dilimlerine bölünmez, hepsi aynı geniş frekans bandını aynı anda kullanır ve birbirlerinden özel kodlarla ayrılır.

İlk UMTS şebekeleri 384 kbit/sn sunuyordu; standart, sabit kullanıcı için 2 Mbit/sn'yi hedefliyordu. Görüntülü arama ve mobil web bu dönemde mümkün oldu. Asıl hız ise sonraki eklentilerle geldi:

- **HSPA (3.5G):** İndirmede 14,4 Mbit/sn'ye, yüklemede 5,76 Mbit/sn'ye varan kuramsal hızlar. Telefonda **H** simgesi.
- **HSPA+ (3.75G):** Daha yoğun kodlama ve çift taşıyıcı ile 21, sonra 42 Mbit/sn. Telefonda **H+** simgesi.

Akıllı telefon devrimi 3G'nin üzerinde yaşandı: uygulama mağazaları, haritalar, sosyal medya ve müzik akışı bu nesille yaygınlaştı.

### 4G / LTE: her şey IP

**LTE** (Long Term Evolution) ile ilk ticari şebeke Aralık 2009'da Stockholm ve Oslo'da açıldı. LTE iki köklü değişiklik getirdi.

Birincisi, radyo tarafında **OFDMA**'ya geçildi: frekans bandı binlerce dar alt taşıyıcıya bölünür ve bunlar kullanıcılara ihtiyaçlarına göre anlık olarak dağıtılır. Buna birden çok antenle aynı anda veri gönderen **MIMO** eklendi.

İkincisi, şebeke **tümüyle IP tabanlı** hâle geldi. Önceki nesillerde ses için ayrı bir devre anahtarlamalı ağ vardı; LTE'de böyle bir ağ yoktur, her şey veri paketidir. Bu yüzden ilk LTE telefonları arama sırasında 3G'ye düşerdi. Sorunu, sesi de veri paketi olarak taşıyan **VoLTE** çözdü; daha net ses ve çok daha hızlı arama kurulumu sağladı.

İlk LTE cihazları 100–150 Mbit/sn tepe hız sunuyordu. Gecikme de 3G'ye göre belirgin biçimde düştü; video akışı, görüntülü görüşme ve mobil oyun gündelik hâle geldi.

### LTE-Advanced ve 4.5G

İlk LTE sürümleri, ITU'nun 4G için koyduğu gereksinimi (sabit kullanıcı için 1 Gbit/sn) aslında karşılamıyordu. Bunu **LTE-Advanced** (LTE-A) başardı. En önemli yeniliği **taşıyıcı birleştirmedir** (carrier aggregation): operatörün farklı bantlardaki frekans dilimleri birleştirilerek tek bir geniş kanal gibi kullanılır. İki, üç ya da daha fazla taşıyıcı birleştikçe hız katlanır.

**LTE-Advanced Pro** ise daha fazla taşıyıcı, daha yoğun kodlama (256-QAM) ve daha çok antenle kuramsal sınırı gigabitlerin üzerine taşıdı. Türkiye'de 1 Nisan 2016'da **“4.5G”** adıyla hizmete giren teknoloji budur. Telefonunuzdaki **4.5G** ya da **LTE+** simgesi, taşıyıcı birleştirmenin etkin olduğunu gösterir.

### 5G: üç ayrı hedef

5G'nin ilk ticari şebekeleri 2019'da açıldı. Önceki nesillerden farklı olarak yalnızca “daha hızlı telefon interneti” için değil, üç ayrı kullanım için tasarlandı:

- **eMBB** (gelişmiş mobil geniş bant): çok yüksek hız ve kapasite. Kalabalık stadyumlarda bile akıcı video.
- **URLLC** (çok güvenilir, düşük gecikmeli iletişim): fabrika otomasyonu, uzaktan kumanda gibi milisaniyelerin önemli olduğu işler.
- **mMTC** (yoğun makine iletişimi): kilometrekarede bir milyona varan sensör ve cihaz.

ITU'nun 5G için koyduğu hedefler bunu yansıtır: indirmede 20 Gbit/sn tepe hız, kullanıcının gündelik deneyiminde 100 Mbit/sn, URLLC için 1 milisaniyelik gecikme.

5G'nin radyo arayüzünün adı **5G NR**'dir (New Radio) ve iki frekans aralığında çalışır:

- **Sub-6** (FR1, 7 GHz'in altı): düşük bant ve özellikle 3,5 GHz çevresindeki orta bant. Dünyadaki 5G'nin büyük bölümü budur; iyi kapsama ile yüksek hızı dengeler.
- **mmWave** (FR2, 24 GHz ve üzeri): çok geniş frekans dilimleri sayesinde gigabitlerce hız sunar, ama menzili birkaç yüz metredir; duvarlar, camlar, hatta yağmur ve yapraklar sinyali zayıflatır. Stadyum, havalimanı ve yoğun şehir merkezleri gibi noktalarda kullanılır.

5G'yi mümkün kılan başlıca teknikler şunlardır:

- **Massive MIMO:** Baz istasyonunda onlarca, hatta yüzlerce anten elemanı bulunur ve aynı anda pek çok kullanıcıya ayrı veri akışı gönderilir.
- **Hüzme yönlendirme** (beamforming): Sinyal her yöne saçılmak yerine doğrudan kullanıcıya odaklanan bir hüzme hâlinde gönderilir.
- **Ağ dilimleme** (network slicing): Aynı fiziksel şebeke, farklı gereksinimler için birbirinden yalıtılmış sanal şebekelere bölünür.

Bir ayrıntı önemlidir: ilk 5G şebekelerinin çoğu **NSA** (non-standalone) kipindedir; yani 5G radyosu, mevcut 4G çekirdek ağına yaslanır. Düşük gecikme ve ağ dilimleme gibi yeniliklerin tamamı ancak kendi 5G çekirdeğine sahip **SA** (standalone) şebekelerle gelir.

Türkiye'de 5G için frekans ihalesi Ekim 2025'te yapıldı ve ticari hizmetin 1 Nisan 2026'da başlaması planlandı.

### Ya 6G?

6G için çalışmalar sürüyor. ITU, yeni neslin çerçevesini **IMT-2030** adıyla tanımladı; ticari şebekelerin 2030 dolaylarında gelmesi bekleniyor. Tartışılan başlıklar arasında terahertz altı frekanslar, uydu ile karasal şebekelerin bütünleşmesi ve şebekenin algılama (bir tür radar) için de kullanılması var. Henüz kesinleşmiş bir standart yoktur.

## Şebekenin görünmeyen parçaları

Nesiller çoğunlukla radyo teknolojisiyle anılır, ama bir mobil şebeke yalnızca antenlerden ibaret değildir.

### SIM ve eSIM

**SIM kart**, abone kimliğinizi ve şebekeyle aranızdaki gizli anahtarı taşıyan küçük bir çiptir. Telefon şebekeye bağlanırken bu anahtarla kendini kanıtlar; anahtar karttan hiç çıkmaz. **eSIM** aynı işlevi, telefonun içine lehimlenmiş bir çip ve uzaktan yüklenen bir operatör profiliyle görür. Fiziksel kart değiştirmeden operatör değiştirmeyi, yurt dışında yerel bir hat eklemeyi ve tek telefonda birden çok numara kullanmayı kolaylaştırır.

### Çekirdek ağ ve taşıma

Baz istasyonu, telefonunuzdan aldığı veriyi operatörün **çekirdek ağına** iletmek zorundadır. Baz istasyonu ile çekirdek arasındaki bu bağlantıya **taşıma hattı** (backhaul) denir ve çoğunlukla fiber optik kabloyla, fiberin ulaşmadığı yerlerde ise noktadan noktaya radyolinkle sağlanır. Bir baz istasyonu en yeni 5G donanımına sahip olsa bile, taşıma hattı darsa size sunabileceği hız sınırlı kalır. Hız testlerinde aynı operatörün farklı mahallelerde çok farklı sonuç vermesinin sık görülen nedenlerinden biri budur.

### Küçük hücreler ve bina içi kapsama

Yüksek frekanslar duvarlardan zor geçtiği için operatörler, büyük kulelerin yanında **küçük hücreler** de kurar: sokak lambalarına, bina cephelerine ve alışveriş merkezlerinin tavanlarına yerleştirilen, kısa menzilli ve düşük güçlü baz istasyonları. Kalabalık bir meydanda ya da metro istasyonunda bağlantının çalışmasını sağlayan çoğu zaman bunlardır. Ev ve iş yerlerinde ise **Wi-Fi üzerinden arama** (VoWiFi) özelliği, zayıf mobil sinyali Wi-Fi bağlantısıyla tamamlar.

### Nesnelerin interneti için dar bant

Her cihazın gigabitlere ihtiyacı yoktur. Bir su sayacı günde birkaç bayt gönderir, ama pilinin on yıl dayanması gerekir. Bu tür cihazlar için 4G ailesine **NB-IoT** ve **LTE-M** adlı dar bantlı, düşük güçlü kipler eklenmiştir; çok az veri taşır, ama bodrum katlarına kadar ulaşan bir kapsama ve çok uzun pil ömrü sunarlar. 5G'nin yoğun makine iletişimi hedefi de bu çizginin devamıdır.

## Hız karşılaştırması

:::chart
{"type":"bar","log":true,"title":"Nesillere göre kuramsal tepe indirme hızı (logaritmik ölçek)","labels":["GPRS (2.5G)","EDGE (2.75G)","UMTS (3G)","HSPA (3.5G)","HSPA+ (3.75G)","LTE (4G)","LTE-Advanced","5G (hedef)"],"datasets":[{"label":"Tepe indirme hızı (Mbit/sn)","data":[0.114,0.384,2,14.4,42,150,1000,20000]}],"unit":"Mbit/sn","yTitle":"Mbit/sn (logaritmik)","source":"3GPP teknik belirtimleri; ITU-R IMT-Advanced ve IMT-2020 gereksinimleri","sourceUrl":"https://www.itu.int/pub/R-REP-M.2410","date":"2017 (IMT-2020 raporu)","note":"Kuramsal tepe değerlerdir (yaklaşık); gerçek kullanımdaki hızlar bunların çok altındadır."}
:::

Dikey eksen logaritmiktir, çünkü doğrusal bir ölçekte 5G'nin dışındaki bütün çubuklar görünmez olurdu: GPRS ile 5G hedefi arasında yaklaşık 175 bin kat fark vardır. Bunu somutlaştıralım: 5 GB'lık bir filmi kuramsal tepe hızlarda indirmek EDGE ile yaklaşık 29 saat, HSPA+ ile 16 dakika, ilk LTE ile 4,5 dakika, LTE-Advanced ile 40 saniye, 5G hedefiyle 2 saniye sürer.

:::svg Aynı dosyanın farklı nesillerde indirilmesi: çubukların dolma hızı, nesiller arasındaki hız farkını temsil eder (ölçekli değildir).
<svg viewBox="0 0 720 240" role="img" aria-label="Dört ilerleme çubuğu: 2G çok yavaş dolar, 3G yavaş, 4G hızlı, 5G neredeyse anında dolar" data-dur="10">
<text x="20" y="46" class="t-b">2G (EDGE)</text><text x="20" y="100" class="t-b">3G (HSPA+)</text><text x="20" y="154" class="t-b">4G (LTE-A)</text><text x="20" y="208" class="t-b">5G</text>
<g class="s-bg3 l-line w2"><rect x="150" y="26" width="550" height="30" rx="8"/><rect x="150" y="80" width="550" height="30" rx="8"/><rect x="150" y="134" width="550" height="30" rx="8"/><rect x="150" y="188" width="550" height="30" rx="8"/></g>
<rect x="150" y="26" width="40" height="30" rx="8" class="s-mut" data-o="l" data-a="grow x .02 .95"/>
<rect x="150" y="80" width="260" height="30" rx="8" class="s-blue" data-o="l" data-a="grow x .02 .9"/>
<rect x="150" y="134" width="550" height="30" rx="8" class="s-acc" data-o="l" data-a="grow x .02 .45"/>
<rect x="150" y="188" width="550" height="30" rx="8" class="s-acc2" data-o="l" data-a="grow x .02 .1"/>
<text x="690" y="208" text-anchor="end" class="t-s t-w t-b" data-a="show .1">tamamlandı</text><text x="690" y="154" text-anchor="end" class="t-s t-ink t-b" data-a="show .45">tamamlandı</text>
</svg>
:::

## Gecikme karşılaştırması

Gecikmede nesiller arası fark, hız kadar göz alıcı değildir ama en az onun kadar önemlidir. 2G'de bir isteğin yanıtı yarım saniyeden uzun sürebilirken, 3G'de bu yüz milisaniyeler düzeyine, 4G'de onlarca milisaniyeye indi. ITU'nun gereksinim belgeleri, radyo erişim ağının kendi payına düşen gecikme (kullanıcı düzlemi gecikmesi) için açık hedefler koyar:

:::chart
{"type":"bar","title":"ITU hedefleri: radyo erişim ağında kullanıcı düzlemi gecikmesi","labels":["4G (IMT-Advanced)","5G eMBB (IMT-2020)","5G URLLC (IMT-2020)"],"datasets":[{"label":"Gecikme hedefi (ms)","data":[10,4,1]}],"unit":"ms","yTitle":"Milisaniye (düşük olan iyi)","source":"ITU-R M.2134 (IMT-Advanced) ve ITU-R M.2410 (IMT-2020) raporları","sourceUrl":"https://www.itu.int/pub/R-REP-M.2410","date":"2008 ve 2017","note":"Yalnızca radyo erişim ağının payıdır. Bir oyun sunucusuna ölçtüğünüz ping, çekirdek ağ ve internet yolunu da içerdiği için daha yüksektir."}
:::

## Büyük karşılaştırma tablosu

| Nesil | Teknoloji | Yaklaşık başlangıç | Telefondaki simge | Kuramsal tepe indirme hızı | Erişim yöntemi | Getirdiği yenilik |
|---|---|---|---|---|---|---|
| 1G | NMT, AMPS | 1979–1983 | — | Veri yok | FDMA (analog) | Mobil sesli görüşme |
| 2G | GSM | 1991 | 2G | 9,6 kbit/sn | TDMA | Sayısal ses, SMS, SIM, dolaşım |
| 2.5G | GPRS | 2000 | G | ~114 kbit/sn | TDMA | Paket veri |
| 2.75G | EDGE | 2003 | E | ~384 kbit/sn | TDMA | Daha hızlı veri |
| 3G | UMTS (W-CDMA) | 2001 | 3G | 2 Mbit/sn | CDMA | Mobil internet, görüntülü arama |
| 3.5G | HSPA | 2005–2007 | H | 14,4 Mbit/sn | CDMA | Mobil geniş bant |
| 3.75G | HSPA+ | 2008–2010 | H+ | 42 Mbit/sn | CDMA | Çift taşıyıcı, MIMO |
| 4G | LTE | 2009 | 4G / LTE | 150 Mbit/sn | OFDMA | Tümüyle IP, düşük gecikme |
| 4.5G | LTE-Advanced (Pro) | 2013–2016 | 4.5G / LTE+ | 1 Gbit/sn ve üzeri | OFDMA | Taşıyıcı birleştirme |
| 5G | 5G NR | 2019 | 5G | 20 Gbit/sn (hedef) | OFDMA | Massive MIMO, mmWave, ağ dilimleme |

## Frekans bantları

| Nesil | Yaygın bantlar | Not |
|---|---|---|
| 2G (GSM) | 900 ve 1800 MHz (Amerika'da 850 ve 1900 MHz) | Düşük bant sayesinde çok geniş kapsama |
| 3G (UMTS) | 2100 MHz; kimi ülkelerde 900 MHz | Pek çok ülkede kapatılıp frekansı 4G ve 5G'ye aktarılıyor |
| 4G (LTE) | 700, 800, 900, 1800, 2100 ve 2600 MHz | Bantlar taşıyıcı birleştirmeyle birlikte kullanılır |
| 5G sub-6 | 700 MHz, 3,4–3,8 GHz (n78), 2,6 GHz | Orta bant, 5G'nin belkemiğidir |
| 5G mmWave | 26, 28 ve 39 GHz | Çok kısa menzil, çok yüksek kapasite |

Operatörler eski nesilleri kapatarak frekanslarını yeni nesillere aktarıyor; buna **şebekenin kapatılması** (sunset) ve **frekansın yeniden kullanımı** (refarming) denir. Pek çok ülkede önce 3G kapatılıyor; 2G ise geniş kapsaması ve ona bağlı basit cihazlar nedeniyle çoğu zaman daha uzun süre açık tutuluyor.

## Hangisi benim için önemli?

| Durumunuz | Bilmeniz gereken |
|---|---|
| Yeni telefon alıyorum | 5G destekli bir model, cihazı yıllarca kullanacaksanız mantıklıdır; ama bulunduğunuz yerde kapsama olup olmadığına bakın |
| Telefon başka ülkeden | Desteklediği **bantların** Türkiye'deki operatörlerin bantlarıyla örtüştüğünü denetleyin; yalnızca “4G var” yazması yetmez |
| Simge E ya da H+ gösteriyor | Bulunduğunuz yerde 4G kapsaması zayıf; telefon daha eski bir nesle düşmüş |
| Evde sabit internet yok | 4.5G ya da 5G üzerinden sabit kablosuz erişim (FWA) bir seçenek olabilir |
| Çevrim içi oyun oynuyorum | Hızdan çok gecikme ve kararlılık önemlidir; sinyal gücü zayıfsa nesil fark etmez |
| Arama sırasında internet kesiliyor | Telefonunuzda ve hattınızda VoLTE'nin açık olduğundan emin olun |
| Pil çabuk bitiyor | Zayıf sinyalde telefon daha yüksek güçle yayın yapar; 5G kapsamasının kenarında sürekli nesil değiştirmek de pil harcar |

## Sık sorulan sorular

:::faq
Q: 4.5G gerçek bir nesil mi?
A: Resmî bir nesil adı değildir. Türkiye'de LTE-Advanced ve LTE-Advanced Pro teknolojileri için kullanılan ticari addır. Teknik olarak 4G ailesinin gelişmiş sürümüdür.
Q: 5G sağlığa zararlı mı?
A: 5G, önceki nesiller gibi iyonlaştırıcı olmayan radyo dalgaları kullanır; bu dalgaların enerjisi kimyasal bağları koparmaya yetmez. Dünya Sağlık Örgütü ve ICNIRP gibi kurumlar, uluslararası sınır değerlerin altındaki maruziyetin bilinen bir sağlık etkisine yol açtığına dair kanıt bulunmadığını belirtmektedir.
Q: 5G telefonum var ama hız 4.5G'den farklı değil. Neden?
A: Birkaç olası neden vardır: düşük bantta çalışan 5G'nin hızı 4.5G'ye yakındır, şebeke henüz 4G çekirdeğine yaslanıyor olabilir ya da baz istasyonunun kendi bağlantısı darboğaz oluşturuyordur. Asıl fark orta bant ve mmWave kapsamasında görülür.
Q: Telefonumdaki LTE ile 4G aynı şey mi?
A: Gündelik kullanımda evet. LTE, 4G olarak pazarlanan teknolojinin adıdır. Katı tanımda ITU'nun 4G gereksinimlerini tam karşılayan sürüm LTE-Advanced'dir.
Q: 2G ve 3G kapatılırsa eski telefonum çalışır mı?
A: VoLTE desteklemeyen bir telefon, 2G ve 3G kapandığında arama yapamaz. Yalnızca 3G kapatılırsa telefon aramalar için 2G'ye düşer. Eski bir cihazı kullanmayı sürdürecekseniz operatörünüzün kapatma takvimini izleyin.
Q: mmWave neden her yerde yok?
A: Menzili çok kısa olduğu ve engellerden kolay etkilendiği için her birkaç yüz metrede bir baz istasyonu gerektirir. Bu, yalnızca çok yoğun kullanılan noktalarda ekonomik olur.
Q: Wi-Fi ile mobil veri arasındaki fark nedir?
A: Wi-Fi lisanssız frekansları kullanan, kısa menzilli bir yerel ağ teknolojisidir ve sabit internet bağlantınızı kablosuz paylaştırır. Mobil şebeke ise operatörlerin lisanslı frekanslarda işlettiği, geniş alanı kapsayan ve hareket hâlinde kesintisiz çalışan bir ağdır.
:::

## Özet

- Mobil şebekeler hücrelere bölünmüş kapsama alanları ve baz istasyonlarıyla çalışır; “G” nesil demektir.
- 1G analog ses, 2G sayısal ses ve SMS, 3G mobil internet, 4G tümüyle IP tabanlı geniş bant, 5G ise çok yüksek hız, düşük gecikme ve çok sayıda cihaz getirdi.
- Aradaki simgeler ara sürümleri gösterir: G (GPRS), E (EDGE), H ve H+ (HSPA), 4.5G (LTE-Advanced).
- Düşük frekans uzağa gider ama az veri taşır; yüksek frekans çok veri taşır ama kısa menzillidir. 5G bu yüzden sub-6 ve mmWave olarak ikiye ayrılır.
- Standartlardaki tepe hızlar kuramsaldır; gerçek hızı kapsama, yoğunluk ve operatörün frekans varlığı belirler.

## Kaynaklar

- [ITU-R M.2410 – IMT-2020 radyo arayüzleri için asgari teknik başarım gereksinimleri](https://www.itu.int/pub/R-REP-M.2410)
- [ITU-R M.2134 – IMT-Advanced radyo arayüzleri için gereksinimler](https://www.itu.int/pub/R-REP-M.2134)
- [ITU-R M.2083 – IMT Vision: 2020 ve sonrası için çerçeve ve hedefler](https://www.itu.int/rec/R-REC-M.2083)
- [ITU-R M.2160 – IMT-2030 (6G) çerçevesi](https://www.itu.int/rec/R-REC-M.2160)
- [3GPP – Releases (sürümler ve içerikleri)](https://www.3gpp.org/specifications-technologies/releases)
- [3GPP – LTE-Advanced](https://www.3gpp.org/technologies/lte-advanced)
- [3GPP – 5G System Overview](https://www.3gpp.org/technologies/5g-system-overview)
- [GSMA – Mobil teknolojilerin tarihi ve GSM](https://www.gsma.com/about-us/history/)
- [Dünya Sağlık Örgütü – Radiation: 5G mobile networks and health](https://www.who.int/news-room/questions-and-answers/item/radiation-5g-mobile-networks-and-health)
- [Bilgi Teknolojileri ve İletişim Kurumu (BTK)](https://www.btk.gov.tr/)
