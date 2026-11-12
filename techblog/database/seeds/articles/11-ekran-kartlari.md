---
title: Every GPU Explained: GT'den RTX 50'ye, Radeon'dan Veri Merkezi Kartlarına
slug: every-gpu-explained
category: donanim
tags: ekran kartı, gpu, nvidia, rtx, radeon, intel arc
description: NVIDIA GT, GTX, RTX 20/30/40/50, Quadro ve RTX PRO, veri merkezi kartları (T4'ten B200'e), AMD Radeon ve Instinct, Intel Arc; kıyaslama grafikleriyle.
excerpt: RTX 5070 Ti adındaki her parça ne anlama gelir? GTX ile RTX farkı nedir, H100 neden bir oyun kartı değildir? NVIDIA, AMD ve Intel'in bütün ekran kartı ailelerini kıyaslama grafikleriyle açıklıyoruz.
cover: /assets/covers/gpus.svg
cover_alt: İki fanlı bir ekran kartı ve yanında yükselen başarım çubukları çizimi
series: Every X Explained
series_order: 11
date: 2026-10-30 09:00
---
Ekran kartı, bir zamanlar yalnızca oyuncuların ilgilendiği bir parçaydı. Bugün aynı çip ailesi oyunları çiziyor, videoları kurguluyor, bilimsel benzetimleri hızlandırıyor ve yapay zekâ modellerini eğitiyor. Buna karşılık ürün adları giderek karmaşıklaştı: GT, GTX, RTX, Ti, SUPER, XT, XTX, A100, H100, B200…

Bu yazıda önce bir ekran kartının ne yaptığını ve onu değerlendirirken bakılacak kavramları açıklayacağız. Ardından NVIDIA, AMD ve Intel'in bütün ailelerini, giriş düzeyi oyun kartlarından veri merkezi hızlandırıcılarına kadar ele alacağız. Kıyaslama grafikleri PassMark'ın açık veritabanına dayanır.

## Temel kavramlar

### GPU nedir, CPU'dan farkı ne?

**GPU** (grafik işlem birimi), aynı basit işlemi çok sayıda veri üzerinde **aynı anda** yapmak için tasarlanmış bir işlemcidir. Bir işlemci (CPU) az sayıda ama çok yetenekli çekirdeğe sahiptir ve karmaşık, dallanan işleri sırayla çözer. Bir GPU ise binlerce küçük ve basit çekirdeğe sahiptir.

Ekrandaki 8 milyon pikselin her birinin rengini hesaplamak tam da GPU'ya göre bir iştir: her piksel aynı formülle, birbirinden bağımsız hesaplanır. Yapay sinir ağlarındaki dev matris çarpımları da aynı yapıdadır; ekran kartlarının yapay zekânın temel donanımı hâline gelmesinin nedeni budur.

