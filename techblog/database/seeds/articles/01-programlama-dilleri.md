---
title: Every Programming Language Explained: Programlama Dillerinin Tamamı, Tek Yazıda
slug: every-programming-language-explained
category: yazilim
tags: programlama dilleri, python, javascript, rust, c, derleyici
description: Assembly'den Python'a, Rust'tan SQL'e 20'den fazla programlama dili: seviyeleri, çalışma biçimleri, kod örnekleri ve derlenen ile yorumlanan dil farkı.
excerpt: Assembly, C, C++, Java, Python, JavaScript, Go, Rust ve diğerleri: hangi dil ne işe yarar, hangi seviyede çalışır, derlenen ile yorumlanan dil arasındaki fark nedir? Kod örnekleri ve karşılaştırma tablolarıyla.
cover: /assets/covers/programming-languages.svg
cover_alt: Farklı programlama dillerini temsil eden kod pencereleri ve süslü parantezlerden oluşan çizim
series: Every X Explained
series_order: 1
featured: 1
date: 2026-10-20 09:00
---
Bir bilgisayara ne yapacağını söylemenin yüzlerce yolu var. Bu yolların her birine **programlama dili** diyoruz ve hiçbiri “en iyisi” değil: bazıları donanıma çok yakın çalışıp her baytın hesabını size bırakır, bazıları ise tek satırla bir web sunucusu ayağa kaldırır. Bu yazıda en çok kullanılan dilleri tek tek ele alacak, her birinin hangi seviyede çalıştığını, nerede kullanıldığını ve neden var olduğunu açıklayacağız.

Önce dilleri birbirinden ayıran dört temel kavramı öğreneceğiz. Bu kavramları bildiğinizde, daha önce hiç duymadığınız bir dille karşılaşsanız bile onu doğru yere koyabilirsiniz.

## Temel kavramlar

### Düşük seviye ve yüksek seviye

Bir dilin “seviyesi”, donanımdan ne kadar uzak olduğunu anlatır; kalitesini değil. **Düşük seviyeli** bir dilde işlemcinin yazmaçlarını, bellek adreslerini ve tek tek komutları siz yönetirsiniz. **Yüksek seviyeli** bir dilde ise “bu listeyi sırala” dersiniz ve belleğin nasıl ayrılacağını, hangi işlemci komutlarının kullanılacağını dil sizin yerinize halleder.

Düşük seviye size denetim ve hız verir, karşılığında daha çok kod ve daha çok hata riski ister. Yüksek seviye üretkenlik verir, karşılığında bir miktar hız ve denetimden vazgeçersiniz.

:::svg Soyutlama merdiveni: her basamak bir alttakinin ayrıntılarını gizler. Aynı “iki sayıyı topla” isteği, yukarı çıktıkça daha kısa ve okunaklı yazılır.
<svg viewBox="0 0 720 330" role="img" aria-label="Donanımdan Python'a uzanan beş basamaklı soyutlama merdiveni; her basamakta aynı toplama işleminin o seviyedeki yazımı gösterilir" data-dur="10">
<rect x="20" y="262" width="680" height="48" rx="8" class="s-bg3 l-line w2"/><text x="40" y="291" class="t-b">Donanım</text><text x="250" y="291" class="t-m">transistörler: açık / kapalı</text>
<rect x="60" y="204" width="600" height="48" rx="8" class="s-bg3 l-line w2"/><text x="80" y="233" class="t-b">Makine kodu</text><text x="250" y="233" class="t-m">10001011 01000101 00000011</text>
<rect x="100" y="146" width="520" height="48" rx="8" class="s-bg3 l-line w2"/><text x="120" y="175" class="t-b">Assembly</text><text x="250" y="175" class="t-m">mov eax, [a]   add eax, [b]</text>
<rect x="140" y="88" width="440" height="48" rx="8" class="s-bg3 l-line w2"/><text x="160" y="117" class="t-b">C</text><text x="250" y="117" class="t-m">int c = a + b;</text>
<rect x="180" y="30" width="360" height="48" rx="8" class="s-soft l-acc w2"/><text x="200" y="59" class="t-b">Python</text><text x="290" y="59" class="t-m">c = a + b</text>
<rect x="20" y="262" width="680" height="48" rx="8" class="s-none l-acc w3" data-a="show 0 .2"/>
<rect x="60" y="204" width="600" height="48" rx="8" class="s-none l-acc w3" data-a="show .2 .4"/>
<rect x="100" y="146" width="520" height="48" rx="8" class="s-none l-acc w3" data-a="show .4 .6"/>
<rect x="140" y="88" width="440" height="48" rx="8" class="s-none l-acc w3" data-a="show .6 .8"/>
<rect x="180" y="30" width="360" height="48" rx="8" class="s-none l-acc w3" data-a="show .8 1"/>
<text x="690" y="20" text-anchor="end" class="t-s">↑ daha soyut, daha üretken</text><text x="690" y="326" text-anchor="end" class="t-s">↓ daha hızlı, daha çok denetim</text>
</svg>
:::

### Derlenen ve yorumlanan diller

