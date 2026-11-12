---
title: Every Network Hardware Explained: Modem, Router, Mesh ve Wi-Fi Standartları
slug: every-network-hardware-explained
category: ag-ve-internet
tags: modem, router, wi-fi, mesh, wi-fi 7, access point
description: Modem, router, switch, access point, mesh ve tekrarlayıcı; Wi-Fi 4/5/6/6E/7, 2,4/5/6 GHz bantları, anten sayısı, MIMO ve hüzme yönlendirme; sinyal animasyonlarıyla.
excerpt: Modem ile router aynı şey mi? Mesh sistemi tekrarlayıcıdan neden daha iyi? Bazı router'lar neden daha güçlü çeker? Ağ donanımlarını, Wi-Fi standartlarını, bantları, MIMO'yu ve hüzme yönlendirmeyi sinyal animasyonlarıyla anlatıyoruz.
cover: /assets/covers/network-hardware.svg
cover_alt: Antenlerinden halka halka sinyal yayan bir kablosuz yönlendirici çizimi
series: Every X Explained
series_order: 12
date: 2026-10-31 09:00
---
İnternet sağlayıcınızın verdiği o yanıp sönen kutu, çoğu evde ağın tamamıdır. Hız düştüğünde ya da arka odada bağlantı koptuğunda suçlu da hep odur. Ama o kutunun içinde aslında birkaç ayrı cihaz vardır ve sorunu çözmek için hangisinin ne yaptığını bilmek gerekir.

Bu yazıda ev ve küçük ofis ağlarını oluşturan donanımları tek tek ele alacağız. Ardından Wi-Fi standartlarını, frekans bantlarını ve en çok merak edilen soruyu yanıtlayacağız: bazı cihazların sinyali neden diğerlerinden daha güçlü?

## Temel kavramlar

Bir ev ağında veri şu yolu izler: internet sağlayıcının hattı evinize gelir, **modem** bu hattın sinyalini ağ verisine çevirir, **yönlendirici** (router) bu bağlantıyı evdeki cihazlar arasında paylaştırır, **anahtar** (switch) kablolu cihazları, **erişim noktası** (access point) ise kablosuz cihazları ağa bağlar.

:::svg Bir ev ağında verinin yolu: sağlayıcının hattı modeme gelir, yönlendirici bağlantıyı paylaştırır, anahtar kablolu cihazları, erişim noktası kablosuz cihazları bağlar.
<svg viewBox="0 0 720 300" role="img" aria-label="Soldan sağa: internet bulutu, modem, yönlendirici. Yönlendiriciden bir kol anahtara ve oradan masaüstü bilgisayar ile televizyona; diğer kol erişim noktasına ve oradan kablosuz olarak telefon ile dizüstü bilgisayara gider" data-dur="9">
<path d="M70 150c-22 0-34-12-34-28s14-28 30-26c6-16 20-24 36-24 22 0 36 14 38 32 16 0 26 10 26 24s-10 22-26 22z" class="s-bg3 l-fg w2"/><text x="100" y="134" text-anchor="middle" class="t-s t-b">İnternet</text>
<rect x="210" y="96" width="90" height="56" rx="8" class="s-bg3 l-fg w2"/><text x="255" y="122" text-anchor="middle" class="t-b">Modem</text><text x="255" y="140" text-anchor="middle" class="t-s">sinyal çevirir</text>
<rect x="350" y="90" width="110" height="68" rx="8" class="s-soft l-acc w3"/><text x="405" y="118" text-anchor="middle" class="t-b">Yönlendirici</text><text x="405" y="138" text-anchor="middle" class="t-s">paylaştırır</text>
<rect x="520" y="30" width="90" height="50" rx="8" class="s-bg3 l-fg w2"/><text x="565" y="60" text-anchor="middle" class="t-b">Anahtar</text>
<rect x="520" y="170" width="90" height="56" rx="8" class="s-bg3 l-fg w2"/><text x="565" y="194" text-anchor="middle" class="t-b">Erişim</text><text x="565" y="212" text-anchor="middle" class="t-b">noktası</text>
<path d="M162 124h48M300 124h50M460 110l60-50M460 140l60 50M610 46h40M610 64h40" class="l-fg w2" fill="none"/>
<rect x="650" y="34" width="56" height="20" rx="4" class="s-bg3 l-fg w1"/><text x="678" y="48" text-anchor="middle" class="t-s">PC</text><rect x="650" y="58" width="56" height="20" rx="4" class="s-bg3 l-fg w1"/><text x="678" y="72" text-anchor="middle" class="t-s">TV</text>
<circle cx="565" cy="198" r="50" class="s-none l-acc w2" data-a="wave 2.6"/><circle cx="565" cy="198" r="50" class="s-none l-acc w2" data-a="wave 2.6 1.3"/>
<rect x="640" y="230" width="22" height="38" rx="4" class="s-fg"/><text x="651" y="286" text-anchor="middle" class="t-s">telefon</text>
<rect x="470" y="246" width="50" height="30" rx="4" class="s-fg"/><text x="495" y="294" text-anchor="middle" class="t-s">dizüstü</text>
<text x="255" y="182" text-anchor="middle" class="t-s">genel IP</text><text x="405" y="182" text-anchor="middle" class="t-s">NAT · DHCP · güvenlik duvarı</text>
<circle cx="166" cy="124" r="7" class="s-blue" data-a="move 40 0 .03 .15"/><circle cx="304" cy="124" r="7" class="s-blue" data-a="move 42 0 .18 .3"/>
<circle cx="462" cy="110" r="7" class="s-blue" data-a="move 54 -46 .33 .48"/><circle cx="462" cy="140" r="7" class="s-acc2" data-a="move 54 46 .33 .48"/>
<circle cx="612" cy="46" r="6" class="s-blue" data-a="move 34 0 .5 .62"/><circle cx="600" cy="220" r="6" class="s-acc2" data-a="move 46 26 .5 .7"/>
</svg>
:::

