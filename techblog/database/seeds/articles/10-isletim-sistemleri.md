---
title: Every Operating System Explained: Windows'tan Linux'a, Android'den RTOS'a
slug: every-operating-system-explained
category: yazilim
tags: işletim sistemi, windows, linux, macos, android, çekirdek
description: Windows, macOS, Linux dağıtımları, ChromeOS, Android, iOS, HarmonyOS, KaiOS, FreeBSD ve gerçek zamanlı sistemler: çekirdek mimarileri ve kullanım alanları.
excerpt: İşletim sistemi tam olarak ne iş yapar, çekirdek nedir, Linux ile dağıtım arasındaki fark ne? Windows, macOS, Linux, ChromeOS, Android, iOS, HarmonyOS, KaiOS, FreeBSD ve gömülü sistemleri çekirdek mimarileri ve kullanım alanlarıyla anlatıyoruz.
cover: /assets/covers/operating-systems.svg
cover_alt: Üst üste dizilmiş uygulama, çekirdek ve donanım katmanlarını gösteren pencereli çizim
series: Every X Explained
series_order: 10
date: 2026-10-29 09:00
---
Bilgisayarınızı açtığınızda karşınıza çıkan masaüstü, telefonunuzdaki ana ekran, arabanızın gösterge paneli, hatta çamaşır makinenizin program seçicisi… Hepsinin arkasında, donanım ile uygulamalar arasında duran bir yazılım katmanı vardır: **işletim sistemi**. Çoğu zaman onu yalnızca görünen yüzüyle, yani pencereler ve simgelerle tanırız. Asıl işi ise görünmeyen yerde yapar.

Bu yazıda önce işletim sisteminin ne yaptığını ve **çekirdek** kavramını açıklayacak, sonra masaüstünden cep telefonuna, sunucudan gömülü cihaza bütün büyük işletim sistemlerini tek tek ele alacağız.

## Temel kavramlar

### İşletim sistemi ne iş yapar?

Bir işletim sistemi dört temel görevi üstlenir:

- **Süreç yönetimi:** Aynı anda çalışan onlarca programa işlemci zamanını paylaştırır.
- **Bellek yönetimi:** Her programa kendi bellek alanını verir ve programların birbirinin belleğine dokunmasını engeller.
- **Dosya sistemi:** Diskteki ham blokları dosya ve klasörler hâlinde düzenler; kimin neyi okuyup yazabileceğini denetler.
- **Aygıt yönetimi:** Klavye, ekran, disk, ağ kartı gibi donanımlarla **sürücüler** aracılığıyla konuşur ve uygulamalara ortak bir arayüz sunar.

Bu sayede bir uygulama geliştiricisi, programının hangi marka disk ya da ekran kartıyla çalışacağını düşünmek zorunda kalmaz; işletim sistemine “şu dosyayı aç” der, gerisini sistem halleder.

### Çekirdek ve kullanıcı alanı

İşletim sisteminin kalbi **çekirdektir** (kernel). Çekirdek, donanıma doğrudan erişebilen ayrıcalıklı kipte çalışır. Uygulamalar ise **kullanıcı alanında**, kısıtlı bir kipte çalışır; donanıma doğrudan dokunamazlar. Bir uygulama dosya okumak, ağa veri göndermek ya da bellek istemek için çekirdekten **sistem çağrısı** ile hizmet ister.

Bu ayrım hem güvenliğin hem de kararlılığın temelidir: bir uygulama çökerse yalnızca kendisi kapanır; çekirdek ve diğer uygulamalar çalışmayı sürdürür.