:::svg CPU işleri az sayıda güçlü çekirdekle sırayla çözer. GPU aynı basit işlemi binlerce küçük çekirdekle aynı anda yapar.
<svg viewBox="0 0 720 280" role="img" aria-label="Solda dört büyük çekirdekli CPU, bir ızgaradaki kareleri teker teker boyar. Sağda çok sayıda küçük çekirdekli GPU, aynı ızgaranın bütün karelerini aynı anda boyar" data-dur="8">
<text x="180" y="26" text-anchor="middle" class="t-l">CPU: sırayla</text><text x="540" y="26" text-anchor="middle" class="t-l">GPU: paralel</text>
<path d="M360 10v260" class="l-line w1 dash" fill="none"/>
<g class="s-soft l-acc w2"><rect x="40" y="50" width="50" height="50" rx="8"/><rect x="100" y="50" width="50" height="50" rx="8"/><rect x="40" y="110" width="50" height="50" rx="8"/><rect x="100" y="110" width="50" height="50" rx="8"/></g>
<text x="95" y="186" text-anchor="middle" class="t-s">4 güçlü çekirdek</text>
<g class="s-line"><rect x="200" y="50" width="34" height="34" rx="4"/><rect x="240" y="50" width="34" height="34" rx="4"/><rect x="280" y="50" width="34" height="34" rx="4"/><rect x="200" y="90" width="34" height="34" rx="4"/><rect x="240" y="90" width="34" height="34" rx="4"/><rect x="280" y="90" width="34" height="34" rx="4"/><rect x="200" y="130" width="34" height="34" rx="4"/><rect x="240" y="130" width="34" height="34" rx="4"/><rect x="280" y="130" width="34" height="34" rx="4"/></g>
<g class="s-blue"><rect x="200" y="50" width="34" height="34" rx="4" data-a="show .05"/><rect x="240" y="50" width="34" height="34" rx="4" data-a="show .15"/><rect x="280" y="50" width="34" height="34" rx="4" data-a="show .25"/><rect x="200" y="90" width="34" height="34" rx="4" data-a="show .35"/><rect x="240" y="90" width="34" height="34" rx="4" data-a="show .45"/><rect x="280" y="90" width="34" height="34" rx="4" data-a="show .55"/><rect x="200" y="130" width="34" height="34" rx="4" data-a="show .65"/><rect x="240" y="130" width="34" height="34" rx="4" data-a="show .75"/><rect x="280" y="130" width="34" height="34" rx="4" data-a="show .85"/></g>
<text x="257" y="186" text-anchor="middle" class="t-s">kareler tek tek boyanır</text>
<g class="s-soft l-acc w1"><rect x="390" y="50" width="16" height="16" rx="3"/><rect x="410" y="50" width="16" height="16" rx="3"/><rect x="430" y="50" width="16" height="16" rx="3"/><rect x="450" y="50" width="16" height="16" rx="3"/><rect x="470" y="50" width="16" height="16" rx="3"/><rect x="390" y="70" width="16" height="16" rx="3"/><rect x="410" y="70" width="16" height="16" rx="3"/><rect x="430" y="70" width="16" height="16" rx="3"/><rect x="450" y="70" width="16" height="16" rx="3"/><rect x="470" y="70" width="16" height="16" rx="3"/><rect x="390" y="90" width="16" height="16" rx="3"/><rect x="410" y="90" width="16" height="16" rx="3"/><rect x="430" y="90" width="16" height="16" rx="3"/><rect x="450" y="90" width="16" height="16" rx="3"/><rect x="470" y="90" width="16" height="16" rx="3"/><rect x="390" y="110" width="16" height="16" rx="3"/><rect x="410" y="110" width="16" height="16" rx="3"/><rect x="430" y="110" width="16" height="16" rx="3"/><rect x="450" y="110" width="16" height="16" rx="3"/><rect x="470" y="110" width="16" height="16" rx="3"/><rect x="390" y="130" width="16" height="16" rx="3"/><rect x="410" y="130" width="16" height="16" rx="3"/><rect x="430" y="130" width="16" height="16" rx="3"/><rect x="450" y="130" width="16" height="16" rx="3"/><rect x="470" y="130" width="16" height="16" rx="3"/></g>
<text x="438" y="186" text-anchor="middle" class="t-s">binlerce küçük çekirdek</text>
<g class="s-line"><rect x="560" y="50" width="34" height="34" rx="4"/><rect x="600" y="50" width="34" height="34" rx="4"/><rect x="640" y="50" width="34" height="34" rx="4"/><rect x="560" y="90" width="34" height="34" rx="4"/><rect x="600" y="90" width="34" height="34" rx="4"/><rect x="640" y="90" width="34" height="34" rx="4"/><rect x="560" y="130" width="34" height="34" rx="4"/><rect x="600" y="130" width="34" height="34" rx="4"/><rect x="640" y="130" width="34" height="34" rx="4"/></g>
<g class="s-acc" data-a="show .12"><rect x="560" y="50" width="34" height="34" rx="4"/><rect x="600" y="50" width="34" height="34" rx="4"/><rect x="640" y="50" width="34" height="34" rx="4"/><rect x="560" y="90" width="34" height="34" rx="4"/><rect x="600" y="90" width="34" height="34" rx="4"/><rect x="640" y="90" width="34" height="34" rx="4"/><rect x="560" y="130" width="34" height="34" rx="4"/><rect x="600" y="130" width="34" height="34" rx="4"/><rect x="640" y="130" width="34" height="34" rx="4"/></g>
<text x="617" y="186" text-anchor="middle" class="t-s">hepsi aynı anda boyanır</text>
<text x="360" y="240" text-anchor="middle" class="t-s">Bağımsız ve birbirinin aynısı çok sayıda küçük iş varsa GPU; karmaşık ve sıralı işler varsa CPU daha uygundur.</text>
</svg>
:::

### Harici ve tümleşik grafik

**Tümleşik grafik** (iGPU), işlemcinin içine yerleştirilmiş grafik birimidir; sistem belleğini paylaşır. Ofis işleri, video izleme ve hafif oyunlar için yeterlidir. **Harici ekran kartı** (dGPU) ise kendi belleği, kendi soğutması ve çok daha yüksek gücü olan ayrı bir karttır.

### VRAM

**VRAM**, ekran kartının kendi belleğidir. Oyunlarda dokular, geometri ve kare tamponları; yapay zekâda ise modelin ağırlıkları burada tutulur. VRAM yetmezse oyunlarda takılma ve doku kalitesinde düşme görülür; yapay zekâ modeli ise hiç yüklenmeyebilir. Daha fazla VRAM kartı hızlandırmaz, ama yetersiz VRAM onu kullanılamaz hâle getirebilir. Yüksek çözünürlük, yüksek doku kalitesi ve ışın izleme VRAM gereksinimini artırır.

### Rasterleştirme ve ışın izleme

Oyunlar yıllarca yalnızca **rasterleştirme** ile çizildi: üç boyutlu sahnedeki üçgenler ekrana izdüşürülür ve pikseller boyanır. Çok hızlıdır, ama gölge, yansıma ve dolaylı ışık gibi etkiler çeşitli hilelerle taklit edilir.

**Işın izleme** (ray tracing) ise ışığın davranışını benzetir: kameradan her piksel için bir ışın gönderilir, ışının sahnede neye çarptığı, nereye yansıdığı ve ışık kaynağına ulaşıp ulaşmadığı hesaplanır. Sonuç çok daha gerçekçi yansımalar ve gölgelerdir; bedeli ise çok daha ağır bir hesap yüküdür. NVIDIA'nın **RTX** adı buradan gelir: bu kartlar ışın izlemeyi hızlandıran özel devreler içerir.

