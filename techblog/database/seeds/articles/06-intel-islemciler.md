---
title: Every Intel CPU Explained: Pentium'dan Core Ultra'ya Intel İşlemci Rehberi
slug: every-intel-cpu-explained
category: donanim
tags: intel, işlemci, core i5, core ultra, xeon, benchmark
description: Pentium, Celeron, Core i3/i5/i7/i9, Core Ultra ve Xeon: nesil adlandırması, K, F, H, U, T son ekleri, P ve E çekirdekler ve kıyaslama grafikleri.
excerpt: “Core i7-14700K” adındaki her rakam ve harf ne anlama geliyor? Pentium, Celeron, Core i3/i5/i7/i9, Core Ultra ve Xeon ailelerini, son ekleri, P ve E çekirdekleri ve kıyaslama sonuçlarını tek yazıda açıklıyoruz.
cover: /assets/covers/intel-cpus.svg
cover_alt: Ortasında çekirdek ızgarası bulunan, kenarlarından bağlantı bacakları çıkan işlemci çizimi
series: Every X Explained
series_order: 6
date: 2026-10-25 09:00
---
Bir bilgisayar ilanında “Intel Core i5-14400F” ya da “Core Ultra 7 265K” yazdığını gördüğünüzde, o birkaç karakter size işlemcinin sınıfını, neslini, gücünü ve hatta ekran kartı gerekip gerekmediğini söyler. Yeter ki okumayı bilin. Intel'in adlandırması ilk bakışta karmaşık görünür, ama birkaç kuralı öğrendikten sonra her modeli yerine oturtabilirsiniz.

Bu yazıda önce işlemciyi anlamak için gereken kavramları, sonra Intel'in bütün ailelerini ve adlandırma düzenini ele alacağız. Kıyaslama grafikleri için PassMark'ın herkese açık veritabanını kullanıyoruz.

## Temel kavramlar

### Çekirdek ve iş parçacığı

**Çekirdek** (core), işlemcinin içinde komutları çalıştıran bağımsız bir işlem birimidir. Dört çekirdekli bir işlemci aynı anda dört ayrı işi gerçekten eşzamanlı yürütebilir.

**İş parçacığı** (thread), işletim sisteminin gördüğü mantıksal işlemci sayısıdır. Intel'in **Hyper-Threading** teknolojisi, tek bir fiziksel çekirdeğin iki iş parçacığını dönüşümlü olarak yürütmesini sağlar. Çekirdek bir iş parçacığında beklerken (örneğin bellekten veri gelmesini beklerken) diğerine geçer. Bu, çekirdeği ikiye katlamaz; uygun iş yüklerinde yaklaşık yüzde 20–30 ek verim sağlar. “6 çekirdek / 12 iş parçacığı” ifadesi bu anlama gelir.

:::svg Hyper-Threading: tek bir fiziksel çekirdek iki iş parçacığını dönüşümlü yürütür. Bir iş beklerken diğeri çalışır.
<svg viewBox="0 0 720 220" role="img" aria-label="İki iş kuyruğu tek bir fiziksel çekirdeğe bağlanır; çekirdek birinci ve ikinci iş parçacığının görevlerini sırayla işler" data-dur="8">
<text x="20" y="54" class="t-b">İş parçacığı 1</text><text x="20" y="164" class="t-b">İş parçacığı 2</text>
<path d="M140 50h260l60 55M140 160h260l60-55" class="l-line w3" fill="none"/>
<rect x="460" y="55" width="150" height="110" rx="14" class="s-soft l-acc w3"/><text x="535" y="104" text-anchor="middle" class="t-b">Fiziksel</text><text x="535" y="124" text-anchor="middle" class="t-b">çekirdek</text>
<path d="M610 110h80" class="l-line w3" fill="none"/><text x="690" y="92" text-anchor="end" class="t-s">sonuç</text>
<rect x="150" y="38" width="26" height="24" rx="5" class="s-blue" data-a="route 240,0 300,55 .02 .22"/>
<rect x="150" y="148" width="26" height="24" rx="5" class="s-acc2" data-a="route 240,0 300,-55 .26 .46"/>
<rect x="150" y="38" width="26" height="24" rx="5" class="s-blue" data-a="route 240,0 300,55 .5 .7"/>
<rect x="150" y="148" width="26" height="24" rx="5" class="s-acc2" data-a="route 240,0 300,-55 .74 .94"/>
<rect x="616" y="98" width="26" height="24" rx="5" class="s-blue" data-a="move 60 0 .22 .3"/><rect x="616" y="98" width="26" height="24" rx="5" class="s-acc2" data-a="move 60 0 .46 .54"/><rect x="616" y="98" width="26" height="24" rx="5" class="s-blue" data-a="move 60 0 .7 .78"/><rect x="616" y="98" width="26" height="24" rx="5" class="s-acc2" data-a="move 60 0 .94 1"/>
<text x="360" y="208" text-anchor="middle" class="t-s">işletim sistemi bu çekirdeği iki mantıksal işlemci olarak görür</text>
</svg>
:::