:::svg Bir uygulama donanıma doğrudan erişemez; isteğini sistem çağrısıyla çekirdeğe iletir. Çekirdek işlemi yapar ve sonucu geri döndürür.
<svg viewBox="0 0 720 300" role="img" aria-label="Dört katman: en üstte uygulamalar, altında sistem çağrısı arayüzü, altında çekirdek ve en altta donanım. Bir istek uygulamadan çekirdeğe, oradan diske iner ve yanıt aynı yoldan geri çıkar" data-dur="9">
<rect x="20" y="16" width="680" height="64" rx="10" class="s-bg3 l-fg w2"/><text x="40" y="42" class="t-b">Kullanıcı alanı</text>
<rect x="250" y="28" width="130" height="40" rx="8" class="s-blue"/><text x="315" y="53" text-anchor="middle" class="t-s t-w t-b">Metin düzenleyici</text><rect x="394" y="28" width="110" height="40" rx="8" class="s-bg2 l-fg w1"/><text x="449" y="53" text-anchor="middle" class="t-s">Tarayıcı</text><rect x="518" y="28" width="110" height="40" rx="8" class="s-bg2 l-fg w1"/><text x="573" y="53" text-anchor="middle" class="t-s">Oyun</text>
<path d="M20 98h680" class="l-acc2 w2 dash" fill="none"/><text x="40" y="116" class="t-s t-acc2 t-b">sistem çağrısı arayüzü: ayrıcalık sınırı</text>
<rect x="20" y="128" width="680" height="76" rx="10" class="s-soft l-acc w2"/><text x="40" y="156" class="t-b">Çekirdek</text>
<g class="s-bg2 l-acc w1"><rect x="180" y="146" width="110" height="40" rx="8"/><rect x="304" y="146" width="110" height="40" rx="8"/><rect x="428" y="146" width="120" height="40" rx="8"/><rect x="562" y="146" width="110" height="40" rx="8"/></g>
<text x="235" y="171" text-anchor="middle" class="t-s">süreçler</text><text x="359" y="171" text-anchor="middle" class="t-s">bellek</text><text x="488" y="171" text-anchor="middle" class="t-s">dosya sistemi</text><text x="617" y="171" text-anchor="middle" class="t-s">sürücüler</text>
<rect x="20" y="224" width="680" height="60" rx="10" class="s-bg3 l-fg w2"/><text x="40" y="258" class="t-b">Donanım</text><text x="300" y="258" class="t-s">işlemci · bellek · disk · ağ kartı · ekran</text>
<circle cx="315" cy="72" r="8" class="s-blue" data-a="route 0,40 173,90 173,170 .04 .4"/>
<text x="560" y="116" class="t-m" data-a="show .08 .45">open("rapor.txt")</text>
<circle cx="488" cy="240" r="8" class="s-acc" data-a="route 0,-70 -173,-150 -173,-176 .52 .9"/>
<text x="560" y="116" class="t-m t-acc" data-a="show .56 .96">→ dosya tanıtıcısı</text>
</svg>
:::

### Çekirdek mimarileri

Çekirdeğin içinde neyin bulunacağı, işletim sistemi tasarımının en eski tartışmasıdır.

- **Tek parça (monolitik) çekirdek:** Dosya sistemi, ağ yığını, sürücüler ve süreç yönetiminin tamamı çekirdeğin içinde, tek bir adres alanında çalışır. Bileşenler birbirini doğrudan çağırdığı için hızlıdır; ama hatalı bir sürücü bütün sistemi çökertebilir. **Linux** ve **FreeBSD** bu türdendir (modülleri çalışırken yükleyebilirler, ama modüller yine çekirdeğin içinde çalışır).
- **Mikroçekirdek:** Çekirdekte yalnızca en temel işlevler kalır: süreçler arası iletişim, temel zamanlama ve bellek yönetimi. Sürücüler ve dosya sistemi, kullanıcı alanında ayrı süreçler olarak çalışır. Bir sürücü çökerse yeniden başlatılır; sistem ayakta kalır. Bedeli, bileşenler arasındaki mesajlaşmanın getirdiği ek yüktür. **QNX**, **seL4** ve **HarmonyOS**'un kendi çekirdeği bu türdendir.
- **Melez (hibrit) çekirdek:** Mikroçekirdek yapısını benimser ama başarım için pek çok hizmeti çekirdek alanında tutar. **Windows NT** ailesi ve Apple'ın **XNU** çekirdeği böyle sınıflandırılır.