:::svg Işın izleme: kameradan çıkan ışın nesneye çarpar, yansır ve ışık kaynağına ulaşıp ulaşmadığına göre pikselin rengi hesaplanır.
<svg viewBox="0 0 720 270" role="img" aria-label="Solda kamera ve önünde piksel ızgarası. Kameradan çıkan bir ışın bir küreye çarpar, oradan zemine yansır ve ışık kaynağına doğru gider; başka bir ışın bir engelin arkasında kalarak gölge oluşturur" data-dur="8">
<path d="M20 240h680" class="l-mut w2" fill="none"/>
<path d="M40 130l44-22v44z" class="s-fg"/><rect x="20" y="116" width="26" height="28" rx="4" class="s-fg"/><text x="48" y="176" text-anchor="middle" class="t-s t-b">kamera</text>
<g class="s-none l-fg w1"><rect x="130" y="70" width="20" height="20"/><rect x="130" y="90" width="20" height="20"/><rect x="130" y="110" width="20" height="20"/><rect x="130" y="130" width="20" height="20"/><rect x="130" y="150" width="20" height="20"/><rect x="130" y="170" width="20" height="20"/></g>
<text x="140" y="212" text-anchor="middle" class="t-s">pikseller</text>
<circle cx="400" cy="150" r="56" class="s-soft l-acc w3"/><text x="400" y="155" text-anchor="middle" class="t-s t-b">nesne</text>
<circle cx="610" cy="50" r="22" class="s-acc2"/><circle cx="610" cy="50" r="34" class="s-none l-acc2 w2" data-a="wave 2.4"/><text x="610" y="100" text-anchor="middle" class="t-s t-b">ışık kaynağı</text>
<rect x="490" y="150" width="20" height="90" rx="3" class="s-mut"/><text x="556" y="200" class="t-s">engel</text>
<path d="M84 130 348 128" class="l-blue w2 dash" fill="none" data-a="show .04"/><circle cx="84" cy="130" r="7" class="s-blue" data-a="move 264 -2 .04 .28"/>
<path d="M348 128 596 62" class="l-acc2 w2 dash" fill="none" data-a="show .3"/><circle cx="348" cy="128" r="7" class="s-acc2" data-a="move 248 -66 .3 .52"/>
<rect x="130" y="110" width="20" height="20" class="s-acc2" data-a="show .52"/><text x="170" y="60" class="t-s t-b" data-a="show .52">ışığa ulaştı → aydınlık piksel</text>
<path d="M84 150 440 232" class="l-blue w2 dash" fill="none" data-a="show .58"/><circle cx="84" cy="150" r="7" class="s-blue" data-a="move 356 82 .58 .76"/>
<path d="M440 232 500 190" class="l-red w2 dash" fill="none" data-a="show .78"/><circle cx="440" cy="232" r="7" class="s-red" data-a="move 54 -38 .78 .88"/>
<rect x="130" y="170" width="20" height="20" class="s-fg" data-a="show .9"/><text x="170" y="262" class="t-s t-b" data-a="show .9">engele takıldı → gölgedeki piksel</text>
</svg>
:::

### Akıllı büyütme ve kare üretimi

Işın izlemenin yükünü hafifletmek için kartlar oyunu düşük çözünürlükte üretip yapay zekâyla büyütür: NVIDIA **DLSS**, AMD **FSR**, Intel **XeSS**. Yeni sürümler buna **kare üretimini** ekler: gerçekten çizilen iki karenin arasına yapay zekâyla üretilmiş ara kareler yerleştirilir. Bu, görüntünün akıcılığını artırır; ama üretilen kareler oyuncunun girdisini yansıtmadığı için tepki süresini iyileştirmez. Bu nedenle üreticilerin “kare üretimi açık” koşullarda verdiği kare hızı değerlerini, kartın ham gücüyle karıştırmamak gerekir.

