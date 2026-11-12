---
title: Every Data Structure Explained: Veri Yapılarının Tamamı ve Big-O Rehberi
slug: every-data-structure-explained
category: yazilim
tags: veri yapıları, algoritma, big-o, ağaç, hash tablosu, graf
description: Dizi, bağlı liste, yığın, kuyruk, hash tablosu, ağaç, BST, AVL, heap, trie ve graf: her yapının nasıl çalıştığı, Big-O tablosu ve işlem animasyonlarıyla.
excerpt: Dizi, bağlı liste, yığın, kuyruk, deque, hash tablosu, ikili arama ağacı, AVL, heap, trie ve graf. Her veri yapısının nasıl çalıştığını, hangi işlemin ne kadar sürdüğünü ve ne zaman hangisini seçeceğinizi animasyonlarla anlatıyoruz.
cover: /assets/covers/data-structures.svg
cover_alt: Birbirine oklarla bağlı düğümlerden oluşan ağaç ve bağlı liste çizimi
series: Every X Explained
series_order: 3
date: 2026-10-22 09:00
---
Bir programın hızlı ya da yavaş olmasını çoğu zaman kullanılan dil değil, verinin bellekte nasıl düzenlendiği belirler. Aynı bir milyon kaydı bir yapıda aramak bir milyon adım, başka bir yapıda yirmi adım sürer. **Veri yapıları**, veriyi belirli işlemleri hızlı yapacak biçimde düzenleme yöntemleridir ve her birinin güçlü olduğu işlemler ile bedelini ödediği işlemler vardır.

Bu yazıda en temel veri yapılarını sırayla ele alacağız. Her biri için üç soruyu yanıtlayacağız: bellekte nasıl durur, hangi işlem ne kadar sürer ve gerçek hayatta nerede kullanılır?

## Temel kavramlar

### Big-O gösterimi

Bir işlemin “hızlı” olduğunu söylemek yetmez; veri büyüdükçe sürenin nasıl arttığını bilmek gerekir. **Big-O gösterimi** tam olarak bunu anlatır: eleman sayısı *n* büyürken adım sayısının hangi hızla büyüdüğünü.

| Gösterim | Adı | n = 1.000.000 için yaklaşık adım | Örnek |
|---|---|---|---|
| O(1) | Sabit | 1 | Dizide indeksle erişim |
| O(log n) | Logaritmik | 20 | Dengeli ağaçta arama |
| O(n) | Doğrusal | 1.000.000 | Listede baştan sona arama |
| O(n log n) | Doğrusal-logaritmik | 20.000.000 | Verimli sıralama |
| O(n²) | Karesel | 1.000.000.000.000 | İç içe iki döngü |

Big-O sabit çarpanları ve küçük terimleri yok sayar; çünkü amaç iki yöntemi saniye saniye kıyaslamak değil, veri büyüdüğünde hangisinin ayakta kalacağını görmektir.

:::chart
{"type":"line","log":true,"title":"Eleman sayısı arttıkça adım sayısının büyümesi (logaritmik ölçek)","labels":["2","4","8","16","32","64","128"],"datasets":[{"label":"O(1)","data":[1,1,1,1,1,1,1]},{"label":"O(log n)","data":[1,2,3,4,5,6,7]},{"label":"O(n)","data":[2,4,8,16,32,64,128]},{"label":"O(n log n)","data":[2,8,24,64,160,384,896]},{"label":"O(n²)","data":[4,16,64,256,1024,4096,16384]}],"xTitle":"Eleman sayısı (n)","yTitle":"Adım sayısı","source":"Matematiksel hesaplama (log tabanı 2)","date":"2026-10","note":"Dikey eksen logaritmiktir; doğrusal ölçekte O(n²) eğrisi diğerlerini görünmez kılar."}
:::

### Bellek düzeni: bitişik ya da dağınık

Veri yapıları bellekte iki temel biçimde durur. **Bitişik** yapılarda (dizi) elemanlar bellekte yan yanadır; bu sayede herhangi birinin adresi doğrudan hesaplanır ve işlemcinin önbelleği çok verimli kullanılır. **Bağlantılı** yapılarda (bağlı liste, ağaç) her eleman bellekte ayrı bir yerde durur ve bir sonrakinin adresini bir **işaretçi** ile tutar; araya eleman eklemek kolaydır ama bir elemana ulaşmak için bağlantıları izlemek gerekir.

### Zaman ve bellek: iki ayrı maliyet

Big-O yalnızca süreyi değil, kullanılan belleği de anlatır. Buna **alan karmaşıklığı** denir. Çoğu zaman ikisi arasında bir takas vardır: hash tablosu aramayı O(1)'e indirir ama boş kovalar için fazladan bellek harcar; trie önek aramasını hızlandırır ama her düğümde çok sayıda bağlantı tutar. Bağlı liste de her eleman için fazladan bir işaretçi saklar; 8 baytlık bir tam sayıyı tutan düğüm, 64 bit bir sistemde işaretçisiyle birlikte en az 16 bayt yer kaplar.