:::svg Tek parça çekirdekte hatalı bir sürücü bütün sistemi çökertebilir. Mikroçekirdekte sürücü ayrı bir süreçtir; çökerse yalnızca o yeniden başlatılır.
<svg viewBox="0 0 720 300" role="img" aria-label="Solda tek parça çekirdek: dosya sistemi, ağ ve sürücü aynı kutunun içindedir; sürücü çöktüğünde bütün kutu kırmızıya döner. Sağda mikroçekirdek: aynı bileşenler çekirdeğin dışında ayrı kutulardır; sürücü çöker, yeniden başlatılır ve diğerleri çalışmaya devam eder" data-dur="11">
<text x="180" y="26" text-anchor="middle" class="t-l">Tek parça çekirdek</text><text x="540" y="26" text-anchor="middle" class="t-l">Mikroçekirdek</text>
<path d="M360 10v280" class="l-line w1 dash" fill="none"/>
<rect x="60" y="50" width="240" height="40" rx="8" class="s-bg3 l-fg w2"/><text x="180" y="75" text-anchor="middle" class="t-s">uygulamalar</text>
<rect x="40" y="110" width="280" height="150" rx="12" class="s-soft l-acc w3"/><text x="180" y="134" text-anchor="middle" class="t-b">çekirdek</text>
<g class="s-bg2 l-acc w1"><rect x="56" y="150" width="120" height="40" rx="8"/><rect x="184" y="150" width="120" height="40" rx="8"/><rect x="56" y="200" width="120" height="40" rx="8"/><rect x="184" y="200" width="120" height="40" rx="8"/></g>
<text x="116" y="175" text-anchor="middle" class="t-s">dosya sistemi</text><text x="244" y="175" text-anchor="middle" class="t-s">ağ</text><text x="116" y="225" text-anchor="middle" class="t-s">zamanlayıcı</text><text x="244" y="225" text-anchor="middle" class="t-s">sürücü</text>
<rect x="184" y="200" width="120" height="40" rx="8" class="s-red" data-a="show .15 .98"/><text x="244" y="225" text-anchor="middle" class="t-s t-w t-b" data-a="show .15 .98">sürücü ✕</text>
<g data-a="show .3 .98"><rect x="40" y="110" width="280" height="150" rx="12" class="s-none l-red w4"/><text x="180" y="284" text-anchor="middle" class="t-s t-red t-b">bütün sistem çöktü</text></g>
<rect x="420" y="50" width="240" height="40" rx="8" class="s-bg3 l-fg w2"/><text x="540" y="75" text-anchor="middle" class="t-s">uygulamalar</text>
<g class="s-bg2 l-fg w2"><rect x="380" y="110" width="100" height="44" rx="8"/><rect x="490" y="110" width="100" height="44" rx="8"/><rect x="600" y="110" width="100" height="44" rx="8"/></g>
<text x="430" y="137" text-anchor="middle" class="t-s">dosya sistemi</text><text x="540" y="137" text-anchor="middle" class="t-s">ağ</text><text x="650" y="137" text-anchor="middle" class="t-s">sürücü</text>
<rect x="600" y="110" width="100" height="44" rx="8" class="s-red" data-a="show .5 .72"/><text x="650" y="137" text-anchor="middle" class="t-s t-w t-b" data-a="show .5 .72">sürücü ✕</text>
<rect x="600" y="110" width="100" height="44" rx="8" class="s-acc" data-a="show .74 .98"/><text x="650" y="137" text-anchor="middle" class="t-s t-ink t-b" data-a="show .74 .98">yeniden başladı</text>
<path d="M430 154v36M540 154v36M650 154v36" class="l-mut w2" fill="none"/>
<rect x="400" y="190" width="280" height="56" rx="12" class="s-soft l-acc w3"/><text x="540" y="214" text-anchor="middle" class="t-b">mikroçekirdek</text><text x="540" y="234" text-anchor="middle" class="t-s">mesajlaşma · zamanlama · bellek</text>
<text x="540" y="284" text-anchor="middle" class="t-s t-acc t-b" data-a="show .74 .98">sistem çalışmaya devam ediyor</text>
</svg>
:::

### Çoklu görev ve zamanlama

Dört çekirdekli bir işlemcide yüzlerce süreç “aynı anda” çalışıyor gibi görünür. Bunu sağlayan **zamanlayıcıdır** (scheduler): her sürece işlemcide çok kısa bir zaman dilimi (birkaç milisaniye) verir, süresi dolunca durumunu kaydedip sıradaki sürece geçer. Bu geçişe **bağlam değişimi** denir ve o kadar hızlı yapılır ki insan gözüne her şey eşzamanlı görünür. Hangi sürecin ne zaman çalışacağına sürecin önceliğine ve ne kadar beklediğine bakılarak karar verilir.

:::svg Zamanlayıcı, tek bir işlemci çekirdeğini süreçler arasında kısa zaman dilimleriyle paylaştırır. Geçişler çok hızlı olduğu için hepsi aynı anda çalışıyor gibi görünür.
<svg viewBox="0 0 720 230" role="img" aria-label="Üç süreç: müzik çalar, tarayıcı ve indirme. Zaman ekseni boyunca işlemci sırayla her birine kısa dilimler ayırır; etkin olan sürecin dilimi renkli gösterilir" data-dur="8">
<text x="20" y="54" class="t-b">Müzik çalar</text><text x="20" y="108" class="t-b">Tarayıcı</text><text x="20" y="162" class="t-b">İndirme</text>
<path d="M150 190h550" class="l-mut w2" fill="none"/><text x="700" y="212" text-anchor="end" class="t-s">zaman →</text><text x="150" y="212" class="t-s">her dilim birkaç milisaniye</text>
<g class="s-line"><rect x="150" y="34" width="550" height="30" rx="6"/><rect x="150" y="88" width="550" height="30" rx="6"/><rect x="150" y="142" width="550" height="30" rx="6"/></g>
<g class="s-blue"><rect x="152" y="36" width="58" height="26" rx="5" data-a="show .02"/><rect x="335" y="36" width="58" height="26" rx="5" data-a="show .35"/><rect x="518" y="36" width="58" height="26" rx="5" data-a="show .68"/></g>
<g class="s-acc"><rect x="213" y="90" width="58" height="26" rx="5" data-a="show .13"/><rect x="396" y="90" width="58" height="26" rx="5" data-a="show .46"/><rect x="579" y="90" width="58" height="26" rx="5" data-a="show .79"/></g>
<g class="s-acc2"><rect x="274" y="144" width="58" height="26" rx="5" data-a="show .24"/><rect x="457" y="144" width="58" height="26" rx="5" data-a="show .57"/><rect x="640" y="144" width="58" height="26" rx="5" data-a="show .9"/></g>
<rect x="150" y="26" width="4" height="154" rx="2" class="s-fg" data-a="move 548 0 .02 .98"/>
</svg>
:::

### Unix mirası