:::svg Akıllı büyütme: oyun düşük çözünürlükte çizilir, yapay zekâ modeli görüntüyü hedef çözünürlüğe büyütür. Daha az piksel hesaplandığı için kare hızı artar.
<svg viewBox="0 0 720 220" role="img" aria-label="Solda iri kareli küçük bir görüntü, 1440p olarak işaretli. Ortada büyütme modeli. Sağda ince kareli büyük bir görüntü, 4K olarak işaretli" data-dur="8">
<rect x="30" y="60" width="150" height="100" rx="6" class="s-bg3 l-fg w2"/>
<g class="s-blue"><rect x="48" y="78" width="26" height="26"/><rect x="100" y="78" width="26" height="26"/><rect x="74" y="104" width="26" height="26"/><rect x="126" y="104" width="26" height="26"/><rect x="48" y="130" width="26" height="14"/><rect x="100" y="130" width="26" height="14"/></g>
<text x="105" y="184" text-anchor="middle" class="t-b">çizilen: 1440p</text><text x="105" y="202" text-anchor="middle" class="t-s">3,7 milyon piksel</text>
<path d="M180 110h80M440 110h60" class="l-mut w2" fill="none"/>
<rect x="260" y="70" width="180" height="80" rx="12" class="s-soft l-acc w2"/><text x="350" y="104" text-anchor="middle" class="t-b">Büyütme modeli</text><text x="350" y="126" text-anchor="middle" class="t-s">DLSS · FSR · XeSS</text>
<rect x="186" y="98" width="24" height="24" rx="4" class="s-blue" data-a="move 50 0 .05 .3"/><rect x="446" y="98" width="24" height="24" rx="4" class="s-acc" data-a="move 30 0 .4 .6"/>
<rect x="500" y="30" width="200" height="134" rx="6" class="s-bg3 l-fg w2"/>
<g class="s-acc" data-a="show .6"><rect x="516" y="46" width="14" height="14"/><rect x="544" y="46" width="14" height="14"/><rect x="572" y="46" width="14" height="14"/><rect x="600" y="46" width="14" height="14"/><rect x="628" y="46" width="14" height="14"/><rect x="656" y="46" width="14" height="14"/><rect x="530" y="60" width="14" height="14"/><rect x="558" y="60" width="14" height="14"/><rect x="586" y="60" width="14" height="14"/><rect x="614" y="60" width="14" height="14"/><rect x="642" y="60" width="14" height="14"/><rect x="670" y="60" width="14" height="14"/><rect x="516" y="74" width="14" height="14"/><rect x="544" y="74" width="14" height="14"/><rect x="572" y="74" width="14" height="14"/><rect x="600" y="74" width="14" height="14"/><rect x="628" y="74" width="14" height="14"/><rect x="656" y="74" width="14" height="14"/><rect x="530" y="88" width="14" height="14"/><rect x="558" y="88" width="14" height="14"/><rect x="586" y="88" width="14" height="14"/><rect x="614" y="88" width="14" height="14"/><rect x="642" y="88" width="14" height="14"/><rect x="670" y="88" width="14" height="14"/><rect x="516" y="102" width="14" height="14"/><rect x="544" y="102" width="14" height="14"/><rect x="572" y="102" width="14" height="14"/><rect x="600" y="102" width="14" height="14"/><rect x="628" y="102" width="14" height="14"/><rect x="656" y="102" width="14" height="14"/><rect x="530" y="116" width="14" height="14"/><rect x="558" y="116" width="14" height="14"/><rect x="586" y="116" width="14" height="14"/><rect x="614" y="116" width="14" height="14"/><rect x="642" y="116" width="14" height="14"/><rect x="670" y="116" width="14" height="14"/><rect x="516" y="130" width="14" height="14"/><rect x="544" y="130" width="14" height="14"/><rect x="572" y="130" width="14" height="14"/><rect x="600" y="130" width="14" height="14"/><rect x="628" y="130" width="14" height="14"/><rect x="656" y="130" width="14" height="14"/></g>
<text x="600" y="186" text-anchor="middle" class="t-b">gösterilen: 4K</text><text x="600" y="204" text-anchor="middle" class="t-s">8,3 milyon piksel</text>
</svg>
:::

### Güç tüketimi ve bağlantı

Bir ekran kartının çektiği güç (TDP ya da TGP), hem güç kaynağı gereksinimini hem de çıkardığı ısıyı belirler. Giriş düzeyi kartlar 75 W ile yalnızca anakart yuvasından beslenirken, en üst düzey oyun kartları 450 W'ın üzerine çıkar ve ayrı güç bağlantıları ister. Kartın kasaya sığıp sığmayacağına, güç kaynağınızın yeterli olup olmadığına ve doğru güç kablosuna sahip olup olmadığınıza satın almadan önce bakmak gerekir.

## NVIDIA GeForce: oyuncu kartları

### Ad nasıl okunur?

Örnek: **GeForce RTX 5070 Ti**

- **RTX:** ürün ailesi. Işın izleme ve DLSS desteği olan kartlar.
- **50:** nesil (50 serisi).
- **70:** nesil içindeki kademe. 50 giriş, 60 orta, 70 üst-orta, 80 üst, 90 en üst düzeydir.
- **Ti:** aynı kademenin güçlendirilmiş sürümü. **SUPER** ise nesil ortasında yapılan yenilemeyi gösterir. Sıralama genellikle şöyledir: düz model &lt; SUPER &lt; Ti &lt; Ti SUPER.

İşlemcilerde olduğu gibi, kademe rakamı yalnızca aynı nesil içinde anlamlıdır: yeni neslin 60 kademesi, genellikle iki nesil öncesinin 80 kademesine yakın başarım gösterir.

### GT serisi

**GT**, NVIDIA'nın en alt kademesidir: GT 710, GT 730, GT 1030 gibi. Bu kartlar oyun için değil, tümleşik grafiği olmayan ya da ek ekran çıkışı gereken sistemlerde görüntü almak için tasarlanmıştır. Güncel tümleşik grafik birimleri çoğu GT kartından daha güçlüdür; bugün yeni bir GT kartı almanın nadiren anlamı vardır.

### GTX serisi

**GTX**, yaklaşık on yıl boyunca NVIDIA'nın oyun kartlarının adıydı. En bilinen kuşakları 2016'da çıkan **GTX 10 serisi** (Pascal mimarisi: GTX 1050, 1060, 1070, 1080 ve 1080 Ti) ve 2019'daki **GTX 16 serisidir** (GTX 1650, 1660, 1660 SUPER). 16 serisi, RTX 20 ile aynı Turing mimarisini kullanır ama ışın izleme devreleri ve DLSS içermez; RTX'e bütçesi yetmeyenler için sunulmuştur. GTX adı 16 serisiyle birlikte sona ermiştir. GTX 1060 ve 1650, yıllarca dünyanın en çok kullanılan ekran kartları arasında kalmıştır.

### RTX 20 serisi (2018)