### Saat hızı, turbo ve IPC

**Saat hızı** (GHz), bir çekirdeğin saniyede kaç milyar çevrim yaptığını gösterir. Aynı nesil içinde daha yüksek GHz daha hızlı demektir; ama farklı nesilleri GHz ile kıyaslamak yanıltır. Çünkü asıl başarımı belirleyen ikinci etken **IPC**'dir (çevrim başına işlenen komut sayısı). Yeni bir mimari aynı GHz'te daha çok iş yapar; 2015'in 4,0 GHz'lik işlemcisi, bugünün 4,0 GHz'lik işlemcisinden belirgin biçimde yavaştır.

**Turbo Boost**, sıcaklık ve güç sınırları izin verdiğinde çekirdekleri kısa süreliğine taban hızın üzerine çıkarır. Kutuda yazan “5,8 GHz'e kadar” ifadesi bu en yüksek turbo hızıdır ve genellikle yalnızca bir iki çekirdek için geçerlidir.

### P çekirdekler ve E çekirdekler

2021'de 12. nesille birlikte Intel **hibrit mimariye** geçti: aynı işlemcide iki farklı çekirdek türü bulunur.

- **P çekirdekler** (Performance) büyük, hızlı ve güç tüketen çekirdeklerdir. Oyun gibi tek çekirdek hızına duyarlı işleri üstlenirler.
- **E çekirdekler** (Efficient) küçük ve tutumludur. Bir P çekirdeğin kapladığı alana yaklaşık dört E çekirdek sığar. Arka plan işlerini ve çok çekirdeğe yayılan yükleri (video dönüştürme, derleme) üstlenirler.

Hangi işin hangi çekirdeğe gideceğine, işlemcinin içindeki **Thread Director** donanımının verdiği bilgilerle işletim sistemi karar verir. Bu yüzden hibrit işlemcilerde çekirdek sayıları “8P + 16E” biçiminde yazılır. Bir ayrıntı: E çekirdeklerde Hyper-Threading yoktur; Core Ultra 200S serisinde ise P çekirdeklerden de kaldırılmıştır. Bu nedenle Core i9-14900K 24 çekirdek ve 32 iş parçacığına sahipken, aynı çekirdek düzenindeki Core Ultra 9 285K 24 çekirdek ve 24 iş parçacığına sahiptir.