Bugünkü işletim sistemlerinin çoğu, 1969'da Bell Laboratuvarları'nda doğan **Unix**'in doğrudan ya da dolaylı mirasçısıdır. Unix'in “her şey bir dosyadır” ve “tek bir işi iyi yapan küçük programlar” ilkeleri hâlâ yaşar. Unix'ten iki büyük kol çıktı: Berkeley'de geliştirilen **BSD** (FreeBSD ve dolaylı olarak macOS ile iOS) ve Unix'e benzeyen ama sıfırdan yazılan **Linux** (Android, ChromeOS ve dağıtımlar). Unix soyundan gelmeyen tek büyük aile **Windows**'tur.

## Masaüstü işletim sistemleri

### Windows

Microsoft Windows, masaüstü ve dizüstü bilgisayarların en yaygın işletim sistemidir. Tarihinde iki ayrı soy vardır.

İlk soy, **MS-DOS**'un üzerinde çalışan bir grafik arayüz olarak başladı: Windows 1.0 (1985), 3.1 (1992), ardından büyük ölçüde kendi başına bir sistem hâline gelen ama hâlâ DOS temelli olan Windows 95, 98 ve Me. Bu soy 16 ve 32 bit kodu karıştırıyordu ve kararlılığıyla ünlü değildi.

İkinci soy, 1993'te sıfırdan tasarlanan **Windows NT**'dir: bellek koruması, çok kullanıcılı güvenlik ve melez bir çekirdek getirdi. Önce iş dünyasına yönelikti (NT 4.0, Windows 2000). **Windows XP** (2001) ile iki soy birleşti; o günden beri bütün Windows sürümleri NT çekirdeğini kullanır.

| Sürüm | Yıl | Çekirdek soyu | Öne çıkan özellik |
|---|---|---|---|
| Windows 95 | 1995 | DOS tabanlı | Başlat menüsü ve görev çubuğu |
| Windows 98 / Me | 1998 / 2000 | DOS tabanlı | USB desteği, internetle bütünleşme |
| Windows 2000 | 2000 | NT | İş dünyası için kararlı NT |
| Windows XP | 2001 | NT | Ev ve iş sürümlerinin tek çekirdekte birleşmesi |
| Windows Vista | 2007 | NT | Kullanıcı Hesabı Denetimi, yeni sürücü modeli |
| Windows 7 | 2009 | NT | Vista'nın olgunlaşmış hâli |
| Windows 8 / 8.1 | 2012 / 2013 | NT | Dokunmatik odaklı arayüz |
| Windows 10 | 2015 | NT | “Hizmet olarak Windows”; desteği Ekim 2025'te sona erdi |
| Windows 11 | 2021 | NT | Yenilenen arayüz; TPM 2.0 ve güvenli önyükleme zorunluluğu |

Windows'un en büyük gücü uyumluluktur: çok geniş donanım desteği, on yıllar öncesinin yazılımlarını çalıştırabilme ve PC oyunlarının büyük çoğunluğu. Zayıf yanları, en çok hedef alınan platform olması nedeniyle kötü amaçlı yazılım riski ve son sürümlerde artan reklam, telemetri ve hesap zorunluluklarıdır. Sunucu tarafında **Windows Server**, kurumsal ağlarda Active Directory ile yaygındır.

### macOS

Apple'ın Mac bilgisayarları için geliştirdiği macOS, 2001'de **Mac OS X** adıyla çıktı ve klasik Mac OS'un (1984) yerini aldı. Adı 2012'de OS X, 2016'da macOS oldu. Temelinde açık kaynaklı **Darwin** sistemi ve onun **XNU** çekirdeği bulunur. XNU, Mach mikroçekirdeği ile BSD bileşenlerini birleştiren melez bir çekirdektir; macOS bu sayede sertifikalı bir Unix'tir.

macOS yalnızca Apple donanımında çalışır. Bu, donanım ile yazılımın birlikte tasarlanmasını sağlar: 2020'de Intel işlemcilerden Apple'ın kendi ARM tabanlı işlemcilerine geçiş, pil ömrü ve başarımda büyük kazanım getirdi. Sürümler uzun yıllar Kaliforniya'daki yerlerin adlarını ve artan numaraları taşıdı; Apple 2025'te bütün sistemlerinde sürüm numarasını yıla göre adlandırmaya geçti (macOS 26).

Yaratıcı işlerde (video, müzik, tasarım) ve yazılım geliştirmede yaygındır. Zayıf yanları donanımın fiyatı, yükseltme olanağının sınırlılığı ve oyun kataloğunun Windows'a göre dar olmasıdır.

### Linux ve dağıtımlar

**Linux**, tek başına bir işletim sistemi değil, bir **çekirdektir**. Linus Torvalds onu 1991'de bir öğrenci projesi olarak yazmaya başladı ve GPL lisansıyla açık kaynak olarak yayımladı. Kullanılabilir bir sistem için çekirdeğin yanına bir kabuk, sistem araçları (çoğu GNU projesinden), bir paket yöneticisi ve genellikle bir masaüstü ortamı gerekir. Bunların bir araya getirilmiş hâline **dağıtım** (distro) denir.