Sağlayıcıların verdiği cihazlar genellikle bunların dördünü tek kutuda birleştirir; bu yüzden gündelik dilde hepsine birden “modem” denir. Kavramları ayırmak ise önemlidir, çünkü sorun yaşadığınızda çözüm çoğu zaman bu işlevlerden yalnızca birini güçlendirmektir.

## Cihazlar tek tek

### Modem

**Modem**, adını *modülatör–demodülatör* sözcüklerinden alır: sağlayıcının hattındaki sinyali, ağ cihazlarının anlayacağı Ethernet verisine çevirir ve tersini yapar. Hattın türüne göre modem de değişir:

| Bağlantı türü | Hat | Cihaz | Tipik hız aralığı |
|---|---|---|---|
| ADSL / VDSL | Bakır telefon hattı | DSL modem | 8–100 Mbit/sn; santrale uzaklıkla düşer |
| Kablo | Koaksiyel kablo TV hattı | DOCSIS kablo modem | 100 Mbit/sn – 1 Gbit/sn ve üzeri |
| Fiber (FTTH) | Fiber optik | ONT (optik ağ sonlandırıcı) | 100 Mbit/sn – 10 Gbit/sn |
| Mobil | 4.5G / 5G | Hücresel modem | Kapsama ve yoğunluğa bağlı |

Fiberde “modem” teknik olarak yanlış bir addır; ışık sinyalini elektrik sinyaline çeviren cihaza **ONT** denir. Modemin tek işi sinyali çevirmektir; tek başına yalnızca bir cihaza internet verebilir.

### Yönlendirici (router)

**Yönlendirici**, iki ağı birbirine bağlar: evinizdeki yerel ağı ve interneti. Ev ağlarında üç kritik işi üstlenir:

- **NAT:** Sağlayıcının verdiği tek genel IP adresini evdeki bütün cihazlara paylaştırır.
- **DHCP:** Ağa bağlanan her cihaza otomatik olarak bir yerel IP adresi verir.
- **Güvenlik duvarı:** Dışarıdan gelen, içeriden istenmemiş bağlantıları engeller.

Yönlendiricinin işlemcisi ve belleği de önemlidir: çok sayıda cihazın bağlı olduğu, VPN'in ya da trafik önceliklendirmenin (**QoS**) kullanıldığı ağlarda zayıf bir yönlendirici, internet hızınız ne olursa olsun darboğaz oluşturur. Tüketici “router”larının neredeyse tamamı aynı kutuda bir anahtar ve bir erişim noktası da barındırır; bu yüzden **kablosuz yönlendirici** olarak anılırlar.

### Anahtar (switch)

**Anahtar**, kablolu cihazları aynı yerel ağda birbirine bağlar. Yönlendiricinizin arkasındaki dört LAN portu aslında küçük bir anahtardır; daha fazla porta ihtiyaç duyduğunuzda ayrı bir anahtar eklersiniz.

Anahtar akıllıca çalışır: her portuna bağlı cihazın MAC adresini öğrenir ve gelen veriyi yalnızca hedef cihazın bulunduğu porta iletir. Eski **hub**'lar ise gelen her veriyi bütün portlara kopyalardı; bu hem yavaş hem de güvensizdi ve hub'lar bugün kullanılmamaktadır.

| Tür | Özellik | Kime uygun |
|---|---|---|
| Yönetilemeyen | Tak ve çalıştır; ayar yok | Evler, küçük ofisler |
| Yönetilebilir | VLAN, port izleme, trafik önceliklendirme | İşletmeler, meraklı ev kullanıcıları |
| PoE | Veriyle birlikte Ethernet kablosundan elektrik verir | Erişim noktaları, güvenlik kameraları, IP telefonlar |

Hız sınıfları da farklıdır: 1 Gbit/sn bugün standarttır; 2,5 Gbit/sn ve 10 Gbit/sn portlu anahtarlar, hızlı fiber bağlantıların ve Wi-Fi 6/7 erişim noktalarının tam hızından yararlanmak için gerekir.

### Erişim noktası (access point)

**Erişim noktası** (AP), kablolu ağı kablosuz yayına çevirir. Yönlendirme yapmaz, IP adresi dağıtmaz; yalnızca Wi-Fi cihazlarını mevcut ağa bağlayan bir köprüdür. Ağa Ethernet kablosuyla bağlandığı için sinyali baştan, tam güçle üretir. Bu, kapsama sorunlarının **en iyi** çözümüdür: evin uzak ucuna bir kablo çekip oraya bir erişim noktası koymak, her kablosuz çözümden daha hızlı ve kararlıdır. İş yerlerinde, otellerde ve okullarda tavanlarda gördüğünüz disk biçimli cihazlar erişim noktalarıdır.