Bir başka önemli kavram **amortize maliyettir**. Dinamik diziye eleman eklemek çoğu zaman tek adım sürer, ama dizi dolduğunda bütün elemanların kopyalanması gerekir. Bu pahalı işlem ne kadar büyük olursa olsun o kadar seyrek gerçekleşir ki, toplam maliyeti ekleme sayısına böldüğünüzde eleman başına sabit bir süre çıkar. “Ortalama O(1)” ifadesinin arkasındaki mantık budur.

### Soyut veri tipi ile veri yapısı

**Soyut veri tipi** (ADT) ne yapılabileceğini, **veri yapısı** ise bunun nasıl yapıldığını tanımlar. “Yığın” bir soyut tiptir: yalnızca üstten eleman eklenir ve alınır. Bu davranışı bir diziyle de bağlı listeyle de gerçekleştirebilirsiniz.

## Doğrusal yapılar

### Dizi (array)

**Dizi**, aynı türden elemanların bellekte yan yana durduğu en temel yapıdır. İlk elemanın adresi ve eleman boyutu bilindiği için *i*. elemanın adresi tek bir çarpma ve toplama ile bulunur. Bu yüzden indeksle erişim O(1)'dir.

Bedeli, esnek olmamasıdır. Klasik bir dizinin boyutu sabittir. Ortaya bir eleman eklemek için sonraki bütün elemanları bir sağa kaydırmanız gerekir; bu O(n)'dir.