| Dağıtım | Temel | Paket yöneticisi | Kime uygun |
|---|---|---|---|
| Debian | Bağımsız (1993) | apt | Kararlılık arayan sunucular ve deneyimli kullanıcılar |
| Ubuntu | Debian | apt | Yeni başlayanlar, masaüstü ve bulut sunucuları |
| Linux Mint | Ubuntu | apt | Windows'tan geçenler |
| Fedora | Bağımsız (Red Hat destekli) | dnf | Yeni teknolojileri erken isteyen geliştiriciler |
| Red Hat Enterprise Linux | Fedora | dnf | Ticari destekli kurumsal sunucular |
| Arch Linux | Bağımsız | pacman | Sistemi parça parça kendisi kurmak isteyenler |
| openSUSE | Bağımsız | zypper | Kurumsal ve masaüstü |
| Alpine | Bağımsız | apk | Çok küçük kapsayıcı (container) imajları |

Dağıtımlar arasındaki başlıca farklar paket yöneticisi, güncelleme sıklığı (sabit sürümler ya da sürekli güncellenen “rolling” model) ve varsayılan masaüstü ortamıdır (GNOME, KDE Plasma ve diğerleri).

Linux masaüstünde küçük bir paya sahiptir, ama başka her yerde egemendir: web sunucularının ve bulut altyapısının büyük bölümü, dünyanın en hızlı 500 süper bilgisayarının tamamı, yönlendiriciler, akıllı televizyonlar ve Android telefonlar Linux çekirdeğiyle çalışır.

### ChromeOS

Google'ın 2011'de ilk cihazlarıyla piyasaya sürdüğü ChromeOS, Linux çekirdeği üzerine kurulu ve arayüzü Chrome tarayıcısının çevresinde tasarlanmış bir sistemdir. Chromebook adlı uygun fiyatlı dizüstülerde çalışır. Güçlü yanları sadelik, hızlı açılış, otomatik güncelleme ve her sekmenin korumalı alanda çalıştığı güvenlik modelidir; bu yüzden okullarda çok yaygındır. Android uygulamalarını ve bir kapsayıcı içinde Linux uygulamalarını da çalıştırabilir. Zayıf yanı, profesyonel masaüstü yazılımlarının çoğunun bulunmamasıdır.

## Mobil işletim sistemleri

### Android

Google'ın geliştirdiği Android'i taşıyan ilk telefon 2008'de çıktı. Android, **Linux çekirdeği** üzerine kuruludur, ama alışılmış bir Linux dağıtımı değildir: kendine ait bir C kitaplığı, uygulamaları çalıştıran **ART** çalışma zamanı ve kendine özgü bir uygulama modeli vardır. Uygulamalar çoğunlukla Kotlin ve Java ile yazılır; her uygulama kendi korumalı alanında, ayrı bir kullanıcı kimliğiyle çalışır.

Çekirdek sistem, **AOSP** (Android Açık Kaynak Projesi) adıyla açık kaynaktır. Samsung, Xiaomi ve diğer üreticiler onu alıp kendi arayüzlerini ekler. Google'ın uygulama mağazası ve servisleri ise ayrı ve kapalı bir pakettir. Bu açıklık Android'i dünyanın en yaygın işletim sistemi yapmış, ama aynı zamanda **parçalanmaya** yol açmıştır: güncellemeler üreticiye ve modele bağlıdır; pek çok cihaz yeni sürümleri geç alır ya da hiç almaz.

### iOS ve iPadOS

Apple, iPhone'un işletim sistemini 2007'de tanıttı. iOS, macOS ile aynı **XNU** çekirdeğini ve Darwin temelini paylaşır; üzerine dokunmatik ekran için tasarlanmış ayrı bir arayüz katmanı kurulmuştur. 2019'da iPad sürümü, çoklu pencere ve kalem gibi özelliklerle **iPadOS** adıyla ayrıldı.

iOS kapalı bir ekosistemdir: yalnızca Apple donanımında çalışır ve uygulamalar esas olarak App Store üzerinden, Apple'ın incelemesinden geçerek dağıtılır. Avrupa Birliği'nde Dijital Pazarlar Yasası sonrasında başka uygulama mağazalarına izin verilmiştir. Bu sıkı denetimin karşılığı, uzun yıllar süren ve bütün cihazlara aynı gün ulaşan güncellemeler ile tutarlı bir güvenlik düzeyidir.

### HarmonyOS

Huawei, ABD yaptırımları nedeniyle Google servislerine erişimini yitirdikten sonra 2019'da HarmonyOS'u duyurdu. İlk sürümler Android'in açık kaynak kodunu içeriyor ve Android uygulamalarını çalıştırabiliyordu. 2024'te tanıtılan **HarmonyOS NEXT** ile Android uyumluluğu bırakıldı: sistem artık Huawei'nin kendi mikroçekirdeğine dayanıyor ve yalnızca bu platform için yazılmış uygulamaları çalıştırıyor. Telefon, tablet, saat, televizyon ve araç ekranlarında tek bir sistem olarak çalışmak üzere tasarlanmıştır; ağırlıklı olarak Çin pazarında yaygındır.