### Tekrarlayıcı ve menzil genişletici

**Tekrarlayıcı** (repeater, extender), mevcut Wi-Fi sinyalini kablosuz olarak alıp yeniden yayınlar. Ucuzdur ve kurulumu kolaydır; kablo gerektirmez. Ama iki önemli zayıflığı vardır.

Birincisi, tek radyolu bir tekrarlayıcı aynı kanalı hem yönlendiriciyle hem de cihazlarınızla konuşmak için kullanır; her veri paketi havada iki kez yol alır ve kullanılabilir hız kabaca **yarıya iner**. İkincisi, tekrarlayıcı zaten zayıflamış bir sinyali çoğaltır: sinyalin kötü olduğu yere koyarsanız kötü sinyali yayar. Doğru konum, yönlendirici ile kapsama sorunu olan bölgenin ortasında, sinyalin hâlâ güçlü olduğu noktadır. Ayrıca çoğu tekrarlayıcı ayrı bir ağ adı oluşturur ya da cihazların geçişini iyi yönetemez; evde dolaşırken bağlantı takılabilir.

### Mesh sistemleri

**Mesh** (örgü) Wi-Fi, birbiriyle eşgüdümlü çalışan iki ya da daha fazla düğümden oluşur. Tekrarlayıcıdan üç temel farkı vardır:

- **Tek ağ, kesintisiz geçiş:** Bütün düğümler aynı ağ adını yayınlar ve cihazınızı en uygun düğüme yönlendirir.
- **Ayrı taşıma bağlantısı:** İyi mesh sistemleri, düğümler arasındaki iletişim (**backhaul**) için ayrı bir radyo ya da bant kullanır; böylece hız yarıya inmez. Üç bantlı sistemlerin üçüncü bandı bu iş içindir.
- **Merkezî yönetim:** Düğümler kanal seçimini ve yük dağılımını birlikte ayarlar.

En iyi sonuç, düğümlerin birbirine Ethernet kablosuyla bağlandığı **kablolu backhaul** ile alınır; bu durumda her düğüm fiilen bir erişim noktası gibi çalışır.

:::svg Tekrarlayıcı aynı kanalı iki yönde kullandığı için hızı düşürür. Mesh düğümleri kendi aralarında ayrı bir bağlantı kullanır ve tek bir ağ gibi davranır.
<svg viewBox="0 0 720 300" role="img" aria-label="Üstte tekrarlayıcı: yönlendiriciden tekrarlayıcıya ve tekrarlayıcıdan telefona aynı renkli bağlantı; hız yarıya iner. Altta mesh: ana düğüm ile uydu düğüm arasında ayrı renkli bir taşıma bağlantısı, uydudan telefona tam hızda bağlantı" data-dur="9">
<text x="20" y="28" class="t-b">Tekrarlayıcı (tek radyo)</text>
<rect x="40" y="50" width="100" height="56" rx="8" class="s-bg3 l-fg w2"/><text x="90" y="83" text-anchor="middle" class="t-s t-b">Yönlendirici</text>
<rect x="310" y="50" width="100" height="56" rx="8" class="s-bg3 l-fg w2"/><text x="360" y="83" text-anchor="middle" class="t-s t-b">Tekrarlayıcı</text>
<rect x="600" y="56" width="26" height="44" rx="5" class="s-fg"/>
<path d="M140 78h170M410 78h190" class="l-acc2 w3 dash" fill="none"/>
<circle cx="146" cy="78" r="8" class="s-acc2" data-a="move 158 0 .03 .23"/><circle cx="416" cy="78" r="8" class="s-acc2" data-a="move 178 0 .27 .47"/>
<text x="225" y="62" text-anchor="middle" class="t-s">kanal 36</text><text x="505" y="62" text-anchor="middle" class="t-s">yine kanal 36</text>
<text x="360" y="130" text-anchor="middle" class="t-s t-acc2 t-b">aynı kanal sırayla kullanılır → hız yaklaşık yarıya iner</text>
<path d="M20 150h680" class="l-line w1 dash" fill="none"/>
<text x="20" y="178" class="t-b">Mesh (ayrı taşıma bağlantısı)</text>
<rect x="40" y="200" width="100" height="56" rx="8" class="s-soft l-acc w2"/><text x="90" y="233" text-anchor="middle" class="t-s t-b">Ana düğüm</text>
<rect x="310" y="200" width="100" height="56" rx="8" class="s-soft l-acc w2"/><text x="360" y="233" text-anchor="middle" class="t-s t-b">Uydu düğüm</text>
<rect x="600" y="206" width="26" height="44" rx="5" class="s-fg"/>
<path d="M140 228h170" class="l-blue w4" fill="none"/><path d="M410 228h190" class="l-acc w3 dash" fill="none"/>
<circle cx="146" cy="228" r="8" class="s-blue" data-a="move 158 0 .5 .62"/><circle cx="416" cy="228" r="8" class="s-acc" data-a="move 178 0 .62 .74"/>
<circle cx="146" cy="228" r="8" class="s-blue" data-a="move 158 0 .74 .86"/><circle cx="416" cy="228" r="8" class="s-acc" data-a="move 178 0 .86 .98"/>
<text x="225" y="212" text-anchor="middle" class="t-s">ayrı bant (ya da kablo)</text><text x="505" y="212" text-anchor="middle" class="t-s">cihaz bandı</text>
<text x="360" y="284" text-anchor="middle" class="t-s t-acc t-b">iki bağlantı aynı anda çalışır → hız korunur</text>
</svg>
:::