İşlemci yalnızca makine kodundan anlar. Yazdığınız kaynak kodun bir şekilde makine koduna dönüşmesi gerekir ve bunun iki ana yolu vardır:

- **Derleme (compile):** Bir *derleyici*, programın tamamını çalıştırmadan önce makine koduna çevirir ve ortaya çalıştırılabilir bir dosya çıkar. C, C++, Rust ve Go böyle çalışır. Çeviri bir kez yapılır; program sonra çok hızlı çalışır.
- **Yorumlama (interpret):** Bir *yorumlayıcı*, kodu çalışma anında satır satır okuyup uygular. Python, Ruby ve PHP'nin klasik çalışma biçimi budur. Kodu değiştirip hemen deneyebilirsiniz, ama her satır çalışırken çevrildiği için genellikle daha yavaştır.

Gerçek dünyada bu ayrım keskin değildir. Java ve C# önce **bayt koduna** (bytecode) derlenir; bu ara kod bir sanal makinede çalışır ve sık kullanılan bölümler çalışma anında makine koduna çevrilir. Buna **JIT (tam zamanında) derleme** denir. JavaScript motorları da aynı tekniği kullanır; bu yüzden “yorumlanan dil” olarak bilinen JavaScript bugün şaşırtıcı ölçüde hızlıdır.

:::svg Derleyici programın tamamını önceden çevirir; yorumlayıcı her satırı çalışma anında çevirir. Üst şeritte çeviri bir kez yapılır, alt şeritte her satır için tekrarlanır.
<svg viewBox="0 0 720 300" role="img" aria-label="Üstte derleme akışı: kaynak kod derleyiciden geçip çalıştırılabilir dosyaya dönüşür ve işlemcide çalışır. Altta yorumlama akışı: yorumlayıcı kaynak kodun satırlarını sırayla okuyup işlemciye iletir" data-dur="9">
<text x="20" y="24" class="t-b">Derleme</text>
<rect x="20" y="40" width="130" height="80" rx="8" class="s-bg3 l-fg w2"/><text x="85" y="72" text-anchor="middle" class="t-m">main.c</text><text x="85" y="94" text-anchor="middle" class="t-s">kaynak kod</text>
<rect x="215" y="40" width="130" height="80" rx="8" class="s-soft l-acc w2"/><text x="280" y="76" text-anchor="middle" class="t-b">Derleyici</text><text x="280" y="98" text-anchor="middle" class="t-s">hepsini çevirir</text>
<rect x="410" y="40" width="130" height="80" rx="8" class="s-bg3 l-fg w2"/><text x="475" y="72" text-anchor="middle" class="t-m">program.exe</text><text x="475" y="94" text-anchor="middle" class="t-s">makine kodu</text>
<rect x="605" y="40" width="95" height="80" rx="8" class="s-bg3 l-fg w2"/><text x="652" y="86" text-anchor="middle" class="t-b">CPU</text>
<path d="M150 80h65M345 80h65M540 80h65" class="l-mut w2" fill="none"/>
<rect x="158" y="68" width="24" height="24" rx="4" class="s-acc" data-a="move 30 0 0 .15"/>
<rect x="353" y="68" width="24" height="24" rx="4" class="s-acc" data-a="move 30 0 .15 .3"/>
<rect x="548" y="68" width="24" height="24" rx="4" class="s-acc2" data-a="move 30 0 .3 .4"/>
<rect x="548" y="68" width="24" height="24" rx="4" class="s-acc2" data-a="move 30 0 .5 .6"/>
<rect x="548" y="68" width="24" height="24" rx="4" class="s-acc2" data-a="move 30 0 .7 .8"/>
<text x="20" y="174" class="t-b">Yorumlama</text>
<rect x="20" y="190" width="130" height="92" rx="8" class="s-bg3 l-fg w2"/><text x="85" y="210" text-anchor="middle" class="t-m">main.py</text>
<rect x="34" y="220" width="102" height="14" rx="3" class="s-line"/><rect x="34" y="240" width="102" height="14" rx="3" class="s-line"/><rect x="34" y="260" width="102" height="14" rx="3" class="s-line"/>
<rect x="34" y="220" width="102" height="14" rx="3" class="s-acc" data-a="show .05 .35"/><rect x="34" y="240" width="102" height="14" rx="3" class="s-acc" data-a="show .35 .65"/><rect x="34" y="260" width="102" height="14" rx="3" class="s-acc" data-a="show .65 .95"/>
<rect x="290" y="196" width="170" height="80" rx="8" class="s-soft l-acc w2"/><text x="375" y="232" text-anchor="middle" class="t-b">Yorumlayıcı</text><text x="375" y="254" text-anchor="middle" class="t-s">satır satır çevirir</text>
<rect x="605" y="196" width="95" height="80" rx="8" class="s-bg3 l-fg w2"/><text x="652" y="242" text-anchor="middle" class="t-b">CPU</text>
<path d="M150 236h140M460 236h145" class="l-mut w2" fill="none"/>
<rect x="160" y="224" width="24" height="24" rx="4" class="s-acc" data-a="move 100 0 .05 .2"/><rect x="470" y="224" width="24" height="24" rx="4" class="s-acc2" data-a="move 105 0 .2 .35"/>
<rect x="160" y="224" width="24" height="24" rx="4" class="s-acc" data-a="move 100 0 .35 .5"/><rect x="470" y="224" width="24" height="24" rx="4" class="s-acc2" data-a="move 105 0 .5 .65"/>
<rect x="160" y="224" width="24" height="24" rx="4" class="s-acc" data-a="move 100 0 .65 .8"/><rect x="470" y="224" width="24" height="24" rx="4" class="s-acc2" data-a="move 105 0 .8 .95"/>
</svg>
:::