### KaiOS

KaiOS, tuşlu telefonlar için tasarlanmış hafif bir işletim sistemidir. Mozilla'nın sonlandırdığı Firefox OS'un kodundan türemiştir; Linux çekirdeği kullanır ve uygulamaları web teknolojileriyle (HTML, CSS, JavaScript) yazılır. Çok az bellekle çalışır ve uzun pil ömrü sunar; dokunmatik ekranı olmayan ucuz telefonlara harita, mesajlaşma ve video gibi akıllı telefon özelliklerini getirir. Hindistan ve Afrika'da yaygınlaşmıştır.

## Sunucu ve özel amaçlı sistemler

### FreeBSD

1993'te yayımlanan FreeBSD, Berkeley Unix'inin (BSD) doğrudan soyundan gelen açık kaynaklı bir işletim sistemidir. Linux'tan önemli bir farkı vardır: FreeBSD yalnızca bir çekirdek değil, çekirdek ile temel sistem araçlarının **tek bir proje** olarak birlikte geliştirildiği bütün bir sistemdir. Lisansı da farklıdır: BSD lisansı, kodu alıp değiştirenlerin değişikliklerini paylaşmasını zorunlu kılmaz.

Bu serbestlik sayesinde FreeBSD kodu pek çok ticari ürünün içindedir: PlayStation konsollarının sistem yazılımı FreeBSD tabanlıdır, Netflix video dağıtım sunucularında FreeBSD kullanır, macOS da BSD'den gelen pek çok bileşen içerir. Güçlü ağ yığını ve ZFS dosya sistemi desteğiyle sunucu, depolama ve güvenlik duvarı cihazlarında tercih edilir. Aynı aileden **OpenBSD** güvenliğe, **NetBSD** ise çok sayıda donanım mimarisine taşınabilirliğe odaklanır.

### Gömülü sistemler ve RTOS

Bir mikrodalga fırının, bir kalp pilinin ya da bir otomobil fren denetleyicisinin içinde de bir bilgisayar ve çoğu zaman bir işletim sistemi vardır. Bu **gömülü sistemler** tek bir işi yapar, çok kısıtlı bellek ve güçle çalışır ve çoğu zaman bir ekranı bile yoktur.

Bunların önemli bir bölümü **gerçek zamanlı işletim sistemi** (RTOS) kullanır. Gerçek zamanlı, “çok hızlı” demek değildir; **öngörülebilir** demektir: sistem, bir olaya belirli bir süre içinde yanıt vereceğini garanti eder.

- **Katı gerçek zamanlı** sistemlerde sürenin aşılması bir arızadır: hava yastığı denetleyicisi, uçuş kontrol sistemi, kalp pili.
- **Esnek gerçek zamanlı** sistemlerde gecikme kaliteyi düşürür ama felakete yol açmaz: video oynatma, sesli görüşme.

Genel amaçlı işletim sistemleri ortalama başarımı en iyilemeye çalışır; bir süreç birkaç milisaniye geç çalışırsa sorun olmaz. RTOS ise en kötü durumu sınırlar: en yüksek öncelikli görev, hazır olduğu anda işlemciyi her zaman alır.

| RTOS | Lisans | Nerede kullanılır |
|---|---|---|
| FreeRTOS | Açık kaynak (MIT) | Mikrodenetleyiciler, nesnelerin interneti cihazları |
| Zephyr | Açık kaynak (Apache 2.0) | Giyilebilir cihazlar, sensörler |
| VxWorks | Ticari | Havacılık, uzay araçları, endüstriyel denetim |
| QNX | Ticari | Otomobil gösterge ve bilgi-eğlence sistemleri, tıbbi cihazlar |
| RT-Thread | Açık kaynak | Tüketici elektroniği |

Çok küçük cihazlarda ise hiç işletim sistemi bulunmaz; program doğrudan donanımın üzerinde, sonsuz bir döngü içinde çalışır. Buna **çıplak donanım** (bare metal) programlama denir.

## Pazar payları

:::chart
{"type":"bar","horizontal":true,"title":"Dünya genelinde işletim sistemi pazar payı (tüm platformlar)","labels":["Android","Windows","iOS","macOS (OS X dâhil)","Linux"],"datasets":[{"label":"Pazar payı","data":[44.56,29.08,17.45,5.94,2.15]}],"unit":"%","xTitle":"Sayfa görüntülemelerindeki pay (%)","source":"StatCounter Global Stats","sourceUrl":"https://gs.statcounter.com/os-market-share","date":"Ekim 2026","note":"StatCounter'ın ayrı raporladığı “OS X” (%3,74) ve “macOS” (%2,2) değerleri toplanmıştır. Ölçüm web trafiğine dayanır; sunucular ve gömülü cihazlar kapsam dışıdır (yaklaşık)."}
:::