### Diğer donanımlar

- **Ağ kartı (NIC):** Bir cihazın ağa bağlanmasını sağlayan donanımdır; Ethernet ya da Wi-Fi olabilir. Bağlantınızın hızı, yönlendiricinin değil, iki uçtan **zayıf olanın** yeteneğiyle sınırlıdır.
- **Powerline adaptörü:** Veriyi evin elektrik tesisatı üzerinden taşır. Kablo çekmenin mümkün olmadığı yerlerde bir seçenektir; başarımı tesisatın yaşına ve hat üzerindeki cihazlara çok bağlıdır.
- **Ethernet kablosu:** Cat 5e kablolar 1 Gbit/sn'yi, Cat 6 kablolar kısa mesafede (55 metreye kadar) 10 Gbit/sn'yi, Cat 6a kablolar 100 metrede 10 Gbit/sn'yi destekler.
- **Donanımsal güvenlik duvarı:** İşletmelerde yönlendiricinin önüne ya da yerine konan, ayrıntılı trafik denetimi yapan cihazdır.

| Cihaz | Ne yapar | OSI katmanı | Ne zaman gerekir |
|---|---|---|---|
| Modem / ONT | Hat sinyalini ağ verisine çevirir | 1 | Her internet bağlantısında |
| Yönlendirici | Ağları bağlar; NAT, DHCP, güvenlik duvarı | 3 | Birden çok cihaz internete çıkacaksa |
| Anahtar | Kablolu cihazları yerel ağda bağlar | 2 | Port sayısı yetmediğinde |
| Erişim noktası | Kablolu ağı Wi-Fi'a çevirir | 2 | Kablo çekilebilen yerde kapsama gerektiğinde |
| Tekrarlayıcı | Wi-Fi sinyalini kablosuz yineler | 1–2 | Kablo yoksa, küçük bir ölü bölge için |
| Mesh sistemi | Eşgüdümlü düğümlerle geniş kapsama | 2–3 | Büyük ya da çok katlı evlerde |

## Wi-Fi standartları

Wi-Fi, IEEE'nin **802.11** standart ailesine dayanır. Harfli adlar (802.11n, ac, ax) uzun süre kafa karıştırdığı için Wi-Fi Alliance 2018'de numaralı adlara geçti.

| Ad | IEEE standardı | Yıl | Bantlar | En geniş kanal | En yoğun kodlama | Kuramsal en yüksek hız | Başlıca yenilik |
|---|---|---|---|---|---|---|---|
| Wi-Fi 4 | 802.11n | 2009 | 2,4 ve 5 GHz | 40 MHz | 64-QAM | 600 Mbit/sn | MIMO |
| Wi-Fi 5 | 802.11ac | 2013 | 5 GHz | 160 MHz | 256-QAM | 6,9 Gbit/sn | Geniş kanallar, MU-MIMO (indirme) |
| Wi-Fi 6 | 802.11ax | 2019 | 2,4 ve 5 GHz | 160 MHz | 1024-QAM | 9,6 Gbit/sn | OFDMA, iki yönlü MU-MIMO, TWT |
| Wi-Fi 6E | 802.11ax | 2020 | 2,4, 5 ve 6 GHz | 160 MHz | 1024-QAM | 9,6 Gbit/sn | 6 GHz bandı |
| Wi-Fi 7 | 802.11be | 2024 | 2,4, 5 ve 6 GHz | 320 MHz | 4096-QAM | 46 Gbit/sn | Çok bağlantılı çalışma (MLO) |

:::chart
{"type":"bar","log":true,"title":"Wi-Fi nesillerinin kuramsal en yüksek veri hızı (logaritmik ölçek)","labels":["Wi-Fi 4 (2009)","Wi-Fi 5 (2013)","Wi-Fi 6 / 6E (2019)","Wi-Fi 7 (2024)"],"datasets":[{"label":"Kuramsal en yüksek hız (Mbit/sn)","data":[600,6933,9608,46120]}],"unit":"Mbit/sn","yTitle":"Mbit/sn (logaritmik)","source":"IEEE 802.11n, 802.11ac, 802.11ax ve 802.11be standartları; Wi-Fi Alliance","sourceUrl":"https://www.wi-fi.org/discover-wi-fi","date":"2009–2024","note":"Standardın izin verdiği en geniş kanal ve en fazla uzamsal akışla ulaşılan kuramsal değerlerdir; tüketici cihazları bunun çok altında çalışır."}
:::

Bu sayılar, standardın izin verdiği en geniş kanal ve sekiz ya da on altı uzamsal akışla hesaplanan üst sınırlardır. Bir telefon genellikle yalnızca iki akışı destekler; gerçek hayatta göreceğiniz hız tepe değerin küçük bir bölümüdür. Yine de her neslin getirdiği yenilikler somuttur:

- **OFDMA** (Wi-Fi 6): Bir kanalı alt parçalara bölerek aynı anda birden çok cihaza veri gönderir. Hızı değil, kalabalık ağlardaki verimliliği ve gecikmeyi iyileştirir.
- **TWT** (Wi-Fi 6): Cihazlara ne zaman uyanacaklarını söyler; pil ömrünü uzatır.
- **6 GHz** (Wi-Fi 6E): Eski cihazların bulunmadığı, geniş ve boş bir bant açar.
- **MLO** (Wi-Fi 7): Bir cihaz aynı anda birden çok banda bağlanabilir; hız artar, bir bant kötüleştiğinde bağlantı kopmaz.

Bir standarttan yararlanmak için **iki ucun da** onu desteklemesi gerekir: Wi-Fi 7 bir yönlendirici, Wi-Fi 5 bir dizüstü bilgisayarla Wi-Fi 5 konuşur.

## Frekans bantları: 2,4, 5 ve 6 GHz

Wi-Fi üç frekans bandında çalışır ve aralarındaki fark, mobil şebekelerdekiyle aynı fizik kuralına dayanır: frekans yükseldikçe taşınabilen veri artar, menzil ve engel aşma yeteneği azalır.

| Özellik | 2,4 GHz | 5 GHz | 6 GHz |
|---|---|---|---|
| Menzil ve duvar geçişi | En iyi | Orta | En zayıf |
| Hız potansiyeli | Düşük | Yüksek | En yüksek |
| Örtüşmeyen kanal sayısı (20 MHz) | 3 (1, 6 ve 11) | Yaklaşık 25 (bölgeye göre) | AB'de 24, ABD'de 59 |
| Parazit | Çok yoğun: komşu ağlar, Bluetooth, mikrodalga fırın | Daha az | Çok az; yalnızca yeni cihazlar |
| Uygun olduğu durum | Uzak odalar, akıllı ev cihazları | Gündelik kullanımın çoğu | Aynı odada çok yüksek hız |
| Gerekli standart | Tümü | Wi-Fi 4 ve sonrası | Wi-Fi 6E ve Wi-Fi 7 |

2,4 GHz bandının sorunu darlığıdır: yalnızca üç kanal birbirine karışmadan kullanılabilir. Bir apartmanda onlarca ağ bu üç kanalı paylaşır. 6 GHz bandı ise ABD'de 1200 MHz, Avrupa Birliği'nde 500 MHz genişliğinde yepyeni bir alandır.

:::svg Düşük frekans duvarlardan daha iyi geçer ve uzağa ulaşır; yüksek frekans daha çok veri taşır ama her engelde daha fazla zayıflar.
<svg viewBox="0 0 720 300" role="img" aria-label="Solda yönlendirici, ortada iki duvar. Üç satırda üç bant: 2,4 gigahertz dalgası iki duvarı da geçip güçlü ulaşır; 5 gigahertz dalgası ikinci duvardan sonra zayıflar; 6 gigahertz dalgası birinci duvardan sonra belirgin biçimde zayıflar" data-dur="8">
<rect x="20" y="110" width="70" height="80" rx="8" class="s-soft l-acc w2"/><path d="M40 110V84M70 110V84" class="l-acc w3 rc"/><text x="55" y="156" text-anchor="middle" class="t-s t-b">Router</text>
<rect x="330" y="20" width="16" height="260" class="s-mut"/><rect x="530" y="20" width="16" height="260" class="s-mut"/><text x="338" y="296" text-anchor="middle" class="t-s">duvar</text><text x="538" y="296" text-anchor="middle" class="t-s">duvar</text>
<text x="110" y="52" class="t-b">2,4 GHz</text><text x="110" y="142" class="t-b">5 GHz</text><text x="110" y="232" class="t-b">6 GHz</text>
<path d="M110 70q27-22 55 0t55 0 55 0 55 0" class="l-blue w4 rc" fill="none"/><path d="M346 70q27-18 55 0t55 0 55 0 19 0" class="l-blue w3 rc" fill="none"/><path d="M546 70q27-14 55 0t55 0 30 0" class="l-blue w3 rc" fill="none"/>
<path d="M110 160q14-22 27 0t27 0 27 0 27 0 27 0 27 0 27 0 27 0" class="l-acc w4 rc" fill="none"/><path d="M346 160q14-14 27 0t27 0 27 0 27 0 27 0 27 0 22 0" class="l-acc w3 rc" fill="none"/><path d="M546 160q14-6 27 0t27 0 27 0 27 0 27 0" class="l-acc w1 rc" fill="none"/>
<path d="M110 250q9-22 18 0t18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0" class="l-acc2 w4 rc" fill="none"/><path d="M346 250q9-8 18 0t18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0 18 0" class="l-acc2 w2 rc" fill="none"/><path d="M546 250h120" class="l-acc2 w1 dash" fill="none"/>
<circle cx="110" cy="70" r="7" class="s-blue" data-a="move 570 0 .02 .9"/><circle cx="110" cy="160" r="7" class="s-acc" data-a="move 480 0 .02 .8"/><circle cx="110" cy="250" r="7" class="s-acc2" data-a="move 330 0 .02 .6"/>
<text x="700" y="52" text-anchor="end" class="t-s t-b">güçlü</text><text x="700" y="142" text-anchor="end" class="t-s t-b">zayıf</text><text x="700" y="232" text-anchor="end" class="t-s t-b">çok zayıf</text>
</svg>
:::