Turing mimarisiyle gelen RTX 2060, 2070, 2080 ve 2080 Ti, gerçek zamanlı ışın izlemeyi ve DLSS'i tüketiciye sunan ilk kartlardır. Çıktıklarında ışın izlemeyi destekleyen oyun çok azdı ve DLSS'in ilk sürümü bulanık bulunuyordu; ama sonraki bütün nesillerin temelini bu seri attı.

### RTX 30 serisi (2020)

Ampere mimarisi büyük bir başarım sıçraması getirdi: RTX 3060 (12 GB), 3060 Ti, 3070, 3080 ve 24 GB belleğiyle 3090. Bu nesil, kripto para madenciliği talebi ve küresel çip kıtlığıyla aynı döneme denk geldi; kartlar uzun süre önerilen fiyatların çok üzerinde satıldı.

### RTX 40 serisi (2022)

Ada Lovelace mimarisiyle gelen seri (RTX 4060, 4070, 4080 ve 4090), verimliliği belirgin biçimde artırdı ve **DLSS 3** ile kare üretimini getirdi. 24 GB belleğe sahip RTX 4090, çıktığında bir önceki amiral gemisinden çok büyük farkla öndeydi ve yerel yapay zekâ kullanıcılarının da gözdesi oldu. 2024'te seri SUPER modelleriyle yenilendi (4070 SUPER, 4070 Ti SUPER, 4080 SUPER).

### RTX 50 serisi (2025)

Blackwell mimarisine dayanan RTX 50 serisi, **DLSS 4** ve iki gerçek kare arasına birden çok kare üretebilen çoklu kare üretimi özelliğiyle geldi.

| Model | VRAM | Konumu |
|---|---|---|
| RTX 5090 | 32 GB | En üst düzey; 4K ve yerel yapay zekâ |
| RTX 5080 | 16 GB | Üst düzey 4K |
| RTX 5070 Ti | 16 GB | Üst-orta; 1440p ve 4K |
| RTX 5070 | 12 GB | 1440p |
| RTX 5060 Ti | 8 GB ya da 16 GB | 1080p ve 1440p |
| RTX 5060 | 8 GB | 1080p |

## NVIDIA profesyonel kartlar: Quadro ve RTX PRO

**Quadro**, NVIDIA'nın iş istasyonu kartlarının uzun yıllar kullandığı addı. 2020'den sonra Quadro adı bırakıldı; kartlar önce **RTX A serisi** (RTX A4000, A5000, A6000), sonra **RTX Ada** kuşağı ve en son **RTX PRO** adıyla anılmaya başlandı.

Bu kartlar çoğu zaman oyuncu kartlarıyla aynı çipleri kullanır. Fark başka yerdedir:

- **Çok daha fazla bellek:** RTX A6000 ve RTX 6000 Ada 48 GB, RTX PRO 6000 Blackwell 96 GB belleğe sahiptir.
- **Sertifikalı sürücüler:** CAD, mühendislik benzetimi ve görsel efekt yazılımları için yazılım üreticileriyle birlikte doğrulanır.
- **Hata düzelten (ECC) bellek** ve çoklu kart kullanımına uygun, havayı arkadan dışarı atan soğutma tasarımı.
- **Uzun süreli tedarik ve kurumsal destek.**

Bunların karşılığı, aynı çipli oyuncu kartının birkaç katı fiyattır. Oyun için bir avantaj sağlamazlar.

## NVIDIA veri merkezi kartları

Yapay zekâ modellerinin eğitildiği ve çalıştırıldığı kartlar ayrı bir ailedir. Eskiden **Tesla** adıyla anılırlardı. Ekran çıkışları yoktur, kendi fanları çoğu zaman bulunmaz (sunucu kasasının hava akışıyla soğutulurlar) ve adlarındaki harf, mimari kuşağını adlandıran bilim insanından gelir.

| Kart | Mimari | Duyuru | Bellek | Konumu |
|---|---|---|---|---|
| T4 | Turing | 2018 | 16 GB | Düşük güçlü (70 W) çıkarım kartı; bulutta çok yaygın |
| V100 | Volta | 2017 | 16 ya da 32 GB | Tensor çekirdekleri içeren ilk kart; derin öğrenmenin ilk büyük dalgası |
| A100 | Ampere | 2020 | 40 ya da 80 GB | Büyük dil modellerinin ilk kuşağının eğitildiği kart |
| H100 | Hopper | 2022 | 80 GB | Üretken yapay zekâ patlamasının simgesi |
| H200 | Hopper | 2023 | 141 GB | H100'ün daha çok ve daha hızlı bellekli sürümü |
| B200 | Blackwell | 2024 | 180 GB | Sıvı soğutmalı raf sistemleriyle sunulan kuşak |

Bu kartları oyuncu kartlarından ayıran özellikler şunlardır:

- **Bellek miktarı ve hızı:** Büyük modellerin ağırlıkları onlarca, yüzlerce gigabayt yer tutar. H200 ve B200, çok yüksek bant genişlikli HBM bellek kullanır.
- **Çoklu kart ölçeklenmesi:** Tek bir sunucuda sekiz kart, NVLink adlı hızlı bağlantılarla tek bir büyük hızlandırıcı gibi çalışır; binlerce sunucu da özel ağlarla birbirine bağlanır.
- **Kesintisiz çalışma:** Aylarca tam yük altında çalışacak biçimde tasarlanır ve doğrulanır.
- **Yazılım:** NVIDIA'nın **CUDA** platformu ve üzerindeki kitaplıklar, yapay zekâ yazılımlarının büyük bölümünün varsayılan hedefidir. NVIDIA'nın bu alandaki konumunun en önemli dayanağı donanımdan çok bu yazılım ekosistemidir.