:::chart
{"type":"doughnut","title":"Masaüstü işletim sistemi pazar payı","labels":["Windows","macOS (OS X dâhil)","Linux","ChromeOS"],"datasets":[{"label":"Pay","data":[76.65,15.67,5.63,2.04]}],"unit":"%","source":"StatCounter Global Stats","sourceUrl":"https://gs.statcounter.com/os-market-share/desktop/worldwide","date":"Ekim 2026","note":"“OS X” (%9,86) ve “macOS” (%5,81) değerleri toplanmıştır (yaklaşık)."}
:::

İki grafik birlikte okununca önemli bir gerçek ortaya çıkar: Windows masaüstünde açık ara lider olsa da, bütün cihazlar birlikte sayıldığında en yaygın işletim sistemi Android'dir. Çekirdek düzeyinde bakarsak tablo daha da çarpıcıdır: Android, ChromeOS ve masaüstü Linux'un tamamı Linux çekirdeğini kullanır; iOS ile macOS ise aynı XNU çekirdeğini paylaşır.

## Büyük karşılaştırma tablosu

| İşletim sistemi | Çekirdek | Çekirdek türü | Kaynak modeli | Başlıca kullanım alanı |
|---|---|---|---|---|
| Windows 11 | Windows NT | Melez | Kapalı | Masaüstü, oyun, kurumsal |
| macOS | XNU (Mach + BSD) | Melez | Çekirdek açık, sistem kapalı | Yaratıcı işler, yazılım geliştirme |
| Linux dağıtımları | Linux | Tek parça (modüler) | Açık (GPL) | Sunucu, bulut, geliştirici masaüstü |
| ChromeOS | Linux | Tek parça | Çekirdeği açık (ChromiumOS) | Eğitim, web odaklı dizüstüler |
| Android | Linux | Tek parça | Çekirdeği açık (AOSP), Google servisleri kapalı | Telefon, tablet, televizyon |
| iOS / iPadOS | XNU | Melez | Kapalı | iPhone ve iPad |
| HarmonyOS NEXT | HarmonyOS çekirdeği | Mikroçekirdek | Çekirdeği açık (OpenHarmony) | Huawei cihazları |
| KaiOS | Linux | Tek parça | Kısmen açık | Tuşlu telefonlar |
| FreeBSD | FreeBSD | Tek parça (modüler) | Açık (BSD) | Sunucu, ağ ve depolama cihazları |
| QNX | QNX Neutrino | Mikroçekirdek | Ticari | Otomotiv, tıbbi cihazlar |
| FreeRTOS | FreeRTOS | Gerçek zamanlı çekirdek | Açık (MIT) | Mikrodenetleyiciler |

## Hangisini seçmeliyim?

| İhtiyacınız | Makul seçim | Neden |
|---|---|---|
| Oyun ve en geniş yazılım uyumluluğu | Windows | PC oyunlarının ve masaüstü yazılımlarının çoğu burada |
| Video, müzik, tasarım; iPhone ile bütünlük | macOS | Güçlü yaratıcı yazılımlar, uzun pil ömrü |
| Yazılım geliştirme, sunucu yönetimi | Linux (Ubuntu ya da Fedora) | Sunucularla aynı ortam, ücretsiz, özelleştirilebilir |
| Eski bir bilgisayarı canlandırmak | Hafif bir Linux dağıtımı (Linux Mint) | Düşük kaynak tüketimi |
| Çocuk ya da öğrenci için sade bir dizüstü | ChromeOS | Bakım gerektirmez, güvenli, ucuz |
| Telefon: seçenek ve fiyat çeşitliliği | Android | Her bütçeye cihaz, özelleştirme |
| Telefon: uzun güncelleme desteği ve bütünlük | iOS | Bütün cihazlara aynı anda güncelleme |
| Web sunucusu | Linux (Debian, Ubuntu Server) ya da FreeBSD | Kararlı, ücretsiz, yaygın belgelenmiş |
| Gömülü ve zaman kritik cihaz | Bir RTOS (FreeRTOS, Zephyr, QNX) | Öngörülebilir yanıt süresi |

Tek birini seçmek zorunda da değilsiniz. **Sanal makineler** bir işletim sistemini bir başkasının içinde pencere olarak çalıştırır. Windows'taki **WSL**, Linux'u Windows'un içinde yerel hıza yakın çalıştırır. **Çift önyükleme** ise aynı bilgisayara iki sistem kurup açılışta seçmenizi sağlar.

## Sık sorulan sorular