## Anten, MIMO ve hüzme yönlendirme

### Uzamsal akışlar ve MIMO

**MIMO** (çoklu giriş, çoklu çıkış), birden fazla antenle aynı frekansta aynı anda birden fazla veri akışı göndermektir. Sinyallerin duvarlardan ve eşyalardan yansıyarak farklı yollardan gelmesi, alıcının bu akışları birbirinden ayırmasını sağlar. Her bağımsız akışa **uzamsal akış** (spatial stream) denir ve hız, akış sayısıyla doğru orantılı artar.

Bir cihazın yeteneği “2×2” ya da “4×4” biçiminde yazılır: ilk sayı gönderen, ikinci sayı alan anten zincirlerini gösterir. Burada önemli bir ayrıntı vardır: 4×4 bir yönlendirici ile 2×2 bir telefon arasındaki bağlantı en fazla iki akış kullanır. Fazladan antenler boşa gitmez; sinyal kalitesini ve menzili iyileştirir, ama hızı ikiye katlamaz.

**MU-MIMO** ise akışları birden çok cihaza aynı anda dağıtır: dört akışlı bir yönlendirici, iki akışlı iki telefona aynı anda hizmet verebilir.

:::chart
{"type":"bar","title":"Wi-Fi 6'da kanal genişliğine göre uzamsal akış başına en yüksek hız","labels":["20 MHz","40 MHz","80 MHz","160 MHz"],"datasets":[{"label":"1 akış","data":[143.4,286.8,600.5,1201]},{"label":"2 akış","data":[286.8,573.5,1201,2402]}],"unit":"Mbit/sn","xTitle":"Kanal genişliği","yTitle":"Bağlantı hızı (Mbit/sn)","source":"IEEE 802.11ax MCS tablosu (1024-QAM, 5/6 kodlama, 0,8 µs koruma aralığı)","sourceUrl":"https://standards.ieee.org/ieee/802.11ax/7180/","date":"2021","note":"Bağlantı (PHY) hızıdır; gerçek veri aktarım hızı bunun kabaca yüzde 60–70'idir."}
:::

Grafik, hızı belirleyen iki kolu açıkça gösterir: kanal genişliğini iki katına çıkarmak hızı iki katına çıkarır; akış sayısını iki katına çıkarmak da öyle. Bir yönlendiricinin kutusundaki “AX3000” gibi sınıf adları, bütün bantlardaki bu kuramsal hızların toplamıdır; tek bir cihazın alacağı hızı göstermez.

### Hüzme yönlendirme (beamforming)

Sıradan bir anten sinyali her yöne eşit yayar. **Hüzme yönlendirme** ile yönlendirici, birden çok antenden gönderdiği sinyallerin zamanlamasını öyle ayarlar ki dalgalar hedef cihazın bulunduğu yönde birbirini güçlendirir, diğer yönlerde zayıflatır. Toplam yayın gücü artmaz; enerji işe yarayan yöne odaklanır. Sonuç, orta mesafelerde daha güçlü sinyal ve daha yüksek hızdır.

:::svg Hüzme yönlendirme: sinyal her yöne eşit yayılmak yerine, antenlerin zamanlaması ayarlanarak hedef cihazın yönünde güçlendirilir.
<svg viewBox="0 0 720 260" role="img" aria-label="Solda hüzme yönlendirme olmadan: yönlendiriciden her yöne eşit halkalar yayılır ve telefona zayıf sinyal ulaşır. Sağda hüzme yönlendirme ile: sinyal telefona doğru odaklanmış bir koni biçiminde gider ve güçlü ulaşır" data-dur="6">
<text x="180" y="26" text-anchor="middle" class="t-l">Yönlendirme yok</text><text x="540" y="26" text-anchor="middle" class="t-l">Hüzme yönlendirme</text>
<path d="M360 10v240" class="l-line w1 dash" fill="none"/>
<rect x="80" y="120" width="60" height="40" rx="6" class="s-soft l-acc w2"/><path d="M95 120V98M125 120V98" class="l-acc w3 rc"/>
<circle cx="110" cy="140" r="110" class="s-none l-acc w2" data-a="wave 3"/><circle cx="110" cy="140" r="110" class="s-none l-acc w2" data-a="wave 3 1"/><circle cx="110" cy="140" r="110" class="s-none l-acc w2" data-a="wave 3 2"/>
<rect x="290" y="118" width="26" height="44" rx="5" class="s-fg"/><text x="303" y="186" text-anchor="middle" class="t-s t-b">zayıf</text>
<text x="180" y="244" text-anchor="middle" class="t-s">enerji her yöne dağılır</text>
<rect x="420" y="120" width="60" height="40" rx="6" class="s-soft l-acc w2"/><path d="M435 120V98M465 120V98" class="l-acc w3 rc"/>
<path d="M480 140 650 96v88z" class="s-soft l-acc w2"/>
<path d="M500 132v16" class="l-acc w3 rc" data-a="move 130 0 .05 .5"/><path d="M500 126v28" class="l-acc w3 rc" data-a="move 130 0 .35 .8"/><path d="M500 132v16" class="l-acc w3 rc" data-a="move 130 0 .6 .98"/>
<rect x="656" y="118" width="26" height="44" rx="5" class="s-fg"/><text x="669" y="186" text-anchor="middle" class="t-s t-b t-acc">güçlü</text>
<text x="540" y="244" text-anchor="middle" class="t-s">aynı güç, hedef yönde odaklanır</text>
</svg>
:::