:::svg Hibrit mimari: ağır işler büyük P çekirdeklere, arka plan işleri küçük E çekirdeklere yönlendirilir.
<svg viewBox="0 0 720 300" role="img" aria-label="Sekiz büyük P çekirdek ve on altı küçük E çekirdekten oluşan işlemci düzeni. Thread Director, oyun işini P çekirdeklere, arka plan güncellemesini E çekirdeklere yönlendirir" data-dur="9">
<rect x="20" y="30" width="150" height="50" rx="10" class="s-blue"/><text x="95" y="60" text-anchor="middle" class="t-b t-w">Oyun</text>
<rect x="20" y="210" width="150" height="50" rx="10" class="s-acc2"/><text x="95" y="240" text-anchor="middle" class="t-b t-w">Arka plan işleri</text>
<rect x="230" y="110" width="120" height="70" rx="10" class="s-soft l-acc w2"/><text x="290" y="140" text-anchor="middle" class="t-b">Thread</text><text x="290" y="160" text-anchor="middle" class="t-b">Director</text>
<path d="M170 55c40 0 40 70 60 78M170 235c40 0 40-70 60-78M350 130c40 0 40-66 70-66M350 160c40 0 40 70 70 70" class="l-line w2" fill="none"/>
<rect x="410" y="14" width="296" height="276" rx="14" class="s-bg2 l-fg w2"/>
<g class="s-bg3 l-blue w2"><rect x="424" y="28" width="60" height="50" rx="6"/><rect x="492" y="28" width="60" height="50" rx="6"/><rect x="560" y="28" width="60" height="50" rx="6"/><rect x="628" y="28" width="60" height="50" rx="6"/><rect x="424" y="86" width="60" height="50" rx="6"/><rect x="492" y="86" width="60" height="50" rx="6"/><rect x="560" y="86" width="60" height="50" rx="6"/><rect x="628" y="86" width="60" height="50" rx="6"/></g>
<g class="s-bg3 l-acc2 w2"><rect x="424" y="158" width="28" height="26" rx="4"/><rect x="458" y="158" width="28" height="26" rx="4"/><rect x="492" y="158" width="28" height="26" rx="4"/><rect x="526" y="158" width="28" height="26" rx="4"/><rect x="560" y="158" width="28" height="26" rx="4"/><rect x="594" y="158" width="28" height="26" rx="4"/><rect x="628" y="158" width="28" height="26" rx="4"/><rect x="662" y="158" width="28" height="26" rx="4"/><rect x="424" y="192" width="28" height="26" rx="4"/><rect x="458" y="192" width="28" height="26" rx="4"/><rect x="492" y="192" width="28" height="26" rx="4"/><rect x="526" y="192" width="28" height="26" rx="4"/><rect x="560" y="192" width="28" height="26" rx="4"/><rect x="594" y="192" width="28" height="26" rx="4"/><rect x="628" y="192" width="28" height="26" rx="4"/><rect x="662" y="192" width="28" height="26" rx="4"/></g>
<text x="558" y="150" text-anchor="middle" class="t-s">8 P çekirdek</text><text x="558" y="240" text-anchor="middle" class="t-s">16 E çekirdek</text>
<circle cx="176" cy="55" r="8" class="s-blue" data-a="route 54,78 130,68 250,10 .02 .32"/><circle cx="176" cy="55" r="8" class="s-blue" data-a="route 54,78 130,68 386,62 .2 .5"/>
<circle cx="176" cy="235" r="8" class="s-acc2" data-a="route 54,-78 130,-68 262,-62 .4 .7"/><circle cx="176" cy="235" r="8" class="s-acc2" data-a="route 54,-78 130,-68 430,-30 .6 .9"/>
<g class="s-blue"><rect x="424" y="28" width="60" height="50" rx="6" data-a="show .32 .98"/><rect x="560" y="86" width="60" height="50" rx="6" data-a="show .5 .98"/></g>
<g class="s-acc2"><rect x="424" y="158" width="28" height="26" rx="4" data-a="show .7 .98"/><rect x="594" y="192" width="28" height="26" rx="4" data-a="show .9 .98"/></g>
<text x="558" y="272" text-anchor="middle" class="t-s">örnek düzen: Core i9-14900K ve Core Ultra 9 285K</text>
</svg>
:::

### Üretim süreci, TDP ve soket

- **Üretim süreci** (ör. Intel 7, Intel 4, Intel 18A), transistörlerin ne kadar küçük ve verimli üretildiğini gösteren bir kuşak adıdır. Bugün bu adlar gerçek bir nanometre ölçüsüne karşılık gelmez; farklı üreticilerin süreç adları doğrudan kıyaslanamaz.
- **TDP** ya da Intel'in güncel terimiyle **temel işlemci gücü**, işlemcinin sürekli yük altında tüketeceği gücün watt cinsinden göstergesidir. Masaüstü K serisi işlemciler turbo sırasında bu değerin iki katına yakın güç çekebilir; soğutucu seçimi bu yüzden önemlidir.
- **Soket**, işlemcinin anakarta oturduğu yuvadır. İşlemci ile anakartın soketi aynı olmak zorundadır. 12., 13. ve 14. nesil masaüstü işlemciler **LGA 1700**, Core Ultra 200S serisi ise **LGA 1851** soketini kullanır; ikisi birbiriyle uyumlu değildir.
- **Tümleşik grafik** (iGPU), işlemcinin içindeki grafik birimidir. Ayrı bir ekran kartı takmadan görüntü almanızı sağlar.

## Bir Intel işlemci adı nasıl okunur?

Örnek olarak **Intel Core i7-14700K** adını ele alalım:

:::svg İşlemci adının bölümleri: marka, sınıf, nesil, model numarası ve son ek.
<svg viewBox="0 0 720 250" role="img" aria-label="Intel Core i7-14700K adının bölümleri: Core marka, i7 sınıf, 14 nesil, 700 model numarası, K son ek yani hız aşırtmaya açık" data-dur="10">
<text x="360" y="60" text-anchor="middle" class="t-m t-xl">Intel Core i7-14700K</text>
<g data-a="show .04"><path d="M266 76h92" class="l-blue w4 rc"/><path d="M312 76v48" class="l-blue w2"/><rect x="130" y="124" width="150" height="52" rx="8" class="s-blue"/><path d="M280 150h32" class="l-blue w2"/><text x="205" y="146" text-anchor="middle" class="t-b t-w">Marka</text><text x="205" y="166" text-anchor="middle" class="t-s t-w">Core ailesi</text></g>
<g data-a="show .22"><path d="M386 76h44" class="l-acc w4 rc"/><path d="M408 76v112" class="l-acc w2"/><rect x="250" y="188" width="170" height="52" rx="8" class="s-acc"/><text x="335" y="210" text-anchor="middle" class="t-b t-ink">Sınıf</text><text x="335" y="230" text-anchor="middle" class="t-s t-ink">i3 &lt; i5 &lt; i7 &lt; i9</text></g>
<g data-a="show .4"><path d="M458 76h44" class="l-acc2 w4 rc"/><path d="M480 76v48" class="l-acc2 w2"/><rect x="430" y="124" width="100" height="52" rx="8" class="s-acc2"/><text x="480" y="146" text-anchor="middle" class="t-b t-w">Nesil</text><text x="480" y="166" text-anchor="middle" class="t-s t-w">14. nesil</text></g>
<g data-a="show .58"><path d="M506 76h68" class="l-pur w4 rc"/><path d="M540 76v40h10v72" class="l-pur w2" fill="none"/><rect x="440" y="188" width="170" height="52" rx="8" class="s-pur"/><text x="525" y="210" text-anchor="middle" class="t-b t-w">Model numarası</text><text x="525" y="230" text-anchor="middle" class="t-s t-w">büyük olan güçlü</text></g>
<g data-a="show .76"><path d="M578 76h22" class="l-red w4 rc"/><path d="M589 76v48" class="l-red w2"/><rect x="540" y="124" width="170" height="52" rx="8" class="s-red"/><text x="625" y="146" text-anchor="middle" class="t-b t-w">Son ek</text><text x="625" y="166" text-anchor="middle" class="t-s t-w">K: hız aşırtmaya açık</text></g>
</svg>
:::

- **Core:** marka. Intel'in ana tüketici ailesi.
- **i7:** sınıf. Aynı nesil içinde i3 giriş, i5 orta, i7 üst, i9 en üst düzeydir.
- **14:** nesil. Tireden sonraki ilk bir ya da iki rakam nesli gösterir (8700 → 8. nesil, 12400 → 12. nesil).
- **700:** model numarası (SKU). Aynı sınıf içinde büyük sayı daha güçlü modeli gösterir.
- **K:** son ek. İşlemcinin özel bir özelliğini belirtir.

### Yeni düzen: Core Ultra

Intel, 2023'ün sonunda adlandırmayı değiştirdi: “i” harfi kalktı, üst modeller **Core Ultra**, alt modeller yalnızca **Core** adını aldı. Yeni düzende **Core Ultra 7 265K** şöyle okunur: Ultra 7 sınıf (eski i7'nin karşılığı), 2 seri numarası (Series 2), 65 model, K son ek. Sınıflar yine 3, 5, 7 ve 9'dur.

Bu yüzden iki farklı düzenden gelen adları karşılaştırırken dikkat etmek gerekir: “Core Ultra 5 245K”, adındaki 5'e rağmen birkaç yıl öncesinin i7 ve i9 modellerinden daha güçlü olabilir. Sınıf rakamı yalnızca **aynı nesil içinde** anlamlıdır.

### Son ekler

| Son ek | Anlamı | Nerede görülür |
|---|---|---|
| K | Çarpanı açık; hız aşırtma (overclock) yapılabilir | Masaüstü |
| F | Tümleşik grafik yok; ayrı ekran kartı gerekir | Masaüstü |
| KF | Çarpanı açık ve tümleşik grafik yok | Masaüstü |
| KS | Fabrikada en yüksek hıza ayarlanmış özel sürüm | Masaüstü |
| T | Düşük güç tüketimi için sınırlandırılmış (35 W sınıfı) | Masaüstü, küçük kasalar |
| S | Masaüstü serisi (Core Ultra 200S) | Masaüstü |
| X / XE | Uç düzey, çok çekirdekli HEDT sınıfı | Üst düzey masaüstü |
| H | Yüksek başarımlı dizüstü (45 W sınıfı) | Oyun ve iş istasyonu dizüstüleri |
| HX | En yüksek başarımlı dizüstü; masaüstü sınıfı çekirdek sayısı | Üst düzey dizüstü |
| HK | Yüksek başarımlı ve çarpanı açık | Dizüstü |
| P | Başarım ile incelik arası (28 W sınıfı) | 12. ve 13. nesil ince dizüstüler |
| U | Düşük güç (15 W sınıfı); uzun pil ömrü | İnce ve hafif dizüstüler |
| V | Belleği işlemci paketine yerleştirilmiş verimli seri (Core Ultra 200V) | İnce dizüstüler |
| Y | Çok düşük güç; fansız tasarımlar | Eski ultra ince cihazlar |
| G1–G7 | Tümleşik grafik düzeyi (büyük sayı güçlü) | 10. ve 11. nesil dizüstüler |
| E / TE | Gömülü sistemler için uzun süreli tedarik | Endüstriyel cihazlar |