### Tip sistemi

Her değerin bir **tipi** vardır: tam sayı, metin, liste… Diller bu tipleri ne zaman ve ne kadar sıkı denetlediklerine göre ayrılır.

- **Statik tipli** dillerde (C, Java, Rust, Go, TypeScript) bir değişkenin tipi derleme anında bellidir. Bir sayıyı metin bekleyen bir fonksiyona vermeye çalışırsanız program derlenmez. Hatalar erken yakalanır.
- **Dinamik tipli** dillerde (Python, JavaScript, Ruby, PHP) tipler çalışma anında denetlenir. Kod daha kısa yazılır, ama bazı hatalar ancak o satır çalıştığında ortaya çıkar.

Ayrıca **güçlü** ve **zayıf** tip ayrımı vardır. Python güçlü tiplidir: `"3" + 4` yazarsanız hata verir. JavaScript zayıf tiplidir: aynı ifade sessizce `"34"` üretir.

### Bellek yönetimi

Programlar çalışırken bellek ayırır ve işi bitince bu belleği geri vermek zorundadır. Bunun üç yolu vardır:

1. **Elle yönetim:** C ve C++'ta belleği siz ayırır, siz serbest bırakırsınız. En hızlı ve en tehlikeli yoldur; unutursanız bellek sızar, iki kez serbest bırakırsanız program çöker ya da güvenlik açığı oluşur.
2. **Çöp toplayıcı (garbage collector):** Java, C#, Go, Python ve JavaScript'te artık kullanılmayan belleği çalışma zamanı kendisi bulup temizler. Güvenlidir, ama toplayıcının çalıştığı anlarda kısa duraklamalar olabilir.
3. **Sahiplik (ownership):** Rust'ın getirdiği yaklaşımdır. Derleyici, her değerin tek bir sahibi olmasını zorunlu kılar ve sahibi kapsam dışına çıktığında belleği kendiliğinden serbest bırakacak kodu ekler. Çöp toplayıcı yoktur, ama elle yönetimin hataları da derleme anında engellenir.

### Paradigma