**Dinamik dizi** (Python'da `list`, Java'da `ArrayList`, C++'ta `std::vector`) bu sorunu yumuşatır: dolduğunda daha büyük (genellikle iki katı) bir alan ayırıp elemanları oraya kopyalar. Kopyalama pahalıdır ama seyrek olur; bu yüzden sona eklemenin *ortalama* maliyeti yine O(1)'dir.

```python
sayilar = [10, 20, 30, 40]
print(sayilar[2])        # O(1): doğrudan erişim -> 30
sayilar.append(50)       # ortalama O(1): sona ekleme
sayilar.insert(0, 5)     # O(n): bütün elemanlar kayar
```

### Bağlı liste (linked list)

**Bağlı liste**, her biri bir değer ve bir sonraki düğümün adresini tutan **düğümlerden** oluşur. Düğümler bellekte dağınık durur; listeyi birbirine bağlayan şey işaretçilerdir.

Yerini bildiğiniz bir düğümün ardına yeni eleman eklemek ya da onu silmek O(1)'dir: yalnızca iki işaretçi değişir, hiçbir şey kaymaz. Bedeli, rastgele erişimin olmamasıdır. Beşinci elemana ulaşmak için baştan başlayıp beş bağlantı izlemeniz gerekir; erişim ve arama O(n)'dir.

**Çift yönlü bağlı listede** her düğüm bir öncekini de gösterir; liste iki yönde gezilebilir, karşılığında düğüm başına bir işaretçi daha yer kaplar.

:::svg Dizide araya eleman eklemek için sonraki elemanlar kaydırılır. Bağlı listede yalnızca iki işaretçi değişir.
<svg viewBox="0 0 720 320" role="img" aria-label="Üstte dizi: araya 25 değeri eklenirken sonraki 30 ve 40 değerleri bir hücre sağa kayar. Altta bağlı liste: 20 ile 30 düğümleri arasına 25 düğümü, yalnızca iki ok değiştirilerek eklenir" data-dur="10">
<text x="20" y="26" class="t-b">Dizi: araya ekleme O(n)</text>
<g class="s-bg3 l-fg w2"><rect x="60" y="50" width="80" height="54"/><rect x="140" y="50" width="80" height="54"/><rect x="220" y="50" width="80" height="54"/><rect x="300" y="50" width="80" height="54"/><rect x="380" y="50" width="80" height="54"/></g>
<text x="100" y="124" text-anchor="middle" class="t-s">[0]</text><text x="180" y="124" text-anchor="middle" class="t-s">[1]</text><text x="260" y="124" text-anchor="middle" class="t-s">[2]</text><text x="340" y="124" text-anchor="middle" class="t-s">[3]</text><text x="420" y="124" text-anchor="middle" class="t-s">[4]</text>
<text x="100" y="84" text-anchor="middle" class="t-l">10</text><text x="180" y="84" text-anchor="middle" class="t-l">20</text>
<g data-a="move 80 0 .25 .45 show"><rect x="304" y="54" width="72" height="46" rx="4" class="s-soft"/><text x="340" y="84" text-anchor="middle" class="t-l">40</text></g>
<g data-a="move 80 0 .05 .25 show"><rect x="224" y="54" width="72" height="46" rx="4" class="s-soft"/><text x="260" y="84" text-anchor="middle" class="t-l">30</text></g>
<g data-a="move 0 62 .5 .68 keep"><rect x="224" y="-8" width="72" height="46" rx="4" class="s-acc"/><text x="260" y="22" text-anchor="middle" class="t-l t-ink">25</text></g>
<text x="500" y="72" class="t-s" data-a="show .05 .5">önce 40, sonra 30 sağa kayar…</text><text x="500" y="92" class="t-s" data-a="show .5">…açılan yere 25 yazılır</text>
<text x="20" y="178" class="t-b">Bağlı liste: araya ekleme O(1)</text>
<g class="s-bg3 l-fg w2"><rect x="60" y="200" width="90" height="50" rx="8"/><rect x="230" y="200" width="90" height="50" rx="8"/><rect x="480" y="200" width="90" height="50" rx="8"/></g>
<text x="105" y="232" text-anchor="middle" class="t-l">10</text><text x="275" y="232" text-anchor="middle" class="t-l">20</text><text x="525" y="232" text-anchor="middle" class="t-l">30</text>
<path d="M150 225h72m-10-6 10 6-10 6" class="l-fg w2 rc" fill="none"/>
<path d="M320 225h152m-10-6 10 6-10 6" class="l-mut w2 rc dash" fill="none" data-a="show 0 .6"/>
<g data-a="show .55"><rect x="355" y="262" width="90" height="46" rx="8" class="s-acc"/><text x="400" y="292" text-anchor="middle" class="t-l t-ink">25</text></g>
<path d="M320 236c20 30 20 48 32 49m-12-3 12 3-8 9" class="l-acc w3 rc" fill="none" data-a="show .68"/>
<path d="M445 285c20-2 30-18 44-36m-12 4 12-4 0 13" class="l-acc w3 rc" fill="none" data-a="show .8"/>
<text x="600" y="232" class="t-s" data-a="show .8">yalnızca iki</text><text x="600" y="250" class="t-s" data-a="show .8">işaretçi değişti</text>
</svg>
:::

### Yığın (stack)

**Yığın**, “son giren ilk çıkar” (**LIFO**) kuralıyla çalışır. Üst üste konmuş tabaklar gibidir: yalnızca en üste tabak koyabilir (**push**) ve yalnızca en üsttekini alabilirsiniz (**pop**). İki işlem de O(1)'dir.

Yığın her yerdedir. Bir fonksiyon başka bir fonksiyonu çağırdığında nereye döneceği **çağrı yığınına** yazılır; “stack overflow” hatası bu yığının taşmasıdır. Tarayıcının geri düğmesi, metin düzenleyicinin geri al komutu ve parantezlerin dengeli olup olmadığını denetleyen algoritmalar da yığın kullanır.

### Kuyruk (queue)

**Kuyruk**, “ilk giren ilk çıkar” (**FIFO**) kuralıyla çalışır; bir gişe sırası gibi. Eleman arkadan eklenir (**enqueue**), önden alınır (**dequeue**). Yazıcıya gönderilen belgeler, işlenmeyi bekleyen ağ paketleri ve arka planda çalışacak görevler kuyrukta bekler.

:::svg Yığında eklenen son eleman ilk çıkar (LIFO). Kuyrukta ilk eklenen ilk çıkar (FIFO).
<svg viewBox="0 0 720 300" role="img" aria-label="Solda yığın: A, B ve C sırayla üstten eklenir, önce en son eklenen C çıkar. Sağda kuyruk: A, B ve C arkadan eklenir, önce ilk eklenen A önden çıkar" data-dur="12">
<text x="150" y="24" text-anchor="middle" class="t-l">Yığın (LIFO)</text><text x="520" y="24" text-anchor="middle" class="t-l">Kuyruk (FIFO)</text>
<path d="M90 60v200h120V60" class="l-fg w3 rc" fill="none"/>
<g data-a="move 0 170 .02 .12 keep"><rect x="98" y="38" width="104" height="44" rx="6" class="s-blue"/><text x="150" y="67" text-anchor="middle" class="t-l t-w">A</text></g>
<g data-a="move 0 120 .14 .24 keep"><rect x="98" y="38" width="104" height="44" rx="6" class="s-acc2"/><text x="150" y="67" text-anchor="middle" class="t-l t-w">B</text></g>
<g data-a="route 0,70 0,70 0,70 0,70 0,70 180,70 .26 .62"><rect x="98" y="38" width="104" height="44" rx="6" class="s-acc"/><text x="150" y="67" text-anchor="middle" class="t-l t-ink">C</text></g>
<text x="150" y="278" text-anchor="middle" class="t-s" data-a="show .02">push A, push B, push C</text>
<text x="150" y="296" text-anchor="middle" class="t-s t-b" data-a="show .5">pop → C (son giren)</text>
<path d="M360 10v280" class="l-line w1 dash" fill="none"/>
<path d="M400 120h300M400 180h300" class="l-fg w3 rc" fill="none"/>
<text x="400" y="108" class="t-s">ön (çıkış)</text><text x="700" y="108" text-anchor="end" class="t-s">arka (giriş)</text>
<g data-a="route -270,0 -270,0 -270,0 -270,0 -270,0 -270,0 -270,0 -270,100 .02 .7"><rect x="676" y="128" width="64" height="44" rx="6" class="s-blue"/><text x="708" y="157" text-anchor="middle" class="t-l t-w">A</text></g>
<g data-a="move -196 0 .14 .24 keep"><rect x="676" y="128" width="64" height="44" rx="6" class="s-acc2"/><text x="708" y="157" text-anchor="middle" class="t-l t-w">B</text></g>
<g data-a="move -122 0 .26 .36 keep"><rect x="676" y="128" width="64" height="44" rx="6" class="s-acc"/><text x="708" y="157" text-anchor="middle" class="t-l t-ink">C</text></g>
<text x="550" y="230" text-anchor="middle" class="t-s" data-a="show .02">enqueue A, enqueue B, enqueue C</text>
<text x="550" y="296" text-anchor="middle" class="t-s t-b" data-a="show .6">dequeue → A (ilk giren)</text>
</svg>
:::

### Deque ve öncelik kuyruğu

**Deque** (çift uçlu kuyruk), iki ucundan da O(1) sürede ekleme ve çıkarma yapılabilen yapıdır; hem yığın hem kuyruk gibi kullanılabilir. Kayan pencere algoritmalarında ve iş çalma (work stealing) zamanlayıcılarında tercih edilir.

**Öncelik kuyruğunda** ise elemanlar geliş sırasına göre değil, önceliklerine göre çıkar. Bu davranışı verimli biçimde sağlayan yapı, aşağıda anlatacağımız heap'tir.

## Hash tablosu

**Hash tablosu** (sözlük, eşleme, `dict`, `HashMap`), anahtar–değer çiftlerini saklar ve bir anahtarın değerini ortalama O(1) sürede bulur. Bunu bir **hash fonksiyonu** ile yapar: fonksiyon anahtarı (örneğin bir metni) bir sayıya çevirir, bu sayı da tablodaki bir hücrenin (**kova**) indeksi olur. Böylece aranan değer için bütün tablo taranmaz; doğrudan ilgili kovaya gidilir.

İki farklı anahtar aynı kovaya düşerse buna **çakışma** denir ve kaçınılmazdır. İki yaygın çözüm vardır: **zincirleme** (her kovada küçük bir liste tutulur) ve **açık adresleme** (dolu kovayla karşılaşınca belirli bir kurala göre başka bir kova denenir). Tablo doldukça çakışmalar artar; bu yüzden **doluluk oranı** belirli bir eşiği aşınca tablo büyütülür ve bütün elemanlar yeniden yerleştirilir.

```python
stok = {"elma": 12, "armut": 5}
stok["muz"] = 30          # ortalama O(1) ekleme
print(stok["elma"])       # ortalama O(1) arama -> 12
print("kivi" in stok)     # ortalama O(1) üyelik denetimi -> False
```

Hash tablosunun iki zayıf yanı vardır: elemanlar sıralı tutulmaz ve en kötü durumda (bütün anahtarlar aynı kovaya düşerse) işlemler O(n)'ye iner. **Küme** (set), yalnızca anahtarları saklayan bir hash tablosudur; “bu eleman daha önce görüldü mü?” sorusunu anında yanıtlar.

:::svg Hash fonksiyonu anahtarı bir kova numarasına çevirir; arama yapılırken tablo taranmaz, doğrudan o kovaya gidilir.
<svg viewBox="0 0 720 280" role="img" aria-label="Elma, muz ve kivi anahtarları hash fonksiyonundan geçerek sırasıyla 3, 0 ve 3 numaralı kovalara yerleşir; elma ile kivi aynı kovaya düşerek çakışır ve zincirleme ile yan yana saklanır" data-dur="12">
<rect x="250" y="100" width="150" height="80" rx="12" class="s-soft l-acc w2"/><text x="325" y="134" text-anchor="middle" class="t-b">hash(anahtar)</text><text x="325" y="156" text-anchor="middle" class="t-s">mod 5</text>
<g class="s-bg3 l-fg w2"><rect x="500" y="30" width="60" height="42"/><rect x="500" y="72" width="60" height="42"/><rect x="500" y="114" width="60" height="42"/><rect x="500" y="156" width="60" height="42"/><rect x="500" y="198" width="60" height="42"/></g>
<text x="484" y="56" text-anchor="end" class="t-m">0</text><text x="484" y="98" text-anchor="end" class="t-m">1</text><text x="484" y="140" text-anchor="end" class="t-m">2</text><text x="484" y="182" text-anchor="end" class="t-m">3</text><text x="484" y="224" text-anchor="end" class="t-m">4</text>
<g data-a="show 0 .3"><rect x="40" y="118" width="120" height="44" rx="8" class="s-blue"/><text x="100" y="146" text-anchor="middle" class="t-b t-w">"elma"</text></g>
<circle cx="170" cy="140" r="8" class="s-blue" data-a="move 70 0 .03 .12"/><circle cx="410" cy="140" r="8" class="s-blue" data-a="move 84 37 .14 .26"/>
<g data-a="show .26"><rect x="566" y="160" width="62" height="34" rx="6" class="s-blue"/><text x="597" y="182" text-anchor="middle" class="t-s t-w t-b">elma</text></g>
<g data-a="show .33 .63"><rect x="40" y="118" width="120" height="44" rx="8" class="s-acc2"/><text x="100" y="146" text-anchor="middle" class="t-b t-w">"muz"</text></g>
<circle cx="170" cy="140" r="8" class="s-acc2" data-a="move 70 0 .36 .45"/><circle cx="410" cy="140" r="8" class="s-acc2" data-a="move 84 -89 .47 .59"/>
<g data-a="show .59"><rect x="566" y="34" width="62" height="34" rx="6" class="s-acc2"/><text x="597" y="56" text-anchor="middle" class="t-s t-w t-b">muz</text></g>
<g data-a="show .66 .98"><rect x="40" y="118" width="120" height="44" rx="8" class="s-acc"/><text x="100" y="146" text-anchor="middle" class="t-b t-ink">"kivi"</text></g>
<circle cx="170" cy="140" r="8" class="s-acc" data-a="move 70 0 .69 .78"/><circle cx="410" cy="140" r="8" class="s-acc" data-a="move 84 37 .8 .9"/>
<g data-a="show .9"><path d="M628 177h10" class="l-fg w2"/><rect x="638" y="160" width="62" height="34" rx="6" class="s-acc"/><text x="669" y="182" text-anchor="middle" class="t-s t-ink t-b">kivi</text><text x="600" y="262" text-anchor="middle" class="t-s">çakışma: aynı kovada zincirlenir</text></g>
<text x="530" y="20" text-anchor="middle" class="t-s">kovalar</text>
</svg>
:::

## Ağaçlar

### Ağaç ve ikili ağaç

**Ağaç**, bir **kök** düğümden başlayıp dallanan hiyerarşik bir yapıdır. Her düğümün sıfır ya da daha çok **çocuğu** vardır; çocuğu olmayan düğümlere **yaprak** denir. Dosya sistemi klasörleri, HTML belgesinin DOM yapısı ve bir şirketin organizasyon şeması birer ağaçtır. Her düğümün en fazla iki çocuğu varsa buna **ikili ağaç** denir.

### İkili arama ağacı (BST)

**İkili arama ağacında** bir kural vardır: her düğümün solundaki bütün değerler ondan küçük, sağındakiler ondan büyüktür. Bu kural sayesinde arama yaparken her adımda ağacın yarısını elersiniz: aradığınız değer düğümden küçükse sola, büyükse sağa gidersiniz.

Ağaç dengeliyse arama, ekleme ve silme O(log n)'dir; bir milyon elemanda yaklaşık yirmi karşılaştırma yeter. Ayrıca ağacı soldan sağa gezmek elemanları sıralı verir; hash tablosunun yapamadığı şey budur.

:::svg İkili arama ağacında 60 değerinin aranması: her düğümde değer karşılaştırılır ve ağacın yarısı elenir. Üç karşılaştırma yeter.
<svg viewBox="0 0 720 300" role="img" aria-label="Kökü 50 olan yedi düğümlü ikili arama ağacında 60 aranıyor: 50'den büyük olduğu için sağa, 70'ten küçük olduğu için sola gidilir ve 60 bulunur" data-dur="9">
<g class="l-mut w2" fill="none"><path d="M360 60 200 140M360 60 520 140M200 140 120 220M200 140 280 220M520 140 440 220M520 140 600 220"/></g>
<g class="s-bg3 l-fg w2"><circle cx="360" cy="60" r="28"/><circle cx="200" cy="140" r="28"/><circle cx="520" cy="140" r="28"/><circle cx="120" cy="220" r="28"/><circle cx="280" cy="220" r="28"/><circle cx="440" cy="220" r="28"/><circle cx="600" cy="220" r="28"/></g>
<circle cx="360" cy="60" r="28" class="s-acc" data-a="show .05 .98"/><circle cx="520" cy="140" r="28" class="s-acc" data-a="show .35 .98"/><circle cx="440" cy="220" r="28" class="s-acc2" data-a="show .65 .98"/>
<text x="360" y="67" text-anchor="middle" class="t-l">50</text><text x="200" y="147" text-anchor="middle" class="t-l">30</text><text x="520" y="147" text-anchor="middle" class="t-l">70</text><text x="120" y="227" text-anchor="middle" class="t-l">20</text><text x="280" y="227" text-anchor="middle" class="t-l">40</text><text x="440" y="227" text-anchor="middle" class="t-l">60</text><text x="600" y="227" text-anchor="middle" class="t-l">80</text>
<text x="20" y="30" class="t-b">Aranan: 60</text>
<text x="420" y="48" class="t-s" data-a="show .05 .35">60 &gt; 50 → sağa git</text>
<text x="570" y="112" class="t-s" data-a="show .35 .65">60 &lt; 70 → sola git</text>
<text x="440" y="278" text-anchor="middle" class="t-s t-b" data-a="show .65">bulundu (3 adım)</text>
<g data-a="show .35"><path d="M268 86l24 28M292 86l-24 28" class="l-red w3 rc"/><text x="150" y="88" class="t-s t-red">bu yarı elendi</text></g>
</svg>
:::

### Dengeli ağaçlar: AVL ve kırmızı-siyah

Sıradan bir BST'nin zayıf noktası dengedir. Elemanları sıralı biçimde eklerseniz (1, 2, 3, 4…) ağaç tek yöne uzar ve fiilen bir bağlı listeye dönüşür; arama O(n)'ye düşer.

**AVL ağacı** bunu engeller: her düğümde sol ve sağ alt ağaçların yükseklik farkı en fazla 1 olabilir. Bir ekleme ya da silme bu dengeyi bozarsa ağaç, **döndürme** adı verilen yerel düzenlemelerle kendini yeniden dengeler. Böylece yükseklik her zaman log n düzeyinde kalır ve işlemler en kötü durumda bile O(log n)'dir.

**Kırmızı-siyah ağaç** daha gevşek bir denge kuralı kullanır; aramada AVL'den biraz yavaş, ekleme ve silmede biraz hızlıdır. Java'nın `TreeMap`'i ve C++'ın `std::map`'i kırmızı-siyah ağaçtır. Veritabanı dizinlerinde ise her düğümü çok sayıda anahtar tutan **B-ağacı** kullanılır; disk erişimini en aza indirmek için tasarlanmıştır.

### Heap

**Heap** (yığın ağacı), yalnızca tek bir şeyi çok hızlı yapmak için düzenlenmiş bir ikili ağaçtır: en küçük (ya da en büyük) elemanı vermek. **Min-heap**'te her düğüm çocuklarından küçüktür; dolayısıyla en küçük eleman her zaman köktedir ve ona bakmak O(1)'dir. Eleman eklemek ve en küçüğü çıkarmak O(log n) sürer.

Heap tam sıralı değildir; yalnızca ebeveyn–çocuk ilişkisi garanti edilir. Bu gevşeklik onu hızlı kılar. İlginç bir ayrıntı: heap, işaretçilerle değil düz bir diziyle saklanır; *i* indeksindeki düğümün çocukları 2i+1 ve 2i+2 indekslerindedir.

Öncelik kuyrukları, en kısa yol bulan Dijkstra algoritması, işletim sistemi zamanlayıcıları ve “en büyük k eleman” soruları heap kullanır.

### Trie

**Trie** (önek ağacı), metinleri karakter karakter saklayan bir ağaçtır. Kökten bir düğüme giden yol bir öneki oluşturur; ortak önekli sözcükler aynı yolu paylaşır. Bir sözcüğü aramak ya da eklemek, sözlükte kaç sözcük olduğundan bağımsız olarak sözcüğün uzunluğu (*k*) kadar adım sürer: O(k).

Arama kutusundaki otomatik tamamlama, yazım denetimi ve yönlendiricilerin IP öneki eşleştirmesi trie ile yapılır. Bedeli bellek kullanımıdır: her düğüm olası her karakter için bir bağlantı tutabilir.

## Graf

**Graf**, **düğümler** ve onları birbirine bağlayan **kenarlardan** oluşan en genel yapıdır. Ağaç da aslında döngüsü olmayan özel bir graftır. Kenarlar **yönlü** (Ayşe, Mehmet'i takip ediyor) ya da **yönsüz** (Ayşe ile Mehmet arkadaş), **ağırlıklı** (iki şehir arası 450 km) ya da ağırlıksız olabilir.

Graf iki biçimde saklanır. **Komşuluk listesinde** her düğüm için komşularının listesi tutulur; az kenarlı (seyrek) graflarda bellek dostudur. **Komşuluk matrisinde** ise n × n'lik bir tablo tutulur; “şu iki düğüm bağlı mı?” sorusu O(1)'de yanıtlanır ama n² yer kaplar.

Graflar üzerinde iki temel gezinme yöntemi vardır. **Genişlik öncelikli arama** (BFS) bir kuyruk kullanır, düğümleri katman katman gezer ve ağırlıksız graflarda en kısa yolu bulur. **Derinlik öncelikli arama** (DFS) bir yığın kullanır ve bir yolu sonuna kadar izleyip geri döner. Harita uygulamalarındaki rota hesabı, sosyal ağlardaki arkadaş önerileri, paket yöneticilerinin bağımlılık çözümü ve internetin kendisi graf problemleridir.

## Gerçek hayatta veri yapıları

Bu yapıların hiçbiri yalnızca ders kitaplarında yaşamaz; her gün kullandığınız yazılımların içindedirler.

- **Tarayıcınız** sekme geçmişini iki yığınla tutar: geri gittiğinizde sayfa “ileri” yığınına taşınır. Sayfanın kendisi (DOM) bir ağaçtır.
- **Veritabanları** tablolardaki satırları hızlı bulmak için B-ağacı dizinleri kullanır. Dizin olmayan bir sütunda arama yapmak, bağlı listede arama yapmak gibidir: tablo baştan sona taranır.
- **Harita uygulamaları** yol ağını ağırlıklı bir graf olarak saklar ve en kısa rotayı, heap tabanlı bir öncelik kuyruğu kullanan Dijkstra ya da A* algoritmasıyla bulur.
- **Önbellekler** (örneğin en son kullanılanı tutan LRU önbellek) bir hash tablosu ile çift yönlü bağlı listeyi birleştirir: tablo anında bulmayı, liste ise en eski kaydı anında atmayı sağlar.
- **İşletim sistemleri** çalışmayı bekleyen süreçleri kuyruklarda ve öncelik kuyruklarında tutar; dosya sistemi bir ağaçtır.
- **Derleyiciler** kaynak kodu soyut sözdizimi ağacına çevirir, değişken adlarını hash tablolarında saklar ve parantezleri yığınla denetler.
- **Sosyal ağlar** kullanıcıları düğüm, takip ilişkilerini kenar olarak saklar; “tanıyor olabileceğiniz kişiler” önerisi bir graf gezinmesidir.

Görüldüğü gibi gerçek sistemler çoğu zaman tek bir yapıyla yetinmez; her birinin güçlü yanını bir araya getiren bileşimler kurar.

## Büyük karşılaştırma tablosu

Aşağıdaki tablo, işlemlerin ortalama durumdaki zaman karmaşıklığını gösterir. *n* eleman sayısı, *k* anahtarın uzunluğudur.

| Veri yapısı | Erişim | Arama | Ekleme | Silme | Sıralı mı? | Not |
|---|---|---|---|---|---|---|
| Dizi | O(1) | O(n) | O(n) | O(n) | Ekleme sırası | Sıralıysa ikili arama O(log n) |
| Dinamik dizi | O(1) | O(n) | Sona: O(1) ort. | O(n) | Ekleme sırası | Büyürken kopyalama yapılır |
| Bağlı liste | O(n) | O(n) | O(1)* | O(1)* | Ekleme sırası | *Konum biliniyorsa |
| Yığın | O(n) | O(n) | O(1) | O(1) | LIFO | Yalnızca üst uç |
| Kuyruk | O(n) | O(n) | O(1) | O(1) | FIFO | Arkadan gir, önden çık |
| Deque | O(n) | O(n) | O(1) | O(1) | İki uç | İki uçtan ekleme ve çıkarma |
| Hash tablosu | — | O(1) ort. | O(1) ort. | O(1) ort. | Hayır | En kötü durum O(n) |
| İkili arama ağacı | O(log n) ort. | O(log n) ort. | O(log n) ort. | O(log n) ort. | Evet | Dengesizse O(n) |
| AVL / kırmızı-siyah | O(log n) | O(log n) | O(log n) | O(log n) | Evet | En kötü durumda da garanti |
| Heap | En küçük: O(1) | O(n) | O(log n) | En küçük: O(log n) | Kısmen | Öncelik kuyruğu için |
| Trie | O(k) | O(k) | O(k) | O(k) | Evet (alfabetik) | Önek aramaları |
| Graf (komşuluk listesi) | — | O(V + E) | O(1) | O(E) | — | V düğüm, E kenar sayısı |

## Hangisini seçmeliyim?

Doğru soru “hangi yapı en iyi” değil, “en sık hangi işlemi yapacağım” sorusudur.

| En sık yaptığınız iş | Uygun yapı |
|---|---|
| İndeksle erişim, baştan sona gezme | Dizi / dinamik dizi |
| Anahtara göre hızlı bulma | Hash tablosu |
| “Bu eleman var mı?” denetimi, yinelenenleri ayıklama | Küme (hash set) |
| Sıralı tutma, aralık sorgusu (“10 ile 50 arası”) | Dengeli ağaç |
| Hep en küçük ya da en büyük elemanı alma | Heap |
| Son yapılanı geri alma, iç içe yapılar | Yığın |
| Sırayla işleme, görev bekletme | Kuyruk |
| İki uçtan ekleme ve çıkarma | Deque |
| Önek arama, otomatik tamamlama | Trie |
| İlişkiler, yollar, bağımlılıklar | Graf |
| Ortadan çok sık ekleme ve silme | Bağlı liste (önce ölçün) |

Aşağıdaki JavaScript örneği, aynı “bu değer listede var mı?” sorusunun iki farklı yapıyla nasıl yanıtlandığını gösterir. İlki her seferinde diziyi baştan tarar; ikincisi hash tabanlı bir küme kullanır.

```javascript
const dizi = Array.from({ length: 1_000_000 }, (_, i) => i);
const kume = new Set(dizi);

dizi.includes(999_999); // O(n): bir milyon elemana bakar
kume.has(999_999);      // ortalama O(1): doğrudan kovaya gider
```

Tek bir sorguda fark hissedilmez. Ama bu denetimi bir döngü içinde yüz bin kez yaparsanız ilk yöntem saniyeler, ikincisi milisaniyeler sürer.

:::tip Önce dinamik dizi ve hash tablosu
Gündelik programlamadaki ihtiyaçların büyük bölümünü bu iki yapı karşılar. Bitişik bellek, işlemci önbelleğiyle o kadar iyi çalışır ki küçük ve orta boyutlu verilerde dizi, kuramsal olarak daha iyi görünen bağlı listeyi çoğu zaman geçer. Daha özel bir yapıya, ölçüm bir darboğaz gösterdiğinde geçin.
:::

## Sık sorulan sorular

:::faq
Q: Veri yapısı ile algoritma arasındaki fark nedir?
A: Veri yapısı verinin nasıl düzenlendiğini, algoritma ise o veri üzerinde adım adım ne yapılacağını tanımlar. İkisi birbirinden ayrılmaz: ikili arama algoritması sıralı bir dizi, Dijkstra algoritması bir heap ister.
Q: Hash tablosu O(1) ise neden her yerde onu kullanmıyoruz?
A: Çünkü elemanları sıralı tutmaz, aralık sorgusu yapamaz, en küçük elemanı hızlı veremez ve dizilere göre daha çok bellek kullanır. Ayrıca O(1) ortalama durumdur; kötü bir hash fonksiyonuyla O(n)'ye düşebilir.
Q: Python'daki list bir bağlı liste mi?
A: Hayır. Adına rağmen Python'ın list tipi bir dinamik dizidir; indeksle erişim O(1), başa ekleme O(n)'dir. İki uçtan hızlı ekleme için collections.deque kullanılır.
Q: Stack overflow hatası tam olarak nedir?
A: Her fonksiyon çağrısı çağrı yığınına bir kayıt ekler. Sonu gelmeyen ya da çok derin bir özyineleme bu yığına ayrılan belleği doldurduğunda program stack overflow hatasıyla durur.
Q: Heap veri yapısı ile bellekteki heap aynı şey mi?
A: Hayır, yalnızca adları aynıdır. Veri yapısı olan heap bir öncelik ağacıdır; bellekteki heap ise programın çalışma anında dinamik olarak ayırdığı bellek bölgesidir.
Q: Mülakatlar için hangi veri yapılarını bilmeliyim?
A: Dizi, hash tablosu, bağlı liste, yığın, kuyruk, ikili arama ağacı, heap ve graf ile BFS/DFS gezinmeleri soruların büyük bölümünü karşılar. Her birinin Big-O değerlerini nedenleriyle bilmek ezberlemekten daha değerlidir.
Q: Ortalama durum ile en kötü durum arasındaki fark neden önemli?
A: Ortalama durum tipik başarımı, en kötü durum ise güvenebileceğiniz sınırı gösterir. Gerçek zamanlı sistemlerde ya da kötü niyetli girdi alabilecek servislerde en kötü durum garantisi olan yapılar (örneğin dengeli ağaçlar) tercih edilir.
:::

## Özet

- Big-O, veri büyüdükçe işlem süresinin nasıl arttığını gösterir; yapıları kıyaslamanın ortak dilidir.
- Dizi hızlı erişim, bağlı liste kolay ekleme sağlar; ikisi de aramada O(n)'dir.
- Yığın LIFO, kuyruk FIFO kuralıyla çalışır; ikisi de O(1) ekleme ve çıkarma yapar.
- Hash tablosu ortalama O(1) arama sunar ama sırayı korumaz.
- Dengeli ağaçlar (AVL, kırmızı-siyah) sıralı veride O(log n) garantisi verir; heap en küçük ya da en büyük elemanı anında sunar; trie önek aramalarında güçlüdür.
- Graf, ilişkileri modellemenin en genel yoludur; BFS ve DFS ile gezilir.
- Seçimi en sık yaptığınız işlem belirler; çoğu zaman dinamik dizi ve hash tablosu yeterlidir.

## Kaynaklar

- [Cormen, Leiserson, Rivest, Stein – Introduction to Algorithms, 4. baskı (MIT Press)](https://mitpress.mit.edu/9780262046305/introduction-to-algorithms/)
- [Sedgewick & Wayne – Algorithms, 4. baskı: çevrim içi kitap sitesi](https://algs4.cs.princeton.edu/home/)
- [MIT OpenCourseWare 6.006 – Introduction to Algorithms](https://ocw.mit.edu/courses/6-006-introduction-to-algorithms-spring-2020/)
- [Python Wiki – Time Complexity](https://wiki.python.org/moin/TimeComplexity)
- [Python belgeleri – collections.deque](https://docs.python.org/3/library/collections.html#collections.deque)
- [Oracle Java belgeleri – TreeMap](https://docs.oracle.com/en/java/javase/21/docs/api/java.base/java/util/TreeMap.html)
- [cppreference – Containers library](https://en.cppreference.com/w/cpp/container)
- [NIST Dictionary of Algorithms and Data Structures](https://xlinux.nist.gov/dads/)
- [Adelson-Velsky & Landis (1962), AVL ağacının özgün makalesi hakkında – NIST DADS: AVL tree](https://xlinux.nist.gov/dads/HTML/avltree.html)