:::warn Dizüstü ile masaüstü adları aynı gücü göstermez
Dizüstündeki bir Core i7-1355U ile masaüstündeki Core i7-13700K aynı “13. nesil i7” adını taşır, ama birincisi 15 W sınıfı, ikincisi 125 W sınıfı bir işlemcidir ve çok çekirdek başarımı birkaç kat farklıdır. Karşılaştırırken sınıftan önce son eke bakın.
:::

## Aileler tek tek

### Pentium

1993'te tanıtılan Pentium, Intel'in en tanınmış markasıdır ve on yılı aşkın süre şirketin amiral gemisi oldu. Pentium MMX, II, III ve 4 ile kişisel bilgisayarın yaygınlaşma dönemine damga vurdu. 2006'da Core markası gelince Pentium bütçe sınıfına indi: az çekirdekli, uygun fiyatlı, ofis ve web kullanımına yönelik modeller (ör. Pentium Gold G7400: 2 çekirdek, 4 iş parçacığı). Intel, 2023 itibarıyla dizüstülerde Pentium adını bıraktı.

### Celeron

1998'de çıkan Celeron, Pentium'un da altında konumlanan en ucuz Intel işlemcisiydi: daha küçük önbellek, daha az özellik. Yıllarca en uygun fiyatlı bilgisayarlarda ve Chromebook'larda kullanıldı. Pentium ile birlikte 2023'te emekliye ayrıldı.

### Intel Processor (N serisi)

Pentium ve Celeron'un dizüstü tarafındaki yerini, markasız bir adla **Intel Processor** aldı. N100 ve N150 gibi modeller yalnızca E çekirdeklerden oluşur ve yaklaşık 6 W güç tüketir. Hesap makinesi kadar sessiz mini bilgisayarlar, giriş düzeyi dizüstüler ve ev sunucuları için popülerdir.

### Core i3

Core ailesinin giriş düzeyidir. Güncel masaüstü modelleri (ör. Core i3-14100) 4 P çekirdek ve 8 iş parçacığı sunar; E çekirdek içermez. Ofis işleri, web, video izleme ve hafif oyunlar için yeterlidir.

### Core i5

En çok satan sınıftır ve çoğu kullanıcı için fiyat ile başarım arasındaki en iyi dengeyi sunar. Core i5-12400 yalnızca 6 P çekirdek içerirken, Core i5-14600K 6 P ve 8 E çekirdekle 14 çekirdeğe ulaşır. Oyun ve genel kullanım için fazlasıyla yeterlidir.

### Core i7

Üst sınıftır. Core i7-14700K, 8 P ve 12 E çekirdekle 20 çekirdek ve 28 iş parçacığı sunar. Oyunun yanında yayın yapanlar, video kurgulayanlar ve yazılım derleyenler için uygundur.

### Core i9

2017'de eklenen en üst tüketici sınıfıdır. Core i9-14900K, 8 P ve 16 E çekirdekle 24 çekirdek ve 32 iş parçacığına, 6,0 GHz'e varan turbo hızına sahiptir. Çok çekirdekli yüklerde çok güçlüdür; ama yüksek güç tüketir ve ciddi bir soğutma ister.

:::info 13. ve 14. nesil masaüstü işlemcilerde kararlılık sorunu
Intel, 2024'te 13. ve 14. nesil bazı masaüstü işlemcilerde yüksek voltaj nedeniyle kararsızlık ve kalıcı bozulma görülebildiğini doğruladı, mikrokod güncellemeleri yayımladı ve etkilenen modellerin garanti süresini uzattı. Bu nesilden bir işlemci kullanıyorsanız anakartınızın BIOS'unu güncel tutun.
:::

### Core Ultra

Core Ultra, yeni adlandırmanın üst ailesidir ve üç yeniliği birlikte getirdi: karo (tile) tabanlı tasarım, daha güçlü Arc tümleşik grafik ve yapay zekâ işlerini düşük güçle yürüten **NPU** (sinir işleme birimi).