## Bazı router'lar neden daha güçlü çeker?

İlk akla gelen yanıt “daha güçlü yayın yapıyor”dur, ama bu çoğu zaman yanlıştır. Yayın gücü yasal olarak sınırlıdır: örneğin Avrupa'da 2,4 GHz bandında izin verilen en yüksek eşdeğer yayın gücü 100 miliwatt'tır ve bütün cihazlar bu sınıra uymak zorundadır. Fark, başka yerlerden gelir:

- **Anten tasarımı ve kazancı:** Kazancı yüksek (dBi değeri büyük) bir anten enerji üretmez; onu yatay düzlemde yoğunlaştırır. Tek katlı bir evde menzili artırır, ama üst ve alt katlara giden sinyali zayıflatır.
- **Anten ve akış sayısı:** Daha çok anten, daha iyi alım, daha etkili hüzme yönlendirme ve yansımalardan daha iyi yararlanma demektir.
- **Alıcı duyarlılığı:** Bağlantı iki yönlüdür. Yönlendirici ne kadar güçlü yayın yaparsa yapsın, telefonunuzun küçük anteniyle gönderdiği zayıf yanıtı duyamıyorsa bağlantı kurulamaz. İyi yönlendiriciler zayıf sinyalleri daha iyi ayırt eden alıcı devrelerine sahiptir.
- **Radyo ön uç kalitesi:** Güç yükselteçlerinin ve süzgeçlerin kalitesi, sinyalin ne kadar temiz üretildiğini belirler. Temiz sinyal, daha yoğun kodlamaların (ve dolayısıyla daha yüksek hızın) daha uzak mesafede kullanılabilmesini sağlar.
- **Bant ve kanal seçimi:** Kalabalık bir kanalda çalışan pahalı bir yönlendirici, boş bir kanaldaki ucuz bir yönlendiriciden kötü sonuç verebilir.
- **Konum:** En etkili ve en ucuz etken budur.

Son madde ayrı bir vurguyu hak eder. Sinyal her engelde zayıflar ve malzemeler arasında büyük fark vardır: alçıpan ve ahşap az, tuğla orta, beton ve özellikle demir donatılı beton, metal yüzeyler, aynalar ve su dolu akvaryumlar çok zayıflatır. Yönlendiriciyi evin ortasına yakın, yerden yüksek ve açık bir yere koymak; onu bir dolabın içine, televizyonun arkasına ya da evin bir köşesine koymaktan çok daha iyi sonuç verir.

:::tip Önce konumu, sonra kanalı, en son donanımı değiştirin
Kapsama sorununda sıra şöyle olmalıdır: yönlendiriciyi evin ortasına ve yükseğe taşıyın; 5 GHz bandını ve boş bir kanalı kullandığınızdan emin olun; hâlâ yetmiyorsa kabloyla bağlı bir erişim noktası ya da mesh sistemi ekleyin. Daha pahalı tek bir yönlendirici, kötü bir konumu nadiren telafi eder.
:::

## Hangisini seçmeliyim?

| Durum | Makul çözüm |
|---|---|
| Küçük daire, tek katlı | Ortaya yerleştirilmiş tek bir Wi-Fi 6 yönlendirici |
| Büyük ya da çok katlı ev, kablo çekilebiliyor | Yönlendirici ve kabloyla bağlı erişim noktaları |
| Büyük ya da çok katlı ev, kablo yok | Üç bantlı mesh sistemi |
| Tek bir odada zayıf sinyal | Önce yönlendiricinin konumu; olmazsa doğru yerleştirilmiş bir tekrarlayıcı ya da mesh düğümü |
| Masaüstü, konsol, televizyon | Mümkünse Ethernet kablosu |
| Çok sayıda kablolu cihaz | Yönetilemeyen bir gigabit anahtar |
| Kamera ve erişim noktalarını tek kabloyla beslemek | PoE anahtar |
| Kalabalık apartmanda parazit | 5 GHz ya da 6 GHz bandına geçmek |
| 1 Gbit/sn'den hızlı fiber bağlantı | 2,5 Gbit/sn portlu yönlendirici ve Wi-Fi 6E ya da Wi-Fi 7 |
| Sağlayıcının cihazı yetersiz | Cihazı köprü kipine alıp arkasına kendi yönlendiricinizi bağlamak |

Yeni bir yönlendirici alırken bugün için makul alt sınır Wi-Fi 6'dır. Wi-Fi 6E ve Wi-Fi 7, 6 GHz destekli cihazlarınız varsa ve bağlantınız gerçekten hızlıysa anlam kazanır. Wi-Fi 7'nin 320 MHz kanalları ve 6 GHz bandı yalnızca kısa mesafede tam verim sağlar.

## Sık sorulan sorular