:::faq
Q: Linux bir işletim sistemi mi, çekirdek mi?
A: Dar anlamıyla yalnızca çekirdektir. Gündelik kullanımda ise çekirdeğin yanına sistem araçları, paket yöneticisi ve masaüstü eklenerek oluşturulan dağıtımların tamamına Linux denir.
Q: Android Linux mudur?
A: Android, Linux çekirdeğini kullanır; bu anlamda evet. Ancak çekirdeğin üzerindeki katmanlar (C kitaplığı, çalışma zamanı, uygulama modeli) alışılmış Linux dağıtımlarından tümüyle farklıdır; bu yüzden masaüstü Linux programları Android'de doğrudan çalışmaz.
Q: 32 bit ile 64 bit işletim sistemi arasındaki fark nedir?
A: 32 bit bir sistem en fazla yaklaşık 4 GB belleği adresleyebilir; 64 bit sistemlerde bu sınır pratikte yoktur. Bugünkü işlemci ve işletim sistemlerinin neredeyse tamamı 64 bittir; Windows 11'in 32 bit sürümü yoktur.
Q: macOS'u Apple olmayan bir bilgisayara kurabilir miyim?
A: Apple'ın lisansı macOS'un yalnızca Apple donanımında çalıştırılmasına izin verir. Apple'ın kendi işlemcilerine geçmesiyle birlikte bu, teknik olarak da giderek zorlaşmıştır.
Q: Windows 10 desteği bittiyse kullanmaya devam edebilir miyim?
A: Sistem çalışmayı sürdürür, ama Ekim 2025'ten sonra standart güvenlik güncellemeleri sona ermiştir. Güncelleme almayan bir sistemi internete bağlı kullanmak zamanla riskli hâle gelir. Seçenekler Windows 11'e geçmek, ücretli uzatılmış güvenlik güncellemeleri almak ya da bir Linux dağıtımı kurmaktır.
Q: Linux'ta virüs olmaz mı?
A: Olur, ama masaüstü kullanıcılarını hedefleyen kötü amaçlı yazılım Windows'a göre çok daha azdır. Bunun nedeni hem küçük masaüstü payı hem de yazılımların çoğunlukla güvenilir depolardan kurulmasıdır. Linux sunucular ise sık sık saldırı hedefidir; güncel tutmak her sistemde şarttır.
Q: Gerçek zamanlı işletim sistemi daha mı hızlıdır?
A: Hayır, daha öngörülebilirdir. RTOS, bir göreve belirli bir süre içinde yanıt vermeyi garanti eder. Toplam iş hacmi açısından genel amaçlı bir işletim sistemi çoğu zaman daha verimlidir.
:::

## Özet

- İşletim sistemi; süreçleri, belleği, dosyaları ve aygıtları yönetir ve uygulamalar ile donanım arasında durur.
- Çekirdek ayrıcalıklı kipte çalışır; uygulamalar ondan sistem çağrılarıyla hizmet ister.
- Tek parça çekirdek hızlı, mikroçekirdek dayanıklıdır; Windows ve macOS melez yaklaşımı kullanır.
- Windows bugün NT soyundandır; macOS ve iOS aynı XNU çekirdeğini, Android ve ChromeOS ise Linux çekirdeğini kullanır.
- Linux bir çekirdektir; dağıtımlar onu paket yöneticisi ve araçlarla bütün bir sistem hâline getirir.
- Masaüstünde Windows, bütün cihazlarda Android, sunucularda Linux en yaygın sistemdir.
- Gerçek zamanlı sistemler hız değil, yanıt süresinde öngörülebilirlik sağlar.

## Kaynaklar

- [StatCounter Global Stats – Operating System Market Share Worldwide](https://gs.statcounter.com/os-market-share)
- [StatCounter Global Stats – Desktop Operating System Market Share Worldwide](https://gs.statcounter.com/os-market-share/desktop/worldwide)
- [Silberschatz, Galvin, Gagne – Operating System Concepts, 10. baskı](https://www.os-book.com/OS10/)
- [Arpaci-Dusseau – Operating Systems: Three Easy Pieces (ücretsiz çevrim içi kitap)](https://pages.cs.wisc.edu/~remzi/OSTEP/)
- [The Linux Kernel Archives](https://www.kernel.org/)
- [Microsoft Learn – Windows yaşam döngüsü ve Windows 10 desteğinin sona ermesi](https://learn.microsoft.com/en-us/lifecycle/products/windows-10-home-and-pro)
- [Microsoft Learn – Windows 11 sistem gereksinimleri](https://learn.microsoft.com/en-us/windows/whats-new/windows-11-requirements)
- [Apple Developer – Kernel Programming Guide: Kernel Architecture Overview](https://developer.apple.com/library/archive/documentation/Darwin/Conceptual/KernelProgramming/Architecture/Architecture.html)
- [Android Open Source Project – Platform mimarisi](https://source.android.com/docs/core/architecture)
- [The FreeBSD Project – About FreeBSD](https://www.freebsd.org/about/)
- [TOP500 – Operating system family istatistikleri](https://www.top500.org/statistics/list/)
- [FreeRTOS – What is an RTOS?](https://www.freertos.org/Documentation/01-FreeRTOS-quick-start/01-Beginners-guide/01-RTOS-fundamentals)
- [Zephyr Project belgeleri](https://docs.zephyrproject.org/latest/introduction/index.html)
- [OpenHarmony projesi](https://gitee.com/openharmony)