- **Series 1** (Meteor Lake, Aralık 2023): yalnızca dizüstü; Core Ultra 5 125H, Ultra 7 155H gibi modeller.
- **Series 2:** dizüstünde verimliliğe odaklı **200V** (Lunar Lake, Eylül 2024) ve yüksek başarımlı **200H/HX**; masaüstünde **200S** (Arrow Lake, Ekim 2024). Masaüstü modelleri Core Ultra 5 245K, Ultra 7 265K ve Ultra 9 285K'dır. Bu seri 14. nesle göre belirgin biçimde daha az güç tüketir; oyun başarımı ise çıkışında önceki neslin biraz gerisinde ya da yakınında kalmıştır.
- PassMark veritabanında 2026'da **Core Ultra 200S Plus** adıyla yenilenmiş masaüstü modelleri (ör. Core Ultra 7 270K Plus) de yer almaktadır.

Ultra olmayan **Core 3, Core 5 ve Core 7** modelleri ise önceki nesil mimarilerin yeniden adlandırılmış, daha uygun fiyatlı sürümleridir.

### Xeon

Xeon, Intel'in sunucu ve iş istasyonu ailesidir. Tüketici işlemcilerinden farkı ham hız değil, ölçek ve güvenilirliktir:

- Çok daha fazla çekirdek (tek işlemcide onlarca, hatta yüzden fazla),
- Hata düzelten **ECC bellek** desteği,
- Çok daha fazla bellek kanalı ve terabaytlarca RAM kapasitesi,
- Aynı anakartta birden çok işlemci kullanabilme,
- Günün 24 saati yük altında çalışmaya göre doğrulama.

Aile içinde **Xeon Scalable** (Bronze, Silver, Gold, Platinum kademeleri) veri merkezi serisidir. 2024'te ad **Xeon 6** olarak sadeleşti ve iki kola ayrıldı: yüksek başarımlı P çekirdekli modeller ile çok yoğun E çekirdekli modeller. **Xeon W** (w3, w5, w7, w9) iş istasyonlarına yöneliktir; örneğin 60 çekirdekli Xeon w9-3595X. **Xeon E** ise küçük işletme sunucuları içindir.

Xeon'lar oyun için iyi bir seçim değildir: çekirdek başına hızları genellikle Core i9'dan düşüktür ve platformları çok pahalıdır.

## Nesiller

| Nesil | Kod adı | Yıl | Öne çıkan yenilik | Masaüstü soketi |
|---|---|---|---|---|
| 1. | Nehalem | 2008 | Core i7 adı, Hyper-Threading'in dönüşü | LGA 1366 / 1156 |
| 2. | Sandy Bridge | 2011 | Büyük IPC artışı, tümleşik grafik işlemcinin içinde | LGA 1155 |
| 3. | Ivy Bridge | 2012 | 22 nm, üç boyutlu transistör | LGA 1155 |
| 4. | Haswell | 2013 | AVX2, düşük güç tüketimi | LGA 1150 |
| 6. | Skylake | 2015 | DDR4, 14 nm | LGA 1151 |
| 8. | Coffee Lake | 2017 | i7'de 6, i5'te 6, i3'te 4 çekirdek | LGA 1151 (v2) |
| 9. | Coffee Lake Refresh | 2018 | 8 çekirdekli ilk ana akım i9 | LGA 1151 (v2) |
| 10. | Comet Lake | 2020 | 10 çekirdek | LGA 1200 |
| 11. | Rocket Lake | 2021 | Yeni çekirdek mimarisi, PCIe 4.0 | LGA 1200 |
| 12. | Alder Lake | 2021 | Hibrit mimari (P + E), DDR5, Intel 7 | LGA 1700 |
| 13. | Raptor Lake | 2022 | Daha çok E çekirdek, daha yüksek hız | LGA 1700 |
| 14. | Raptor Lake Refresh | 2023 | Küçük hız artışları | LGA 1700 |
| Core Ultra Series 2 | Arrow Lake | 2024 | NPU, karo tasarım, daha düşük güç | LGA 1851 |

## Kıyaslama sonuçları

Aşağıdaki grafikler PassMark'ın **CPU Mark** puanlarını gösterir. CPU Mark, bütün çekirdekleri kullanan bir dizi testin birleşik sonucudur; dolayısıyla çok çekirdek başarımını yansıtır. Oyun başarımı ağırlıklı olarak tek çekirdek hızına ve önbelleğe bağlı olduğu için bu sıralamayla birebir örtüşmez.

:::chart
{"type":"bar","horizontal":true,"title":"Güncel Intel masaüstü işlemcileri: PassMark CPU Mark","labels":["Pentium Gold G7400","Core i3-12100","Core i3-14100","Core i5-12400","Core i5-14400","Core i5-14600K","Core Ultra 5 245K","Core i7-14700K","Core i9-14900K","Core Ultra 7 265K","Core Ultra 9 285K","Xeon w9-3595X"],"datasets":[{"label":"CPU Mark","data":[6701,12538,15097,18836,24977,38342,42973,51894,58155,58570,67230,99182]}],"xTitle":"CPU Mark (yüksek olan iyi)","source":"PassMark Software – cpubenchmark.net","sourceUrl":"https://www.cpubenchmark.net/high_end_cpus.html","date":"2026-11-12","note":"Kullanıcıların gönderdiği sonuçların ortalamasıdır; değerler zamanla küçük oranlarda değişir (yaklaşık)."}
:::