Paradigma, bir dilin sizi nasıl düşünmeye yönelttiğidir. **Yordamsal** dillerde adım adım komutlar yazarsınız (C). **Nesne yönelimli** dillerde veriyi ve o veriyle çalışan fonksiyonları sınıflarda toplarsınız (Java, C#). **Fonksiyonel** dillerde programı, yan etkisi olmayan fonksiyonların bileşimi olarak kurarsınız (Haskell). **Bildirimsel** dillerde ise nasıl yapılacağını değil, ne istediğinizi söylersiniz (SQL). Modern dillerin çoğu bu yaklaşımların birkaçını birlikte destekler.

## Diller tek tek

### Assembly

Assembly, makine kodunun insanlar için okunabilir hâlidir: her satır tek bir işlemci komutuna karşılık gelir. Bu yüzden her işlemci mimarisinin (x86, ARM, RISC-V) kendi Assembly'si vardır ve biri için yazılan kod diğerinde çalışmaz.

```asm
; x86-64: iki sayıyı topla
mov eax, 5      ; eax yazmacına 5 koy
add eax, 7      ; üzerine 7 ekle
ret             ; sonucu (12) döndür
```

Bugün neredeyse hiç kimse bütün bir programı Assembly ile yazmaz. Ama işletim sistemi çekirdeklerinin açılış kodunda, gömülü sistemlerde, şifreleme kitaplıklarının en kritik döngülerinde ve tersine mühendislikte hâlâ vazgeçilmezdir.

### C

1972'de Dennis Ritchie'nin Bell Laboratuvarları'nda Unix'i yazmak için geliştirdiği C, “taşınabilir Assembly” olarak anılır. Donanıma çok yakındır ama farklı işlemcilerde derlenebilir. Linux çekirdeği, Windows'un büyük bölümü, veritabanları, Python'ın kendi yorumlayıcısı ve sayısız gömülü cihaz C ile yazılmıştır.

```c
#include <stdio.h>

int main(void) {
    int toplam = 0;
    for (int i = 1; i <= 10; i++) {
        toplam += i;
    }
    printf("Toplam: %d\n", toplam);
    return 0;
}
```

C küçük bir dildir; öğrenmesi kolay, ustalaşması zordur. İşaretçiler (pointer) ve elle bellek yönetimi büyük güç verir, ama bellek hatalarının da ana kaynağıdır.

### C++

Bjarne Stroustrup'un 1985'te yayımladığı C++, C'nin üzerine sınıflar, şablonlar ve zengin bir standart kitaplık ekler. Temel fikri “kullanmadığın özelliğin bedelini ödemezsin”dir: yüksek seviyeli soyutlamalar sunar ama C'ye yakın hızda çalışır. Oyun motorları (Unreal Engine), tarayıcılar (Chrome, Firefox), ses ve görüntü işleme yazılımları ve yüksek frekanslı alım satım sistemleri C++ ile yazılır. Karşılığında dil çok büyüktür ve öğrenmesi uzun sürer.

### C#

Microsoft'un 2000'de duyurduğu C#, Java'ya benzer biçimde bayt koduna derlenir ve .NET çalışma zamanında koşar. Windows masaüstü uygulamalarında, kurumsal arka uç servislerinde ve Unity oyun motoru sayesinde oyun geliştirmede çok yaygındır. .NET artık açık kaynaklıdır ve Linux ile macOS'ta da çalışır.

### Java

1995'te Sun Microsystems'in yayımladığı Java'nın sloganı “bir kez yaz, her yerde çalıştır”dı. Kod, Java Sanal Makinesi'nin (JVM) anladığı bayt koduna derlenir; JVM'in bulunduğu her sistemde aynı dosya çalışır. Bankacılık sistemleri, büyük kurumsal uygulamalar ve Android'in ilk on yılı Java üzerine kuruludur. Sözdizimi ayrıntılıdır, ama araçları, kitaplıkları ve geriye dönük uyumluluğu çok güçlüdür.

### Python

Guido van Rossum'un 1991'de yayımladığı Python, okunabilirliği her şeyin önüne koyar; girintiler sözdiziminin parçasıdır. Aynı işi C'ye göre çok daha az satırla yaparsınız:

```python
toplam = sum(range(1, 11))
print(f"Toplam: {toplam}")
```

Python saf hız açısından yavaş bir dildir. Buna karşın veri bilimi ve yapay zekânın ortak dili olmuştur, çünkü ağır hesaplamayı NumPy ve PyTorch gibi C ve C++ ile yazılmış kitaplıklara devreder. Otomasyon betikleri, web arka uçları (Django, FastAPI) ve eğitim de güçlü olduğu alanlardır.

### JavaScript

Brendan Eich'in 1995'te Netscape için on günde tasarladığı JavaScript, tarayıcıların doğrudan çalıştırdığı tek genel amaçlı dildir; bu yüzden web'in vazgeçilmezidir. Node.js ile sunucu tarafına, React Native ile mobil uygulamalara, Electron ile masaüstüne de taşınmıştır.

```javascript
const sayilar = [1, 2, 3, 4, 5];
const kareler = sayilar.map((n) => n * n);
console.log(kareler); // [1, 4, 9, 16, 25]
```

Aceleyle tasarlanmış olmasının izleri (şaşırtıcı tip dönüşümleri gibi) hâlâ dildedir; ama modern sürümleri ve araçları bu sorunların çoğunu yönetilebilir kılar.

### TypeScript

Microsoft'un 2012'de yayımladığı TypeScript, JavaScript'e statik tipler ekler. Yazdığınız kod derleme adımında denetlenir ve düz JavaScript'e çevrilir; tarayıcı TypeScript'i hiç görmez. Büyük kod tabanlarında hataları erken yakaladığı ve düzenleyicide otomatik tamamlamayı güçlendirdiği için bugün ciddi web projelerinin çoğu TypeScript ile yazılır.

### Go

Google'da Robert Griesemer, Rob Pike ve Ken Thompson'ın tasarladığı Go 2009'da duyuruldu. Bilinçli olarak küçük tutulmuş bir dildir: hızlı derlenir, tek bir çalıştırılabilir dosya üretir ve *goroutine* adlı hafif iş parçacıklarıyla eşzamanlı programlamayı kolaylaştırır.

```go
package main

import "fmt"

func main() {
    kanal := make(chan string)
    go func() { kanal <- "merhaba" }() // ayrı bir goroutine
    fmt.Println(<-kanal)
}
```

Docker ve Kubernetes gibi bulut altyapısı araçları Go ile yazılmıştır; ağ servisleri ve komut satırı araçları için çok popülerdir.

### Rust

Mozilla'da doğan ve 1.0 sürümü 2015'te çıkan Rust, C++ hızında çalışırken bellek hatalarını derleme anında engellemeyi hedefler. Bunu yukarıda anlattığımız sahiplik sistemiyle yapar.

```rust
fn main() {
    let isim = String::from("Rust");
    let sahip = isim;          // sahiplik 'sahip' değişkenine geçti
    // println!("{}", isim);   // derlenmez: 'isim' artık geçersiz
    println!("{}", sahip);
}
```

Öğrenme eğrisi diktir; derleyici ilk haftalarda sık sık itiraz eder. Ama derlenen program çoğu zaman doğru çalışır. Linux çekirdeği, Windows, Android ve Firefox Rust ile yazılmış bileşenler içerir.

### PHP

Rasmus Lerdorf'un 1995'te kişisel sayfası için yazdığı araçlardan doğan PHP, web sunucusunda çalışmak üzere tasarlanmıştır. WordPress, Wikipedia ve Laravel ile yazılmış sayısız site PHP kullanır. Eski sürümlerinin tutarsızlıkları yüzünden kötü bir ünü vardır; ancak PHP 7 ve 8 ile hız, tip desteği ve dil tasarımı ciddi biçimde iyileşmiştir. Paylaşımlı barındırmada en kolay yayınlanan dil olmayı sürdürür.

### Ruby

Yukihiro Matsumoto'nun 1995'te yayımladığı Ruby'nin amacı programcıyı mutlu etmektir: sözdizimi düz yazıya yakındır ve her şey bir nesnedir. Ruby on Rails çatısı, 2000'lerin ortasında web geliştirmeyi kökten hızlandırdı; GitHub ve Shopify bu çatıyla kuruldu. Bugün eskisi kadar popüler değildir, ama hızlı ürün geliştirmede hâlâ güçlü bir seçenektir.

### Swift

Apple'ın 2014'te tanıttığı Swift, iOS ve macOS uygulamalarında Objective-C'nin yerini almak için tasarlandı. Derlenen, statik tipli ve güvenliği önemseyen modern bir dildir; örneğin bir değerin “boş” olabileceğini tip sisteminde açıkça belirtmeniz gerekir. Apple platformları için uygulama yazacaksanız doğal seçimdir.

### Kotlin

JetBrains'in geliştirdiği Kotlin 2011'de duyuruldu, 2017'de Google tarafından Android için resmen desteklendi. JVM üzerinde çalışır, Java koduyla aynı projede sorunsuz kullanılır ve Java'ya göre çok daha kısa yazılır. Bugün yeni Android uygulamalarının varsayılan dilidir.

### Dart

Google'ın 2011'de duyurduğu Dart, asıl yaygınlığını Flutter çatısıyla kazandı. Tek bir kod tabanından iOS, Android, web ve masaüstü uygulaması üretmeyi sağlar. Geliştirme sırasında JIT ile anında yenileme sunar, yayın için ise önceden makine koduna derlenir.

### R

İstatistikçiler Ross Ihaka ve Robert Gentleman'ın 1993'te geliştirdiği R, istatistiksel analiz ve veri görselleştirme için tasarlanmış bir dildir. Akademide, biyoistatistikte ve veri analizinde yaygındır. Genel amaçlı programlama için pek tercih edilmez, ama bir veri kümesini birkaç satırda özetleyip grafiğini çizmek konusunda çok güçlüdür.

### SQL

1970'lerde IBM'de geliştirilen SQL, ilişkisel veritabanlarını sorgulama dilidir ve bildirimseldir: veriye nasıl ulaşılacağını değil, hangi veriyi istediğinizi yazarsınız.

```sql
SELECT sehir, COUNT(*) AS musteri_sayisi
FROM musteriler
WHERE kayit_tarihi >= '2026-01-01'
GROUP BY sehir
ORDER BY musteri_sayisi DESC;
```

MySQL, PostgreSQL, SQLite ve SQL Server'ın hepsi SQL konuşur. Hangi dilde uzmanlaşırsanız uzmanlaşın, SQL bilmek neredeyse her zaman işinize yarar.

### Lua

1993'te Brezilya'da geliştirilen Lua, başka programların içine gömülmek için tasarlanmış küçük ve hızlı bir betik dilidir. Yorumlayıcısı birkaç yüz kilobayttır. Oyunlarda (Roblox, World of Warcraft eklentileri), Neovim gibi düzenleyicilerde ve ağ cihazlarında yapılandırma ve eklenti dili olarak kullanılır.

### Haskell

1990'da yayımlanan Haskell, saf fonksiyonel programlamanın en bilinen temsilcisidir. Fonksiyonların yan etkisi yoktur, değerler değiştirilemez ve hesaplamalar ancak sonucu gerektiğinde yapılır (tembel değerlendirme).

```haskell
-- İlk 10 çift sayının karelerinin toplamı
toplam :: Int
toplam = sum [x * x | x <- take 10 [2, 4 ..]]
```

Endüstride kullanımı sınırlıdır, ama fikirleri başka dilleri derinden etkilemiştir: Rust'ın tip sistemi, Swift'in seçenek tipleri ve JavaScript'in `map`/`filter` kalıpları Haskell geleneğinden beslenir.

### Kısaca diğerleri

- **Bash / Shell:** Unix kabuğunun betik dilidir; sunucu yönetimi ve otomasyonun yapıştırıcısıdır.
- **PowerShell:** Microsoft'un nesne tabanlı kabuk ve betik dilidir; Windows yönetiminde standarttır.
- **Scala:** JVM üzerinde nesne yönelimli ve fonksiyonel programlamayı birleştirir; büyük veri araçlarında (Apache Spark) yaygındır.
- **Elixir:** Erlang sanal makinesinde çalışır; milyonlarca eşzamanlı bağlantıyı yöneten, hataya dayanıklı sistemler için kullanılır.
- **Zig:** C'nin yerini almayı hedefleyen, gizli denetim akışı ve gizli bellek ayırma içermeyen genç bir sistem dilidir.
- **MATLAB:** Mühendislikte matris hesaplamaları ve benzetim için kullanılan ticari bir dil ve ortamdır.
- **COBOL ve Fortran:** 1950'lerden kalan bu iki dil, bankacılık ana sistemlerinde ve bilimsel hesaplamada hâlâ çalışmaktadır.

:::svg Dillerin ortaya çıkış yılları. Bugün kullandığımız dillerin çoğu iki dalgada doğdu: 1990'ların ortası ve 2009–2014 arası.
<svg viewBox="0 0 720 250" role="img" aria-label="1950'den 2020'ye uzanan zaman çizgisi üzerinde programlama dillerinin çıkış yılları: Assembly 1949, C 1972, SQL 1974, C++ 1985, Haskell 1990, Python 1991, Java, JavaScript, PHP ve Ruby 1995, C# 2000, Go 2009, Rust 2010, Kotlin ve Dart 2011, TypeScript 2012, Swift 2014" data-dur="12">
<path d="M30 130h660" class="l-mut w2" fill="none"/>
<g class="l-mut w1"><path d="M40 124v12M132 124v12M224 124v12M316 124v12M408 124v12M500 124v12M592 124v12M684 124v12"/></g>
<text x="40" y="154" text-anchor="middle" class="t-s">1950</text><text x="132" y="154" text-anchor="middle" class="t-s">1960</text><text x="224" y="154" text-anchor="middle" class="t-s">1970</text><text x="316" y="154" text-anchor="middle" class="t-s">1980</text><text x="408" y="154" text-anchor="middle" class="t-s">1990</text><text x="500" y="154" text-anchor="middle" class="t-s">2000</text><text x="592" y="154" text-anchor="middle" class="t-s">2010</text><text x="684" y="154" text-anchor="middle" class="t-s">2020</text>
<g data-a="show .02"><circle cx="31" cy="130" r="6" class="s-acc"/><text x="34" y="108" class="t-s t-b">Assembly</text></g>
<g data-a="show .1"><circle cx="242" cy="130" r="6" class="s-acc"/><text x="242" y="108" text-anchor="middle" class="t-s t-b">C</text></g>
<g data-a="show .16"><circle cx="261" cy="130" r="6" class="s-blue"/><path d="M261 136v34" class="l-line w1"/><text x="261" y="186" text-anchor="middle" class="t-s t-b">SQL</text></g>
<g data-a="show .24"><circle cx="362" cy="130" r="6" class="s-acc"/><text x="362" y="108" text-anchor="middle" class="t-s t-b">C++</text></g>
<g data-a="show .32"><circle cx="408" cy="130" r="6" class="s-pur"/><path d="M408 136v34" class="l-line w1"/><text x="400" y="186" text-anchor="middle" class="t-s t-b">Haskell</text></g>
<g data-a="show .38"><circle cx="417" cy="130" r="6" class="s-acc2"/><path d="M417 124v-34" class="l-line w1"/><text x="417" y="84" text-anchor="middle" class="t-s t-b">Python</text></g>
<g data-a="show .46"><circle cx="454" cy="130" r="8" class="s-red"/><path d="M454 122v-62" class="l-line w1"/><text x="454" y="38" text-anchor="middle" class="t-s t-b">Java · JavaScript</text><text x="454" y="54" text-anchor="middle" class="t-s t-b">PHP · Ruby</text></g>
<g data-a="show .56"><circle cx="500" cy="130" r="6" class="s-blue"/><path d="M500 136v58" class="l-line w1"/><text x="500" y="210" text-anchor="middle" class="t-s t-b">C#</text></g>
<g data-a="show .64"><circle cx="583" cy="130" r="6" class="s-acc"/><path d="M583 124v-34" class="l-line w1"/><text x="574" y="84" text-anchor="middle" class="t-s t-b">Go</text></g>
<g data-a="show .7"><circle cx="592" cy="130" r="6" class="s-acc2"/><path d="M592 136v34" class="l-line w1"/><text x="580" y="186" text-anchor="middle" class="t-s t-b">Rust</text></g>
<g data-a="show .76"><circle cx="601" cy="130" r="6" class="s-pur"/><path d="M601 124v-62" class="l-line w1"/><text x="606" y="54" text-anchor="middle" class="t-s t-b">Kotlin · Dart</text></g>
<g data-a="show .82"><circle cx="610" cy="130" r="6" class="s-blue"/><path d="M610 136v58" class="l-line w1"/><text x="612" y="210" text-anchor="middle" class="t-s t-b">TypeScript</text></g>
<g data-a="show .88"><circle cx="629" cy="130" r="6" class="s-red"/><path d="M629 124v-34" class="l-line w1"/><text x="640" y="84" text-anchor="middle" class="t-s t-b">Swift</text></g>
</svg>
:::

## Karşılaştırma tablosu

| Dil | Çıkış yılı | Seviye | Çalışma biçimi | Tip sistemi | Bellek yönetimi | Başlıca kullanım alanı |
|---|---|---|---|---|---|---|
| Assembly | 1949 | Çok düşük | Makine koduna çevrilir | Yok | Elle | Çekirdek, gömülü sistemler |
| C | 1972 | Düşük | Derlenir | Statik, zayıf | Elle | İşletim sistemleri, gömülü |
| C++ | 1985 | Düşük–orta | Derlenir | Statik | Elle (akıllı işaretçiler) | Oyun motorları, tarayıcılar |
| SQL | 1974 | Yüksek | Veritabanı yorumlar | Statik (şema) | Otomatik | Veri sorgulama |
| Haskell | 1990 | Yüksek | Derlenir | Statik, güçlü | Çöp toplayıcı | Araştırma, derleyiciler |
| Python | 1991 | Yüksek | Yorumlanır (bayt kodu) | Dinamik, güçlü | Çöp toplayıcı | Veri bilimi, yapay zekâ, otomasyon |
| R | 1993 | Yüksek | Yorumlanır | Dinamik | Çöp toplayıcı | İstatistik |
| Lua | 1993 | Yüksek | Yorumlanır (bayt kodu) | Dinamik | Çöp toplayıcı | Gömülü betikler, oyunlar |
| Java | 1995 | Yüksek | Bayt kodu + JIT | Statik | Çöp toplayıcı | Kurumsal sistemler, Android |
| JavaScript | 1995 | Yüksek | JIT | Dinamik, zayıf | Çöp toplayıcı | Web ön yüzü ve arka ucu |
| PHP | 1995 | Yüksek | Yorumlanır (bayt kodu + JIT) | Dinamik | Çöp toplayıcı | Web arka ucu |
| Ruby | 1995 | Yüksek | Yorumlanır (bayt kodu + JIT) | Dinamik, güçlü | Çöp toplayıcı | Web uygulamaları |
| C# | 2000 | Yüksek | Bayt kodu + JIT | Statik | Çöp toplayıcı | Kurumsal, oyun (Unity) |
| Go | 2009 | Orta | Derlenir | Statik | Çöp toplayıcı | Bulut servisleri, araçlar |
| Rust | 2010 (1.0: 2015) | Düşük–orta | Derlenir | Statik, güçlü | Sahiplik | Sistem yazılımı, güvenlik |
| Kotlin | 2011 | Yüksek | Bayt kodu + JIT | Statik | Çöp toplayıcı | Android |
| Dart | 2011 | Yüksek | JIT ve ön derleme | Statik | Çöp toplayıcı | Flutter uygulamaları |
| TypeScript | 2012 | Yüksek | JavaScript'e çevrilir | Statik | Çöp toplayıcı | Büyük web projeleri |
| Swift | 2014 | Yüksek | Derlenir | Statik, güçlü | Otomatik başvuru sayımı | iOS ve macOS uygulamaları |

## Hangi dil ne kadar kullanılıyor?

Stack Overflow'un yıllık geliştirici anketi, on binlerce geliştiricinin son bir yılda hangi dillerle çalıştığını sorar. Anket yalnızca katılanları temsil eder ve web geliştiricileri ağırlıktadır; yine de yaygınlık hakkında iyi bir fikir verir.

:::chart
{"type":"bar","horizontal":true,"title":"Geliştiricilerin son bir yılda kullandığı diller (tüm katılımcılar)","labels":["JavaScript","HTML/CSS","SQL","Python","Bash/Shell","TypeScript","Java","C#","C++","C","PHP","Go","Rust","Kotlin","Lua","Assembly","Ruby","Dart","Swift","R"],"datasets":[{"label":"Kullananların oranı","data":[66,61.9,58.6,57.9,48.7,43.6,29.4,27.8,23.5,22,18.9,16.4,14.8,10.8,9.2,7.1,6.4,5.9,5.4,4.9]}],"unit":"%","xTitle":"Katılımcıların yüzdesi","source":"Stack Overflow Developer Survey 2025","sourceUrl":"https://survey.stackoverflow.co/2025/technology","date":"2025","note":"Bir katılımcı birden çok dil seçebildiği için oranların toplamı 100'ü aşar."}
:::

Grafikte dikkat çeken üç nokta var. Birincisi, web'in dilleri (JavaScript, HTML/CSS, TypeScript) en üstte; çünkü hemen her ürünün bir web yüzü var. İkincisi, SQL neredeyse Python kadar yaygın; veri her yerde. Üçüncüsü, Rust ve Go gibi görece genç diller, onlarca yıllık PHP ile aynı banda yerleşmiş durumda.

## Hangisini seçmeliyim?

Dil seçimi, “en iyi dil hangisi” sorusuyla değil, “ne yapmak istiyorum” sorusuyla başlar.

| Hedefiniz | İyi bir başlangıç | Neden |
|---|---|---|
| Programlamayı sıfırdan öğrenmek | Python | Sade sözdizimi, anında sonuç, çok geniş kaynak |
| Web sitesi ve web uygulaması | JavaScript, ardından TypeScript | Tarayıcının dili; ön yüz ve arka uç aynı dille yazılabilir |
| Veri analizi ve yapay zekâ | Python (istatistik ağırlıklıysa R) | Kitaplıkların neredeyse tamamı Python'da |
| iOS uygulaması | Swift | Apple'ın resmî dili |
| Android uygulaması | Kotlin | Google'ın önerdiği dil |
| Tek kodla iOS ve Android | Dart (Flutter) veya TypeScript (React Native) | Tek kod tabanı |
| Oyun geliştirme | C# (Unity) veya C++ (Unreal) | Motorların kendi dilleri |
| Bulut servisleri, komut satırı araçları | Go | Basit dil, hızlı derleme, tek dosya |
| İşletim sistemi, sürücü, yüksek başarım | Rust veya C | Donanıma yakın, çöp toplayıcısız |
| Paylaşımlı barındırmada web sitesi | PHP | Her barındırma paketinde hazır |
| Veritabanıyla çalışmak | SQL | Tüm ilişkisel veritabanlarının ortak dili |

:::tip İlk dil o kadar da önemli değil
Değişkenler, döngüler, fonksiyonlar ve veri yapıları her dilde aynı fikirlerdir. Bir dili iyi öğrenen kişi ikincisini haftalar içinde, üçüncüsünü günler içinde öğrenir. Önemli olan bir dil seçip onunla gerçek bir şey bitirmektir.
:::

## Sık sorulan sorular

:::faq
Q: En hızlı programlama dili hangisi?
A: Tek bir yanıt yok, ama C, C++ ve Rust gibi doğrudan makine koduna derlenen ve çöp toplayıcı kullanmayan diller genellikle en hızlı sonuçları verir. Yine de bir programın hızını çoğu zaman dil değil, seçilen algoritma ve veri yapıları belirler.
Q: Python yavaşsa neden yapay zekâda bu kadar yaygın?
A: Çünkü ağır hesaplama Python'da yapılmaz. NumPy, PyTorch ve TensorFlow gibi kitaplıkların çekirdeği C, C++ ve CUDA ile yazılmıştır; Python yalnızca bu hızlı kodu yöneten okunaklı bir üst katmandır.
Q: HTML ve CSS programlama dili midir?
A: Hayır. HTML bir işaretleme dili, CSS bir biçimlendirme dilidir; koşul, döngü ve genel hesaplama yetenekleri yoktur. Ama web geliştirmenin ayrılmaz parçalarıdır.
Q: Java ile JavaScript aynı şey mi?
A: Hayır, adları dışında ortak yanları çok azdır. Java statik tipli, bayt koduna derlenen bir dildir; JavaScript dinamik tipli ve tarayıcıda çalışan bir dildir. Ad benzerliği 1995'teki bir pazarlama kararından kalmadır.
Q: Derlenen dil her zaman yorumlanan dilden hızlı mıdır?
A: Genellikle evet, ama fark her zaman önemli değildir. JIT derleyiciler arayı ciddi ölçüde kapatmıştır ve ağ ya da veritabanı bekleyen bir programda dilin hızı çoğu zaman darboğaz olmaz.
Q: Kaç dil öğrenmeliyim?
A: Önce birinde gerçekten yetkin olun. Sonra farklı bir düşünme biçimi sunan ikinci bir dil (örneğin Python'dan sonra Rust ya da SQL) ufkunuzu en çok genişleten adım olur.
Q: Yapay zekâ kod yazarken dil öğrenmek hâlâ gerekli mi?
A: Evet. Yapay zekâ araçları kod üretmeyi hızlandırır, ama üretilen kodu okuyup doğruluğunu değerlendirmek, hatayı bulmak ve mimari karar vermek hâlâ dili ve temel kavramları bilmeyi gerektirir.
:::

## Özet

- Programlama dilleri donanıma yakınlıklarına göre düşük ve yüksek seviyeli olarak ayrılır; seviye bir kalite ölçüsü değildir.
- Kod ya önceden derlenir (C, Rust, Go), ya çalışırken yorumlanır (Python, Ruby), ya da bayt koduna çevrilip JIT ile hızlandırılır (Java, C#, JavaScript).
- Tip sistemi (statik/dinamik) hataların ne zaman yakalanacağını, bellek yönetimi (elle, çöp toplayıcı, sahiplik) ise hız ile güvenlik arasındaki dengeyi belirler.
- Her dilin güçlü olduğu bir alan vardır: web için JavaScript/TypeScript, veri için Python ve SQL, sistem yazılımı için C ve Rust, mobil için Swift ve Kotlin.
- Doğru dil, yapmak istediğiniz işe göre seçilir; kavramlar ise diller arasında taşınır.

## Kaynaklar

- [Stack Overflow Developer Survey 2025 – Technology](https://survey.stackoverflow.co/2025/technology)
- [The C Programming Language tarihçesi – Dennis Ritchie, “The Development of the C Language”](https://www.bell-labs.com/usr/dmr/www/chist.html)
- [Python Software Foundation – General Python FAQ](https://docs.python.org/3/faq/general.html)
- [The Rust Programming Language – “Understanding Ownership”](https://doc.rust-lang.org/book/ch04-00-understanding-ownership.html)
- [The Go Programming Language – FAQ](https://go.dev/doc/faq)
- [MDN Web Docs – JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/handbook/intro.html)
- [Oracle – The Java Language Environment (white paper)](https://www.oracle.com/java/technologies/language-environment.html)
- [Swift.org – About Swift](https://www.swift.org/about/)
- [Kotlin Documentation](https://kotlinlang.org/docs/home.html)
- [Haskell.org](https://www.haskell.org/)
- [Lua – About](https://www.lua.org/about.html)