:::chart
{"type":"bar","title":"NVIDIA veri merkezi kartlarında bellek miktarının artışı","labels":["T4 (2018)","V100 (2017)","A100 (2020)","H100 (2022)","H200 (2023)","B200 (2024)"],"datasets":[{"label":"Kart başına bellek (GB)","data":[16,32,80,80,141,180]}],"unit":"GB","yTitle":"Bellek (GB)","source":"NVIDIA ürün belirtimleri (T4, V100, A100, H100, H200 veri sayfaları; DGX B200 belirtimi)","sourceUrl":"https://www.nvidia.com/en-us/data-center/","date":"2017–2024","note":"Her kartın en yüksek bellekli sürümü gösterilmiştir. B200 değeri, 8 kartta toplam 1.440 GB bildiren DGX B200 belirtiminden hesaplanmıştır."}
:::

Altı yılda kart başına bellek on kattan fazla arttı. Bunun nedeni basittir: yapay zekâ modelleri büyüdü ve bir modelin tek bir karta ya da tek bir sunucuya sığması, hem eğitimi hem de çalıştırmayı çok daha verimli hâle getiriyor.

## AMD Radeon

### Radeon RX: oyuncu kartları

AMD'nin oyuncu kartları **Radeon RX** adını taşır. Adlandırma NVIDIA'ya benzer: **RX 9070 XT** adında 9 nesli, 070 kademeyi, **XT** ise güçlendirilmiş sürümü gösterir. Bir önceki nesilde en üst modeller **XTX** ekiyle anılıyordu.

| Seri | Yıl | Mimari | Öne çıkan |
|---|---|---|---|
| RX 400 / 500 | 2016–2017 | Polaris | Uygun fiyatlı 1080p; RX 580 çok yaygınlaştı |
| RX 5000 | 2019 | RDNA | Yeni mimari, 7 nm üretim |
| RX 6000 | 2020 | RDNA 2 | AMD'de ilk ışın izleme desteği |
| RX 7000 | 2022 | RDNA 3 | 24 GB bellekli RX 7900 XTX |
| RX 9000 | 2025 | RDNA 4 | Işın izlemede büyük iyileşme; RX 9070 XT, 9070, 9060 XT |

AMD kartları genellikle aynı fiyata daha fazla VRAM sunar ve rasterleştirme başarımında güçlüdür. Işın izlemede ve büyütme teknolojisinde uzun süre NVIDIA'nın gerisinde kalmış, RX 9000 serisi ve FSR'nin yeni sürümleriyle arayı önemli ölçüde kapatmıştır. FSR'nin bir üstünlüğü, eski sürümlerinin rakip kartlarda da çalışmasıdır. AMD ayrıca PlayStation ve Xbox konsollarının grafik çiplerini üretir.

### Radeon PRO ve Instinct

**Radeon PRO**, AMD'nin iş istasyonu serisidir. **Instinct** ise veri merkezi hızlandırıcılarıdır: 192 GB bellekli MI300X (2023) ve 256 GB bellekli MI325X, bellek miktarında rakiplerinin üzerine çıkarak büyük modellerin çalıştırılmasında bir seçenek sunar. AMD'nin bu alandaki asıl zorluğu donanım değil, CUDA'ya karşı kendi yazılım platformu ROCm'un olgunluğudur.

## Intel Arc

Intel, harici ekran kartı pazarına 2022'de **Arc** markasıyla girdi. Kuşaklar alfabetik sırayla adlandırılır:

- **A serisi (Alchemist, 2022):** A380, A750 ve 16 GB bellekli A770. İlk sürücüler sorunluydu; özellikle eski oyunlarda başarım düşüktü. Güncellemelerle büyük ölçüde düzeldi.
- **B serisi (Battlemage, Aralık 2024):** 12 GB bellekli B580 ve 10 GB bellekli B570. Uygun fiyatına göre güçlü başarımı ve bol belleğiyle olumlu karşılandı.

Arc kartları ışın izlemeyi, kendi büyütme teknolojisi **XeSS**'i ve güçlü video kodlama (AV1) yeteneklerini destekler. Intel henüz üst düzey bir kart sunmamaktadır; rekabeti bütçe ve orta sınıfta yürütür.

## Kıyaslama sonuçları

Aşağıdaki grafikler PassMark'ın **G3D Mark** puanlarını gösterir. Bu puan, çeşitli DirectX sürümlerindeki üç boyutlu testlerin birleşik sonucudur.

:::chart
{"type":"bar","horizontal":true,"title":"Güncel ekran kartları: PassMark G3D Mark","labels":["RTX 5090","RTX 5080","RTX 5070 Ti","RX 7900 XTX","RTX 5070","RX 9070 XT","RX 9070","RTX 5060 Ti 16GB","RTX 5060","RX 9060 XT 16GB","RX 7600","Arc B580","Arc B570"],"datasets":[{"label":"G3D Mark","data":[39023,35651,32336,31477,28681,26922,25369,22630,20582,20141,16425,16101,14191]}],"xTitle":"G3D Mark (yüksek olan iyi)","source":"PassMark Software – videocardbenchmark.net","sourceUrl":"https://www.videocardbenchmark.net/high_end_gpus.html","date":"2026-11-12","note":"Kullanıcıların gönderdiği sonuçların ortalamasıdır; zamanla küçük oranlarda değişir (yaklaşık)."}
:::