Grafikten üç sonuç çıkar. Birincisi, sınıf atlamak her zaman büyük fark yaratır: i3'ten i5'e, i5'ten i7'ye geçişte puan belirgin biçimde artar, çünkü çekirdek sayısı artar. İkincisi, K'lı modeller aynı sınıfın K'sız modellerinden çok daha güçlüdür: i5-14600K, i5-14400'ün yaklaşık bir buçuk katı puan alır; çünkü daha çok E çekirdeğe ve daha yüksek hıza sahiptir. Üçüncüsü, 60 çekirdekli bir Xeon çok çekirdek testinde bütün tüketici işlemcilerini geride bırakır; ama fiyatı ve platform maliyeti de bambaşka bir düzeydedir.

:::chart
{"type":"line","title":"Nesilden nesile en üst ana akım masaüstü işlemci: PassMark CPU Mark","labels":["i7-4790K (2014)","i7-6700K (2015)","i7-7700K (2017)","i7-8700K (2017)","i9-9900K (2018)","i9-10900K (2020)","i9-12900K (2021)","i9-13900K (2022)","i9-14900K (2023)","Ultra 9 285K (2024)"],"datasets":[{"label":"CPU Mark","data":[8058,8933,9632,13553,18016,22209,41093,58028,58155,67230]}],"yTitle":"CPU Mark","source":"PassMark Software – cpubenchmark.net","sourceUrl":"https://www.cpubenchmark.net/high_end_cpus.html","date":"2026-11-12","note":"Her nesilden en üst ana akım (HEDT dışı) masaüstü modeli seçilmiştir; değerler yaklaşıktır."}
:::

Bu eğri Intel'in son on yılını özetler. 2014 ile 2017 arasında dört çekirdekle sınırlı kalınan dönemde ilerleme çok yavaştır. 8. nesilde çekirdek sayısı artmaya başlar; asıl sıçrama ise 12. nesilde hibrit mimariyle gelir: iki yılda puan neredeyse ikiye katlanır.

## Hangisini seçmeliyim?

| Kullanım | Makul sınıf | Örnek |
|---|---|---|
| Ofis, web, video izleme | Core i3 / Core 3 ya da N serisi | Core i3-14100, Intel N150 |
| Genel kullanım ve oyun | Core i5 / Core Ultra 5 | Core i5-14400F, Core Ultra 5 245K |
| Oyun ve yayın, video kurgu | Core i7 / Core Ultra 7 | Core i7-14700K, Core Ultra 7 265K |
| Yoğun çok çekirdekli iş (render, derleme) | Core i9 / Core Ultra 9 | Core Ultra 9 285K |
| Uzun pil ömürlü ince dizüstü | U ya da V son ekli Core Ultra | Core Ultra 7 258V |
| Oyun dizüstüsü | H ya da HX son ekli | Core Ultra 7 255H |
| İş istasyonu, sunucu, ECC bellek | Xeon W / Xeon 6 | Xeon w9-3595X |

Seçerken şu sırayı izleyebilirsiniz:

1. **Sınıfı belirleyin.** Çoğu kullanıcı için i5 / Ultra 5 yeterlidir; bütçenin kalanını ekran kartına ayırmak oyunlarda daha çok fark yaratır.
2. **Son eke bakın.** Ayrı ekran kartınız yoksa F'li model almayın. Hız aşırtma yapmayacaksanız K'ya ek ücret ödemeniz gerekmez (ama K'lı modeller çoğu zaman daha çok çekirdeğe de sahiptir).
3. **Platformu düşünün.** LGA 1700 anakartlar ucuzdur ve DDR4 desteği sunabilir, ama yeni işlemci gelmeyecektir. LGA 1851 daha yenidir ve yalnızca DDR5 kullanır.
4. **Soğutmayı hesaba katın.** i7 ve i9 K modelleri iyi bir kule soğutucu ya da sıvı soğutma ister.

## Sık sorulan sorular

:::faq
Q: Core i7 her zaman Core i5'ten hızlı mıdır?
A: Yalnızca aynı nesil ve aynı son ek içinde. 14. nesil bir Core i5, 9. nesil bir Core i7'den çok daha hızlıdır. Nesil farkı çoğu zaman sınıf farkından daha belirleyicidir.
Q: F'li işlemci alırsam ne olur?
A: F son ekli modellerde tümleşik grafik yoktur; görüntü almak için ayrı bir ekran kartı şarttır. Zaten ekran kartı kullanacaksanız F'li model genellikle biraz daha ucuzdur ve başarımı aynıdır.
Q: Core Ultra, Core i serisinin devamı mı?
A: Evet. Intel 2023'ün sonunda “i” harfini kaldırdı. Core Ultra 5, 7 ve 9 sırasıyla eski i5, i7 ve i9 sınıflarının konumundadır; ek olarak NPU ve daha güçlü tümleşik grafik içerir.
Q: E çekirdekler oyunlarda sorun çıkarır mı?
A: Windows 11 ve güncel oyunlarda genellikle hayır; zamanlayıcı oyunu P çekirdeklere yönlendirir. Hibrit mimarinin çıktığı ilk dönemde bazı eski oyunlarda ve kopya koruma yazılımlarında uyum sorunları yaşanmış, bunlar güncellemelerle büyük ölçüde giderilmiştir.
Q: Hız aşırtma (overclock) yapmaya değer mi?
A: Güncel üst düzey Intel işlemciler fabrika çıkışında sınıra yakın çalıştığı için elle hız aşırtmanın kazancı küçüktür. Çoğu kullanıcı için değmez; yalnızca K son ekli işlemci ve Z serisi anakartla mümkündür.
Q: Xeon ile Core i9 arasındaki fark nedir?
A: Core i9 çekirdek başına daha hızlıdır ve oyun ile gündelik kullanım için uygundur. Xeon ise çok daha fazla çekirdek, ECC bellek, çok daha fazla RAM ve kesintisiz çalışma güvenilirliği sunar; sunucu ve iş istasyonu içindir.
Q: PassMark puanı oyun başarımını gösterir mi?
A: Dolaylı olarak. CPU Mark çok çekirdek ağırlıklı bir puandır; oyunlar ise çoğunlukla birkaç hızlı çekirdekten yararlanır. Oyun için tek iş parçacığı puanına ve oyun testlerine bakmak daha doğrudur.
:::

## Özet

- İşlemci adında sınıf (i3/i5/i7/i9 ya da Ultra 5/7/9), nesil, model numarası ve son ek bulunur; sınıf yalnızca aynı nesil içinde kıyaslanabilir.
- Son ekler kritik bilgi taşır: K hız aşırtma, F tümleşik grafik yok, H ve HX güçlü dizüstü, U ve V verimli dizüstü, T düşük güç demektir.
- 12. nesilden itibaren Intel işlemciler hızlı P çekirdekler ile tutumlu E çekirdekleri birleştirir.
- Pentium ve Celeron 2023'te emekli edildi; yerlerini N serisi Intel Processor aldı.
- Core Ultra, NPU ve daha güçlü tümleşik grafikle gelen yeni üst ailedir; Xeon ise sunucu ve iş istasyonu içindir.
- Çoğu kullanıcı için i5 / Ultra 5 sınıfı yeterlidir.

## Kaynaklar

- [PassMark Software – CPU Benchmarks: High End CPUs](https://www.cpubenchmark.net/high_end_cpus.html)
- [PassMark Software – CPU Benchmarks: Mid Range CPUs](https://www.cpubenchmark.net/mid_range_cpus.html)
- [Intel – Processor Numbers and Naming (işlemci adları ve son ekler)](https://www.intel.com/content/www/us/en/processors/processor-numbers.html)
- [Intel ARK – ürün belirtimleri veritabanı](https://www.intel.com/content/www/us/en/ark.html)
- [Intel – What Is Hyper-Threading?](https://www.intel.com/content/www/us/en/gaming/resources/hyper-threading.html)
- [Intel – How Intel Core Processors' Performance Hybrid Architecture Works](https://www.intel.com/content/www/us/en/gaming/resources/how-hybrid-design-works.html)
- [Intel Newsroom – Core Ultra 200S serisi duyurusu (Ekim 2024)](https://newsroom.intel.com/client-computing/core-ultra-200s-series-desktop-processors)
- [Intel Community – 13. ve 14. nesil masaüstü işlemcilerde kararsızlık sorunu hakkında açıklama](https://community.intel.com/t5/Blogs/Tech-Innovation/Client/Intel-Core-13th-and-14th-Gen-Desktop-Instability-Root-Cause/post/1633239)
- [Intel – Xeon 6 işlemciler](https://www.intel.com/content/www/us/en/products/details/processors/xeon/xeon6-product-brief.html)