:::faq
Q: Modem ile router aynı şey mi?
A: Hayır. Modem sağlayıcının hattındaki sinyali ağ verisine çevirir; router ise bu bağlantıyı evdeki cihazlar arasında paylaştırır. Sağlayıcıların verdiği cihazlar genellikle ikisini tek kutuda birleştirir.
Q: Mesh mi, tekrarlayıcı mı almalıyım?
A: Küçük bir ölü bölge için doğru yere konmuş bir tekrarlayıcı yeterli olabilir. Evin geneline yayılan bir kapsama sorunu, çok katlı bir ev ya da dolaşırken kesintisiz bağlantı gerekiyorsa mesh çok daha iyi sonuç verir.
Q: Daha çok antenli router daha mı iyidir?
A: Genellikle evet, ama anten sayısı tek başına bir ölçü değildir. Önemli olan desteklenen uzamsal akış sayısı ve radyo devrelerinin kalitesidir. Ayrıca cihazlarınız çoğunlukla iki akışla sınırlıdır.
Q: 5 GHz neden 2,4 GHz'ten daha kısa çekiyor?
A: Yüksek frekanslı dalgalar duvar ve eşya gibi engellerde daha fazla zayıflar. 5 GHz daha hızlıdır ama menzili kısadır; 2,4 GHz daha yavaştır ama uzağa ulaşır.
Q: İnternet paketim 1000 Mbit/sn ama Wi-Fi'da 300 görüyorum. Neden?
A: Wi-Fi hızını cihazınızın akış sayısı, kanal genişliği, yönlendiriciye uzaklık ve aradaki engeller belirler. İki akışlı bir telefon, 80 MHz kanalda ve ideal koşullarda bile gerçek aktarımda birkaç yüz megabitle sınırlıdır. Tam hızı görmek için kabloyla ölçün.
Q: Wi-Fi 7 router alırsam eski cihazlarım çalışır mı?
A: Evet. Wi-Fi standartları geriye dönük uyumludur. Eski cihazlar kendi destekledikleri standartla bağlanır, ama yeni standardın getirdiği hızlardan yararlanamaz.
Q: Wi-Fi sinyali sağlığa zararlı mı?
A: Wi-Fi iyonlaştırıcı olmayan, düşük güçlü radyo dalgaları kullanır. Dünya Sağlık Örgütü, uluslararası sınır değerlerin altındaki maruziyetin bilinen bir sağlık etkisine yol açtığına dair kanıt bulunmadığını belirtmektedir.
:::

## Özet

- Modem sinyali çevirir, yönlendirici bağlantıyı paylaştırır, anahtar kablolu cihazları, erişim noktası kablosuz cihazları bağlar; ev tipi cihazlar bunları tek kutuda toplar.
- Tekrarlayıcı sinyali kablosuz yineler ve hızı düşürür; mesh sistemleri eşgüdümlü düğümler ve ayrı taşıma bağlantısıyla bunu aşar; en iyi çözüm kablolu erişim noktasıdır.
- Wi-Fi 4'ten 7'ye her nesil daha geniş kanal, daha yoğun kodlama ve daha akıllı paylaşım getirdi; yararlanmak için iki ucun da standardı desteklemesi gerekir.
- 2,4 GHz uzağa ulaşır ama kalabalık ve yavaştır; 5 GHz dengelidir; 6 GHz çok hızlı ama kısa menzillidir.
- Hızı kanal genişliği ve uzamsal akış sayısı belirler; MIMO akışları çoğaltır, hüzme yönlendirme enerjiyi hedefe odaklar.
- Sinyal gücündeki farkın kaynağı yayın gücü değil; anten tasarımı, alıcı duyarlılığı, radyo kalitesi ve en çok da konumdur.

## Kaynaklar

- [Wi-Fi Alliance – Discover Wi-Fi (nesiller ve adlandırma)](https://www.wi-fi.org/discover-wi-fi)
- [Wi-Fi Alliance – Wi-Fi 6 ve Wi-Fi 6E](https://www.wi-fi.org/discover-wi-fi/wi-fi-certified-6)
- [Wi-Fi Alliance – Wi-Fi CERTIFIED 7](https://www.wi-fi.org/discover-wi-fi/wi-fi-certified-7)
- [IEEE 802.11ax-2021 standardı](https://standards.ieee.org/ieee/802.11ax/7180/)
- [IEEE 802.11be-2024 standardı](https://standards.ieee.org/ieee/802.11be/7516/)
- [IEEE 802.11 Çalışma Grubu](https://www.ieee802.org/11/)
- [ETSI EN 300 328 – 2,4 GHz bandında geniş bant veri iletim sistemleri (güç sınırları)](https://www.etsi.org/deliver/etsi_en/300300_300399/300328/)
- [Avrupa Komisyonu – 6 GHz bandının alt bölümünün Wi-Fi için uyumlaştırılması (2021/1067 sayılı Karar)](https://eur-lex.europa.eu/eli/dec_impl/2021/1067/oj)
- [FCC – 6 GHz bandının lisanssız kullanıma açılması (2020)](https://www.fcc.gov/document/fcc-opens-6-ghz-band-wi-fi-and-other-unlicensed-uses-0)
- [CableLabs – DOCSIS teknolojisi](https://www.cablelabs.com/technologies/docsis-4-0-technology)
- [Dünya Sağlık Örgütü – Electromagnetic fields and public health](https://www.who.int/news-room/questions-and-answers/item/radiation-electromagnetic-fields)