:::warn G3D Mark, üst düzey kartlar arasındaki farkı küçültür
PassMark testleri görece düşük çözünürlüklerde çalışır ve en üst düzey kartlarda işlemci darboğazına takılır. Bu yüzden RTX 5090 ile RTX 5080 arasındaki fark grafikte olduğundan küçük görünür; 4K çözünürlükte ve ışın izlemeli oyunlarda aradaki fark belirgin biçimde daha büyüktür. Puan ayrıca ışın izleme, büyütme kalitesi ve VRAM miktarını yansıtmaz. Bir kart seçerken, oynayacağınız çözünürlükteki oyun testlerine de bakın.
:::

:::chart
{"type":"line","title":"NVIDIA'da nesilden nesile ilerleme: PassMark G3D Mark","labels":["GTX 10 (2016)","RTX 20 (2018)","RTX 30 (2020)","RTX 40 (2022)","RTX 50 (2025)"],"datasets":[{"label":"60 kademesi (1060, 2060, 3060, 4060, 5060)","data":[10011,14069,16871,19475,20582]},{"label":"En üst model (1080 Ti, 2080 Ti, 3090, 4090, 5090)","data":[18599,21398,26458,38016,39023]}],"yTitle":"G3D Mark","source":"PassMark Software – videocardbenchmark.net","sourceUrl":"https://www.videocardbenchmark.net/high_end_gpus.html","date":"2026-11-12","note":"Değerler yaklaşıktır. En üst modellerde puan işlemci darboğazından etkilenir."}
:::

Çizgi grafik iki eğilimi gösterir. Birincisi, 60 kademesi her nesilde düzenli ama ölçülü biçimde ilerlemiştir: 2016'nın GTX 1060'ından 2025'in RTX 5060'ına puan yaklaşık iki katına çıkmıştır. İkincisi, yeni neslin orta kademesi eski neslin amiral gemisine yetişir: RTX 5060'ın puanı, 2018'in en üst modeli RTX 2080 Ti'ye çok yakındır.

## Büyük karşılaştırma tablosu

| Aile | Üretici | Hedef | Örnek modeller | Ayırt edici özellik |
|---|---|---|---|---|
| GeForce GT | NVIDIA | Görüntü çıkışı | GT 710, GT 1030 | Çok düşük güç; oyun için uygun değil |
| GeForce GTX | NVIDIA | Oyun (eski) | GTX 1060, GTX 1660 SUPER | Işın izleme ve DLSS yok |
| GeForce RTX | NVIDIA | Oyun, içerik üretimi | RTX 5060 – RTX 5090 | Işın izleme, DLSS, CUDA |
| RTX PRO (eski Quadro) | NVIDIA | İş istasyonu | RTX A6000, RTX PRO 6000 | Çok bellek, sertifikalı sürücü |
| Veri merkezi | NVIDIA | Yapay zekâ, HPC | T4, A100, H100, H200, B200 | Çok yüksek bellek, çoklu kart ölçeklenmesi |
| Radeon RX | AMD | Oyun | RX 9060 XT – RX 9070 XT | Fiyatına göre bol VRAM, FSR |
| Radeon PRO | AMD | İş istasyonu | Radeon PRO W7900 | Sertifikalı sürücü |
| Instinct | AMD | Yapay zekâ, HPC | MI300X, MI325X | Kart başına çok yüksek bellek |
| Arc | Intel | Oyun (bütçe ve orta) | Arc B570, B580 | Uygun fiyat, AV1 kodlama, XeSS |

## Hangisini seçmeliyim?

| Kullanım | Makul seçim | Neden |
|---|---|---|
| 1080p oyun | RTX 5060, RX 9060 XT ya da Arc B580 | Bu çözünürlükte yüksek ayarlar için yeterli |
| 1440p oyun | RTX 5070, RX 9070 ya da RX 9070 XT | En az 12 GB bellek önerilir |
| 4K oyun | RTX 5080 ya da RTX 5090 | 4K ve ışın izleme çok güç ister |
| Video kurgu ve yayın | NVIDIA RTX ya da Intel Arc | Güçlü donanım kodlayıcıları |
| Yerel yapay zekâ modelleri | VRAM'i en yüksek NVIDIA kartı (16–32 GB) | Yazılımların çoğu CUDA'yı hedefler; belirleyici olan bellektir |
| CAD, mühendislik, görsel efekt | RTX PRO ya da Radeon PRO | Sertifikalı sürücü ve bol bellek |
| Model eğitimi, büyük ölçekli çıkarım | Veri merkezi kartı; çoğu zaman buluttan kiralanır | Satın almak yerine saatlik kiralamak yaygındır |
| Ofis ve video izleme | Tümleşik grafik | Harici kart gerekmez |

Seçerken şu sırayı izleyebilirsiniz:

1. **Çözünürlüğünüzü ve hedef kare hızınızı belirleyin.** Kartı monitöre göre seçin, monitörü karta göre değil.
2. **VRAM'e bakın.** 1080p için 8 GB hâlâ kullanılabilir, ama yeni oyunlarda sınırdadır; 1440p ve üzeri için 12–16 GB daha güvenlidir.
3. **Kullandığınız yazılımı düşünün.** Bazı yaratıcı ve yapay zekâ yazılımları yalnızca ya da en iyi NVIDIA ile çalışır.
4. **Güç kaynağınızı ve kasanızı ölçün.** Üst düzey kartlar hem uzun hem de güce açtır.
5. **Fiyat başına başarıma bakın.** En üst modeller, bir alt kademeye göre az bir başarım artışı için çok daha fazla ücret ister.

## Sık sorulan sorular

:::faq
Q: GTX ile RTX arasındaki fark nedir?
A: RTX kartları ışın izlemeyi hızlandıran özel devrelere ve DLSS'i çalıştıran tensor çekirdeklerine sahiptir; GTX kartlarında bunlar yoktur. GTX adı 16 serisiyle sona ermiştir; NVIDIA'nın güncel oyun kartlarının tamamı RTX'tir.
Q: Ti ve SUPER ne anlama geliyor?
A: İkisi de aynı kademenin daha güçlü sürümünü gösterir. Ti genellikle düz model ile bir üst kademe arasında konumlanır; SUPER ise nesil ortasında yapılan bir yenilemedir.
Q: Daha fazla VRAM daha hızlı kart demek mi?
A: Hayır. VRAM bir kapasite ölçüsüdür, hız ölçüsü değil. Yeterli olduğu sürece fazlası kare hızını artırmaz; ama yetersiz kaldığında başarım ciddi biçimde düşer.
Q: H100 ile oyun oynanır mı?
A: Pratik olarak hayır. Veri merkezi kartlarının ekran çıkışı ve oyun sürücüleri yoktur, kendi fanları bulunmaz ve fiyatları bir oyuncu kartının onlarca katıdır. Farklı bir iş için tasarlanmışlardır.
Q: Neden yapay zekâda neredeyse herkes NVIDIA kullanıyor?
A: Başlıca neden yazılımdır. NVIDIA'nın CUDA platformu on beş yılı aşkın süredir geliştiriliyor ve yapay zekâ çatılarının çoğu önce onu hedefliyor. Rakip donanımlar güçlü olsa da yazılım ekosistemi geçişi zorlaştırıyor.
Q: Eski kartımı yükseltmeli miyim?
A: Oynadığınız oyunlarda hedeflediğiniz çözünürlük ve kare hızına ulaşıyorsanız gerek yoktur. Genel bir kural olarak, iki nesil ve aynı ya da üst kademe bir geçiş hissedilir fark yaratır.
Q: İşlemcim ekran kartımı darboğaza sokar mı?
A: Olabilir. Çok güçlü bir kartı zayıf bir işlemciyle eşlerseniz, özellikle düşük çözünürlükte işlemci kare hızını sınırlar. Çözünürlük arttıkça yük ekran kartına kayar ve darboğaz azalır.
:::

## Özet

- GPU, aynı basit işlemi binlerce çekirdekle paralel yapar; bu yüzden hem grafik hem yapay zekâ için uygundur.
- NVIDIA adlarında ilk rakamlar nesli, sonraki ikisi kademeyi gösterir; Ti ve SUPER güçlendirilmiş sürümlerdir.
- GT görüntü çıkışı, GTX eski oyun kartları, RTX ise ışın izleme ve DLSS destekli güncel ailedir.
- Quadro'nun devamı olan RTX PRO iş istasyonlarına, T4'ten B200'e uzanan veri merkezi kartları ise yapay zekâya yöneliktir; bu ailede belirleyici özellik bellektir.
- AMD Radeon RX fiyatına göre bol VRAM sunar; Intel Arc bütçe sınıfında rekabet eder.
- Kart seçimini çözünürlük, VRAM gereksinimi, kullanılan yazılım ve güç kaynağı belirler.

## Kaynaklar

- [PassMark Software – Video Card Benchmarks: High End Video Cards](https://www.videocardbenchmark.net/high_end_gpus.html)
- [NVIDIA – GeForce RTX 50 Series](https://www.nvidia.com/en-us/geforce/graphics-cards/50-series/)
- [NVIDIA – GeForce ekran kartlarının karşılaştırması](https://www.nvidia.com/en-us/geforce/graphics-cards/compare/)
- [NVIDIA – RTX PRO iş istasyonu kartları](https://www.nvidia.com/en-us/products/workstations/professional-desktop-gpus/)
- [NVIDIA – Veri merkezi ürünleri (T4, A100, H100, H200)](https://www.nvidia.com/en-us/data-center/)
- [NVIDIA – DGX B200 belirtimleri](https://www.nvidia.com/en-us/data-center/dgx-b200/)
- [NVIDIA – DLSS teknolojisi](https://www.nvidia.com/en-us/geforce/technologies/dlss/)
- [AMD – Radeon RX ekran kartları](https://www.amd.com/en/products/graphics/desktops/radeon.html)
- [AMD – Instinct hızlandırıcılar](https://www.amd.com/en/products/accelerators/instinct.html)
- [Intel – Arc B serisi ekran kartları](https://www.intel.com/content/www/us/en/products/docs/discrete-gpus/arc/desktop/b-series/overview.html)
- [TechPowerUp – GPU Database (teknik belirtimler)](https://www.techpowerup.com/gpu-specs/)
