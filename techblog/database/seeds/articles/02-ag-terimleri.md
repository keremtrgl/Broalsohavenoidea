---
title: Every Networking Term Explained: Ağ Terimlerinin Tamamı, Sade Bir Dille
slug: every-networking-term-explained
category: ag-ve-internet
tags: ağ, ip adresi, dns, tcp, vpn, osi modeli
description: IP, IPv4/IPv6, alt ağ, DNS, DHCP, NAT, port, TCP/UDP, OSI ve TCP/IP modelleri, VPN, vekil sunucu, güvenlik duvarı, gecikme ve bant genişliği: paket animasyonlarıyla.
excerpt: Bir web sayfasını açtığınızda arka planda neler olur? IP adresinden DNS'e, NAT'tan TCP el sıkışmasına, VPN'den ping'e kadar bütün ağ terimlerini paket akışı animasyonlarıyla açıklıyoruz.
cover: /assets/covers/networking-terms.svg
cover_alt: Birbirine çizgilerle bağlı bilgisayar, yönlendirici ve sunucu simgelerinden oluşan ağ çizimi
series: Every X Explained
series_order: 2
date: 2026-10-21 09:00
---
Tarayıcınıza bir adres yazıp Enter'a bastığınızda, sayfa ekranınıza gelene kadar geçen yarım saniyede onlarca ağ kavramı devreye girer: cihazınız bir IP adresi kullanır, DNS alan adını çözer, yönlendiriciniz NAT yapar, TCP bağlantı kurar, veriler paketlere bölünüp yola çıkar. Bu yazıda o yolculuğun her adımındaki terimi sırayla açıklayacağız.

Terimleri alfabetik değil, bir paketin izlediği sıraya yakın bir düzende ele alıyoruz. Böylece her kavram bir öncekinin üzerine oturur.

## Temel kavramlar: ağ, paket ve protokol

**Ağ**, birbirine veri gönderebilen cihazlar topluluğudur. Evinizdeki cihazların oluşturduğu küçük ağa **LAN** (yerel alan ağı), şehirleri ve ülkeleri birbirine bağlayan geniş ağlara **WAN** (geniş alan ağı) denir. İnternet, bu ağların birbirine bağlanmasıyla oluşan “ağların ağı”dır.

Ağda veri tek parça gönderilmez; **paket** adı verilen küçük parçalara bölünür. Her paketin üzerinde, bir mektup zarfı gibi, gönderenin ve alıcının adresi yazar. Paketler hedefe farklı yollardan gidebilir ve varış noktasında yeniden birleştirilir. Bir paket yolda kaybolursa yalnızca o parça yeniden gönderilir.

**Protokol**, cihazların birbiriyle nasıl konuşacağını belirleyen kurallar bütünüdür. Paketin biçimi, kimin ne zaman konuşacağı, hata olursa ne yapılacağı protokolde yazar. Bu yazıda geçen IP, TCP, UDP, DNS, DHCP ve HTTP'nin hepsi birer protokoldür.

## Adresleme

### IP adresi

**IP adresi** (Internet Protocol address), ağdaki bir cihazın sayısal adresidir. Paketler bu adrese bakılarak yönlendirilir. İki sürümü kullanımdadır:

- **IPv4** adresleri 32 bittir ve noktalarla ayrılmış dört sayı olarak yazılır: `192.168.1.10`. Her sayı 0–255 arasındadır. 32 bit yaklaşık 4,3 milyar farklı adres demektir; bu sayı dünyadaki cihazlar için çoktan yetersiz kalmıştır.
- **IPv6** adresleri 128 bittir ve iki nokta ile ayrılmış on altılık gruplar hâlinde yazılır: `2001:db8::8a2e:370:7334`. Adres sayısı 2¹²⁸'dir; yani yaklaşık 3,4 × 10³⁸. Pratikte tükenmesi mümkün değildir.

İki sürüm birbiriyle doğrudan konuşamaz; bu yüzden bugün çoğu ağ ikisini yan yana çalıştırır (**dual stack**).

| Özellik | IPv4 | IPv6 |
|---|---|---|
| Adres uzunluğu | 32 bit | 128 bit |
| Yazım | `192.168.1.10` | `2001:db8::8a2e:370:7334` |
| Adres sayısı | Yaklaşık 4,3 milyar (2³²) | Yaklaşık 3,4 × 10³⁸ (2¹²⁸) |
| NAT gereksinimi | Pratikte zorunlu | Gerekmez |
| Adres atama | DHCP veya elle | SLAAC, DHCPv6 veya elle |
| Standart | RFC 791 (1981) | RFC 8200 (2017; ilk tanım 1998) |

### Genel ve özel IP adresleri

Her cihaza internette geçerli, benzersiz bir **genel (public) IP** vermek IPv4'te mümkün olmadığı için bazı adres aralıkları **özel (private)** kullanıma ayrılmıştır. Bu adresler yalnızca yerel ağda geçerlidir ve internette yönlendirilmez:

- `10.0.0.0` – `10.255.255.255`
- `172.16.0.0` – `172.31.255.255`
- `192.168.0.0` – `192.168.255.255`

Evinizdeki telefon, bilgisayar ve televizyonun adresleri büyük olasılıkla `192.168.` ile başlar. Hepsi internete, internet sağlayıcınızın verdiği tek bir genel IP üzerinden çıkar. Bunu sağlayan mekanizma, aşağıda anlatacağımız NAT'tır.

Bir de `127.0.0.1` vardır: **localhost** ya da geri döngü adresi. Bu adrese gönderilen paket ağa hiç çıkmaz, aynı cihaza geri döner.

### Alt ağ (subnet) ve alt ağ maskesi

Bir IP adresi iki bölümden oluşur: **ağ bölümü** (hangi ağ) ve **cihaz bölümü** (o ağdaki hangi cihaz). Sınırın nerede olduğunu **alt ağ maskesi** söyler. `255.255.255.0` maskesi, ilk üç sayının ağı, son sayının cihazı gösterdiği anlamına gelir.

Aynı bilgi **CIDR** gösterimiyle daha kısa yazılır: `192.168.1.0/24`. Buradaki `/24`, adresin ilk 24 bitinin ağ bölümü olduğunu belirtir. Geriye cihazlar için 8 bit, yani 256 adres kalır; bunlardan ilki ağın kendisini, sonuncusu yayın adresini gösterdiği için 254'ü cihazlara verilebilir.

:::chart
{"type":"bar","title":"Alt ağ büyüklüğüne göre kullanılabilir cihaz adresi sayısı (IPv4)","labels":["/24","/25","/26","/27","/28","/29","/30"],"datasets":[{"label":"Kullanılabilir adres","data":[254,126,62,30,14,6,2]}],"xTitle":"CIDR öneki","yTitle":"Cihaz adresi","source":"RFC 4632 (CIDR); hesaplama: 2^(32 − önek) − 2","sourceUrl":"https://www.rfc-editor.org/rfc/rfc4632","date":"2006","note":"Önek bir bit uzadığında adres sayısı yarıya iner."}
:::

Alt ağlara bölmek, büyük bir ağı yönetilebilir ve güvenli parçalara ayırır: örneğin bir şirkette muhasebe, misafir Wi-Fi'ı ve güvenlik kameraları ayrı alt ağlarda tutulur.

### MAC adresi

**MAC adresi**, bir ağ kartına üretici tarafından verilen 48 bitlik donanım adresidir: `3C:5A:B4:1E:9F:02` gibi. IP adresi cihazın ağdaki konumunu, MAC adresi ise kartın kimliğini gösterir. Paketler yerel ağ içinde MAC adresine göre teslim edilir; ağlar arasında ise IP adresine göre yönlendirilir.

Bir cihaz, aynı ağdaki başka bir cihazın IP adresini bilip MAC adresini bilmediğinde **ARP** (Address Resolution Protocol) ile “bu IP kimde?” diye sorar; adresin sahibi MAC adresiyle yanıt verir.

### Ağ geçidi (gateway)

**Varsayılan ağ geçidi**, bir cihazın kendi alt ağı dışındaki bir adrese paket göndermek istediğinde paketi teslim ettiği cihazdır. Ev ağlarında bu, modem/yönlendiricinizdir ve adresi çoğunlukla `192.168.1.1` olur. Cihazınız “bu adres benim ağımda değil” dediği her paketi ağ geçidine bırakır; gerisini ağ geçidi halleder.

## Bağlantının kurulması

### DHCP

Bir ağa bağlandığınızda IP adresini elle girmezsiniz; çünkü **DHCP** (Dynamic Host Configuration Protocol) bunu sizin yerinize yapar. Cihazınız ağa “bana ayar verecek biri var mı?” diye seslenir; DHCP sunucusu (evde yine yönlendiriciniz) ona boşta bir IP adresi, alt ağ maskesi, ağ geçidi ve DNS sunucusu adresi **kiralar**. Kira süresi dolmadan cihaz kirayı yeniler.

### DNS

İnsanlar adları, bilgisayarlar sayıları hatırlar. **DNS** (Domain Name System), `ornek.com` gibi bir alan adını `203.0.113.5` gibi bir IP adresine çeviren, internetin telefon rehberidir.

Tarayıcınız bir adı ilk kez gördüğünde bir **DNS çözümleyiciye** (genellikle internet sağlayıcınızın ya da seçtiğiniz bir açık çözümleyicinin sunucusu) sorar. Çözümleyici yanıtı bilmiyorsa sırayla kök sunuculara, `.com` gibi üst düzey alan adı sunucularına ve son olarak alan adının yetkili sunucusuna sorarak adresi bulur. Sonuç belirli bir süre (**TTL**) önbellekte tutulur; aynı adı ikinci kez sorduğunuzda yanıt anında gelir.

:::svg Bir alan adının çözülmesi: çözümleyici sırayla kök, üst düzey alan adı (TLD) ve yetkili sunucuya sorar, bulduğu IP adresini tarayıcıya döndürür.
<svg viewBox="0 0 720 300" role="img" aria-label="DNS sorgusunun adımları: tarayıcı çözümleyiciye sorar; çözümleyici kök sunucuya, .com sunucusuna ve yetkili sunucuya sırayla sorarak IP adresini bulur ve tarayıcıya iletir" data-dur="12">
<rect x="20" y="120" width="120" height="64" rx="10" class="s-bg3 l-fg w2"/><text x="80" y="148" text-anchor="middle" class="t-b">Tarayıcı</text><text x="80" y="168" text-anchor="middle" class="t-s">ornek.com ?</text>
<rect x="250" y="120" width="140" height="64" rx="10" class="s-soft l-acc w2"/><text x="320" y="148" text-anchor="middle" class="t-b">Çözümleyici</text><text x="320" y="168" text-anchor="middle" class="t-s">önbelleğe bakar</text>
<rect x="540" y="20" width="160" height="56" rx="10" class="s-bg3 l-fg w2"/><text x="620" y="44" text-anchor="middle" class="t-b">Kök sunucu</text><text x="620" y="63" text-anchor="middle" class="t-s">“.com'a sor”</text>
<rect x="540" y="122" width="160" height="56" rx="10" class="s-bg3 l-fg w2"/><text x="620" y="146" text-anchor="middle" class="t-b">.com sunucusu</text><text x="620" y="165" text-anchor="middle" class="t-s">“yetkili sunucu şu”</text>
<rect x="540" y="224" width="160" height="56" rx="10" class="s-bg3 l-fg w2"/><text x="620" y="248" text-anchor="middle" class="t-b">Yetkili sunucu</text><text x="620" y="267" text-anchor="middle" class="t-s t-m">203.0.113.5</text>
<path d="M140 152h110M390 140 540 52M390 152h150M390 164l150 84" class="l-line w2" fill="none"/>
<circle cx="146" cy="152" r="8" class="s-acc" data-a="move 98 0 0 .1"/>
<circle cx="396" cy="138" r="8" class="s-blue" data-a="move 138 -84 .12 .22"/><circle cx="534" cy="54" r="8" class="s-acc2" data-a="move -138 84 .24 .34"/>
<circle cx="396" cy="152" r="8" class="s-blue" data-a="move 138 0 .36 .46"/><circle cx="534" cy="152" r="8" class="s-acc2" data-a="move -138 0 .48 .58"/>
<circle cx="396" cy="166" r="8" class="s-blue" data-a="move 138 82 .6 .7"/><circle cx="534" cy="248" r="8" class="s-acc2" data-a="move -138 -82 .72 .82"/>
<circle cx="244" cy="152" r="8" class="s-acc" data-a="move -98 0 .84 .95"/>
<g data-a="show .9"><rect x="20" y="200" width="150" height="30" rx="6" class="s-acc"/><text x="95" y="220" text-anchor="middle" class="t-m t-ink">= 203.0.113.5</text></g>
<text x="20" y="26" class="t-s">mavi: soru · turuncu: yanıt</text>
</svg>
:::

### NAT

**NAT** (Network Address Translation), özel IP adresli cihazların tek bir genel IP üzerinden internete çıkmasını sağlar. Yönlendiriciniz, dışarı giden her paketin gönderen adresini kendi genel IP'siyle değiştirir ve hangi iç cihazın hangi bağlantıyı açtığını bir tabloya yazar. Yanıt geldiğinde tabloya bakar ve paketi doğru iç cihaza iletir.

NAT'ın bir yan etkisi vardır: dışarıdan içeriye, önceden açılmamış bir bağlantı kurulamaz. Evinizde bir oyun sunucusu ya da kamera yayınlamak istediğinizde yönlendiricide **port yönlendirme** yapmanız bu yüzden gerekir. İnternet sağlayıcınız size genel IP bile vermeyip kendi içinde ikinci bir NAT daha uyguluyorsa buna **CGNAT** denir; bu durumda port yönlendirme de çalışmaz.

:::svg NAT: bilgisayardan çıkan paketin gönderen adresi yönlendiricide genel IP ile değiştirilir; dönen yanıt tablodaki kayda bakılarak doğru cihaza iletilir.
<svg viewBox="0 0 720 260" role="img" aria-label="Özel adresli bir bilgisayardan çıkan paket yönlendiriciden geçerken gönderen adresi 192.168.1.10'dan genel IP 198.51.100.7'ye çevrilir ve sunucuya ulaşır; yanıt aynı yoldan geri döner" data-dur="10">
<rect x="20" y="90" width="130" height="80" rx="10" class="s-bg3 l-fg w2"/><text x="85" y="124" text-anchor="middle" class="t-b">Bilgisayar</text><text x="85" y="146" text-anchor="middle" class="t-m">192.168.1.10</text>
<rect x="285" y="80" width="150" height="100" rx="10" class="s-soft l-acc w2"/><text x="360" y="112" text-anchor="middle" class="t-b">Yönlendirici</text><text x="360" y="134" text-anchor="middle" class="t-s">NAT tablosu</text><text x="360" y="156" text-anchor="middle" class="t-m">198.51.100.7</text>
<rect x="570" y="90" width="130" height="80" rx="10" class="s-bg3 l-fg w2"/><text x="635" y="124" text-anchor="middle" class="t-b">Sunucu</text><text x="635" y="146" text-anchor="middle" class="t-m">203.0.113.5</text>
<path d="M150 130h135M435 130h135" class="l-line w2" fill="none"/>
<text x="85" y="72" text-anchor="middle" class="t-s">yerel ağ (özel IP)</text><text x="635" y="72" text-anchor="middle" class="t-s">internet (genel IP)</text>
<g data-a="move 100 0 .02 .2"><rect x="156" y="196" width="150" height="40" rx="6" class="s-blue"/><text x="231" y="213" text-anchor="middle" class="t-s t-w">gönderen:</text><text x="231" y="229" text-anchor="middle" class="t-m t-w">192.168.1.10</text></g>
<circle cx="158" cy="130" r="9" class="s-blue" data-a="move 120 0 .02 .2"/>
<g data-a="move 100 0 .25 .45"><rect x="420" y="196" width="150" height="40" rx="6" class="s-acc"/><text x="495" y="213" text-anchor="middle" class="t-s t-ink">gönderen:</text><text x="495" y="229" text-anchor="middle" class="t-m t-ink">198.51.100.7</text></g>
<circle cx="443" cy="130" r="9" class="s-acc" data-a="move 120 0 .25 .45"/>
<circle cx="563" cy="130" r="9" class="s-acc2" data-a="move -120 0 .55 .72"/>
<circle cx="278" cy="130" r="9" class="s-acc2" data-a="move -120 0 .76 .93"/>
<text x="360" y="30" text-anchor="middle" class="t-s" data-a="show .2 .5">adres değiştirildi, kayıt tabloya yazıldı</text>
<text x="360" y="50" text-anchor="middle" class="t-s" data-a="show .7 .98">yanıt, tablodaki kayda göre içeri iletildi</text>
</svg>
:::

### Port

Bir IP adresi cihazı gösterir; **port** ise o cihazdaki hangi programın paketi alacağını. Port, 0–65535 arasında bir sayıdır. IP adresini bir apartmanın adresi olarak düşünürseniz port, daire numarasıdır.

| Port | Protokol | Ne için kullanılır |
|---|---|---|
| 22 | SSH | Güvenli uzak komut satırı |
| 25 / 587 | SMTP | E-posta gönderimi |
| 53 | DNS | Alan adı çözümleme |
| 67 / 68 | DHCP | Otomatik IP ayarı |
| 80 | HTTP | Şifrelenmemiş web |
| 443 | HTTPS | Şifreli web |
| 3306 | MySQL | Veritabanı |
| 3389 | RDP | Windows uzak masaüstü |

0–1023 arası portlar “iyi bilinen” servislere ayrılmıştır. Bir IP adresi ile bir portun birleşimine (`203.0.113.5:443`) **soket** denir.

## Taşıma: TCP ve UDP

IP, paketi doğru cihaza ulaştırmaya çalışır ama hiçbir garanti vermez: paket kaybolabilir, sırası karışabilir, iki kez gelebilir. Bu sorunla nasıl başa çıkılacağını taşıma katmanındaki iki protokol belirler.

**TCP** (Transmission Control Protocol) güvenilir bir bağlantı kurar. Veri göndermeden önce iki taraf **üç adımlı el sıkışma** ile anlaşır: istemci `SYN` gönderir, sunucu `SYN-ACK` ile yanıtlar, istemci `ACK` ile onaylar. Sonrasında her paket numaralandırılır; alıcı aldıklarını onaylar, onaylanmayan paket yeniden gönderilir. Web sayfaları, e-posta ve dosya aktarımı TCP kullanır; çünkü tek bir eksik bayt bile kabul edilemez.

**UDP** (User Datagram Protocol) ise bağlantı kurmaz, onay beklemez; paketi gönderir ve unutur. Daha hızlı ve hafiftir. Görüntülü görüşme, çevrim içi oyun ve canlı yayın gibi “geç gelen paketin artık işe yaramadığı” durumlarda tercih edilir. DNS sorguları da çoğunlukla UDP ile yapılır.

:::svg TCP veri göndermeden önce üç adımda bağlantı kurar ve her paketi onaylatır. UDP doğrudan gönderir; kaybolan paket yeniden gönderilmez.
<svg viewBox="0 0 720 310" role="img" aria-label="Solda TCP: istemci SYN gönderir, sunucu SYN-ACK ile yanıtlar, istemci ACK gönderir, ardından veri ve onay gidip gelir. Sağda UDP: istemci üç paketi art arda gönderir, biri yolda kaybolur ve yeniden gönderilmez" data-dur="11">
<text x="170" y="24" text-anchor="middle" class="t-l">TCP</text><text x="550" y="24" text-anchor="middle" class="t-l">UDP</text>
<path d="M360 10v290" class="l-line w1 dash" fill="none"/>
<rect x="20" y="40" width="90" height="32" rx="6" class="s-bg3 l-fg w2"/><text x="65" y="61" text-anchor="middle" class="t-s t-b">İstemci</text>
<rect x="230" y="40" width="90" height="32" rx="6" class="s-bg3 l-fg w2"/><text x="275" y="61" text-anchor="middle" class="t-s t-b">Sunucu</text>
<path d="M65 72v225M275 72v225" class="l-mut w2" fill="none"/>
<g data-a="show .02"><path d="M65 100 275 122" class="l-acc w2" fill="none"/><text x="170" y="102" text-anchor="middle" class="t-m t-acc">SYN</text></g>
<g data-a="show .14"><path d="M275 140 65 162" class="l-acc2 w2" fill="none"/><text x="170" y="142" text-anchor="middle" class="t-m t-acc2">SYN-ACK</text></g>
<g data-a="show .26"><path d="M65 180 275 202" class="l-acc w2" fill="none"/><text x="170" y="182" text-anchor="middle" class="t-m t-acc">ACK</text></g>
<g data-a="show .4"><path d="M65 224 275 246" class="l-blue w3" fill="none"/><text x="170" y="226" text-anchor="middle" class="t-m t-blue">VERİ 1</text></g>
<g data-a="show .54"><path d="M275 262 65 284" class="l-acc2 w2" fill="none"/><text x="170" y="264" text-anchor="middle" class="t-m t-acc2">ACK 1 (alındı)</text></g>
<circle cx="65" cy="100" r="7" class="s-acc" data-a="move 210 22 .02 .12"/><circle cx="275" cy="140" r="7" class="s-acc2" data-a="move -210 22 .14 .24"/><circle cx="65" cy="180" r="7" class="s-acc" data-a="move 210 22 .26 .36"/><circle cx="65" cy="224" r="7" class="s-blue" data-a="move 210 22 .4 .5"/><circle cx="275" cy="262" r="7" class="s-acc2" data-a="move -210 22 .54 .64"/>
<rect x="400" y="40" width="90" height="32" rx="6" class="s-bg3 l-fg w2"/><text x="445" y="61" text-anchor="middle" class="t-s t-b">İstemci</text>
<rect x="610" y="40" width="90" height="32" rx="6" class="s-bg3 l-fg w2"/><text x="655" y="61" text-anchor="middle" class="t-s t-b">Sunucu</text>
<path d="M445 72v225M655 72v225" class="l-mut w2" fill="none"/>
<g data-a="show .02"><path d="M445 100 655 122" class="l-blue w3" fill="none"/><text x="550" y="102" text-anchor="middle" class="t-m t-blue">VERİ 1</text></g>
<g data-a="show .14"><path d="M445 150 560 162" class="l-red w3 dash" fill="none"/><text x="520" y="152" text-anchor="middle" class="t-m t-red">VERİ 2</text><text x="610" y="172" text-anchor="middle" class="t-s t-red">✕ kayboldu</text></g>
<g data-a="show .26"><path d="M445 200 655 222" class="l-blue w3" fill="none"/><text x="550" y="202" text-anchor="middle" class="t-m t-blue">VERİ 3</text></g>
<circle cx="445" cy="100" r="7" class="s-blue" data-a="move 210 22 .02 .12"/><circle cx="445" cy="150" r="7" class="s-red" data-a="move 115 12 .14 .2"/><circle cx="445" cy="200" r="7" class="s-blue" data-a="move 210 22 .26 .36"/>
<text x="550" y="262" text-anchor="middle" class="t-s" data-a="show .4">el sıkışma yok, onay yok,</text><text x="550" y="280" text-anchor="middle" class="t-s" data-a="show .4">yeniden gönderim yok</text>
</svg>
:::

| Özellik | TCP | UDP |
|---|---|---|
| Bağlantı | Kurulur (üç adımlı el sıkışma) | Kurulmaz |
| Teslim garantisi | Var; kayıp paket yeniden gönderilir | Yok |
| Sıra garantisi | Var | Yok |
| Başlık boyutu | En az 20 bayt | 8 bayt |
| Hız | Daha yavaş başlar | Çok hızlı |
| Tipik kullanım | Web, e-posta, dosya aktarımı | Oyun, görüntülü görüşme, DNS, canlı yayın |

Modern web'in yeni taşıma protokolü **QUIC**, UDP'nin üzerine kendi güvenilirlik ve şifreleme katmanını kurar; HTTP/3 bu protokolle çalışır.

## Katmanlı modeller: OSI ve TCP/IP

Ağ iletişimi, her biri tek bir işe odaklanan katmanlara ayrılarak anlatılır. Bir katman, altındaki katmanın nasıl çalıştığını bilmek zorunda değildir; yalnızca ondan hizmet alır.

**OSI modeli** yedi katmanlı kuramsal bir çerçevedir ve sorun giderirken ortak bir dil sağlar (“bu bir 2. katman sorunu”). **TCP/IP modeli** ise internetin gerçekte kullandığı dört katmanlı yapıdır.

| OSI katmanı | Görevi | Örnek | TCP/IP karşılığı |
|---|---|---|---|
| 7. Uygulama | Kullanıcıya dönük servisler | HTTP, DNS, SMTP | Uygulama |
| 6. Sunum | Veri biçimi, şifreleme | TLS, JPEG | Uygulama |
| 5. Oturum | Oturumların açılıp kapanması | RPC | Uygulama |
| 4. Taşıma | Uçtan uca iletim, portlar | TCP, UDP | Taşıma |
| 3. Ağ | Adresleme ve yönlendirme | IP, ICMP | İnternet |
| 2. Veri bağı | Yerel ağda teslim | Ethernet, Wi-Fi, MAC | Ağ erişimi |
| 1. Fiziksel | Bitlerin iletimi | Kablo, radyo dalgası | Ağ erişimi |

Bir veri gönderilirken her katman kendi başlığını ekler; buna **kapsülleme** denir. Uygulamanın verisi TCP başlığıyla **segment**, IP başlığıyla **paket**, Ethernet başlığıyla **çerçeve** (frame) olur. Alıcıda başlıklar ters sırayla çıkarılır.

:::svg Kapsülleme: veri aşağı indikçe her katman kendi başlığını ekler. Alıcı tarafta başlıklar ters sırayla çıkarılır.
<svg viewBox="0 0 720 270" role="img" aria-label="Kapsülleme adımları: uygulama verisine önce TCP başlığı eklenerek segment, sonra IP başlığı eklenerek paket, son olarak Ethernet başlığı eklenerek çerçeve oluşur" data-dur="10">
<text x="20" y="46" class="t-b">Uygulama</text><text x="20" y="106" class="t-b">Taşıma</text><text x="20" y="166" class="t-b">İnternet</text><text x="20" y="226" class="t-b">Ağ erişimi</text>
<text x="700" y="46" text-anchor="end" class="t-s">veri</text><text x="700" y="106" text-anchor="end" class="t-s">segment</text><text x="700" y="166" text-anchor="end" class="t-s">paket</text><text x="700" y="226" text-anchor="end" class="t-s">çerçeve</text>
<g data-a="show 0"><rect x="400" y="22" width="200" height="40" rx="6" class="s-blue"/><text x="500" y="47" text-anchor="middle" class="t-m t-w">HTTP verisi</text></g>
<g data-a="show .2"><rect x="400" y="82" width="200" height="40" rx="6" class="s-blue"/><text x="500" y="107" text-anchor="middle" class="t-m t-w">HTTP verisi</text><rect x="310" y="82" width="86" height="40" rx="6" class="s-acc"/><text x="353" y="107" text-anchor="middle" class="t-m t-ink">TCP</text></g>
<g data-a="show .45"><rect x="400" y="142" width="200" height="40" rx="6" class="s-blue"/><text x="500" y="167" text-anchor="middle" class="t-m t-w">HTTP verisi</text><rect x="310" y="142" width="86" height="40" rx="6" class="s-acc"/><text x="353" y="167" text-anchor="middle" class="t-m t-ink">TCP</text><rect x="220" y="142" width="86" height="40" rx="6" class="s-acc2"/><text x="263" y="167" text-anchor="middle" class="t-m t-w">IP</text></g>
<g data-a="show .7"><rect x="400" y="202" width="200" height="40" rx="6" class="s-blue"/><text x="500" y="227" text-anchor="middle" class="t-m t-w">HTTP verisi</text><rect x="310" y="202" width="86" height="40" rx="6" class="s-acc"/><text x="353" y="227" text-anchor="middle" class="t-m t-ink">TCP</text><rect x="220" y="202" width="86" height="40" rx="6" class="s-acc2"/><text x="263" y="227" text-anchor="middle" class="t-m t-w">IP</text><rect x="130" y="202" width="86" height="40" rx="6" class="s-pur"/><text x="173" y="227" text-anchor="middle" class="t-m t-w">Ethernet</text></g>
<path d="M120 68h500M120 128h500M120 188h500" class="l-line w1 dash" fill="none"/>
</svg>
:::

## Güvenlik ve gizlilik

### Güvenlik duvarı (firewall)

**Güvenlik duvarı**, ağ trafiğini önceden tanımlanmış kurallara göre süzen yazılım ya da cihazdır. Kurallar genellikle “şu adresten, şu porta gelen bağlantıya izin ver ya da reddet” biçimindedir. Yönlendiricinizdeki güvenlik duvarı dışarıdan gelen istenmeyen bağlantıları, işletim sisteminizdeki ise programların ağ erişimini denetler. **Durum bilgili** (stateful) güvenlik duvarları, içeriden başlatılmış bağlantıların yanıtlarını tanır ve yalnızca onlara izin verir.

### VPN

**VPN** (Virtual Private Network), cihazınız ile bir VPN sunucusu arasında şifreli bir **tünel** kurar. Tüm trafiğiniz bu tünelden geçer ve internete VPN sunucusunun IP adresiyle çıkar. Bunun üç sonucu vardır: bulunduğunuz ağdaki (örneğin bir kafenin Wi-Fi'ı) kimse trafiğinizi okuyamaz, ziyaret ettiğiniz siteler gerçek IP adresinizi görmez ve şirketler çalışanlarının iç ağa güvenle bağlanmasını sağlayabilir.

VPN sizi anonim yapmaz; yalnızca güveni internet sağlayıcınızdan VPN sağlayıcısına taşır. Yaygın protokoller **WireGuard**, **OpenVPN** ve **IPsec**'tir.

### Vekil sunucu (proxy)

**Vekil sunucu**, sizin adınıza istek yapan bir aracıdır. VPN'den farkı, genellikle tüm trafiği değil yalnızca belirli bir uygulamanın (örneğin tarayıcının) trafiğini taşıması ve her zaman şifreleme yapmamasıdır. **Ters vekil** (reverse proxy) ise sunucu tarafında durur: gelen istekleri arkadaki sunuculara dağıtır, önbellekler ve şifrelemeyi üstlenir. Nginx ve içerik dağıtım ağları (**CDN**) bu görevi görür.

### HTTPS ve TLS

**HTTP**, tarayıcı ile web sunucusunun konuştuğu protokoldür. **HTTPS**, aynı konuşmanın **TLS** ile şifrelenmiş hâlidir. TLS üç şey sağlar: gizlilik (araya giren okuyamaz), bütünlük (veri yolda değiştirilemez) ve kimlik doğrulama (sunucunun sertifikası, gerçekten o alan adına ait olduğunu kanıtlar).

## Başarım terimleri

### Bant genişliği

**Bant genişliği**, bir bağlantının saniyede taşıyabileceği en yüksek veri miktarıdır ve bit/saniye ile ölçülür: Mbps (megabit) ya da Gbps (gigabit). Bir otoyolun şerit sayısı gibidir: ne kadar genişse aynı anda o kadar çok araç geçer.

:::warn Megabit ile megabayt aynı şey değildir
İnternet hızları megabit (Mb), dosya boyutları megabayt (MB) ile ifade edilir. 1 bayt 8 bittir; yani 100 Mbps'lik bir bağlantı kuramsal olarak saniyede en fazla 12,5 MB indirir.
:::

### Gecikme (latency) ve ping

**Gecikme**, bir paketin hedefe gidip yanıtın geri gelmesi için geçen süredir ve milisaniye (ms) ile ölçülür. Otoyol benzetmesinde şerit sayısı değil, yolun uzunluğudur. Bant genişliği büyük dosyaların ne kadar çabuk ineceğini, gecikme ise bağlantının ne kadar “anında” tepki verdiğini belirler. Çevrim içi oyun ve görüntülü görüşmede gecikme, bant genişliğinden daha önemlidir.

**Ping**, gecikmeyi ölçen araçtır: hedefe küçük bir ICMP “yankı isteği” gönderir ve yanıtın dönüş süresini bildirir. Gündelik dilde “ping'im yüksek” demek, gecikmem fazla demektir.

### Jitter, paket kaybı ve MTU

- **Jitter**, gecikmenin paketten pakete ne kadar değiştiğidir. Sabit 60 ms'lik bir gecikme, 20 ile 150 ms arasında gidip gelen bir gecikmeden daha iyidir; çünkü sesli ve görüntülü iletişim düzenli akışa ihtiyaç duyar.
- **Paket kaybı**, hedefe ulaşmayan paketlerin oranıdır. Yüzde 1'in üzerindeki kayıp görüşmelerde kesilmeye, oyunlarda ışınlanmaya yol açar.
- **MTU**, bir ağ bağlantısından tek parça geçebilecek en büyük paket boyutudur; Ethernet'te 1500 bayttır.
- **Traceroute**, bir paketin hedefe giderken geçtiği yönlendiricileri ve her birindeki gecikmeyi listeleyen araçtır; sorunun yolun neresinde olduğunu bulmaya yarar.

| Terim | Neyi ölçer | Birim | İyi değer (ev kullanımı) |
|---|---|---|---|
| Bant genişliği | Saniyede taşınan veri | Mbps / Gbps | İhtiyaca göre; 4K yayın için 25 Mbps |
| Gecikme (ping) | Gidiş-dönüş süresi | ms | 50 ms'nin altı |
| Jitter | Gecikmedeki dalgalanma | ms | 30 ms'nin altı |
| Paket kaybı | Ulaşmayan paket oranı | % | %1'in altı |

## Hepsi bir arada: bir sayfa açılırken neler olur?

Şimdi parçaları birleştirelim. Tarayıcıya `https://ornek.com` yazıp Enter'a bastığınızda sırasıyla şunlar gerçekleşir:

1. Cihazınız ağa bağlanırken **DHCP** ile bir özel IP adresi, ağ geçidi ve DNS sunucusu adresi almıştır.
2. Tarayıcı, `ornek.com` adının IP adresini **DNS** çözümleyiciye sorar ve örneğin `203.0.113.5` yanıtını alır.
3. İşletim sistemi bu adresin kendi **alt ağında** olmadığını görür ve paketi **ağ geçidine**, yani yönlendiriciye gönderir. Yönlendiricinin **MAC adresini** öğrenmek için gerekirse **ARP** kullanır.
4. Yönlendirici **NAT** uygulayarak paketin gönderen adresini genel IP adresinizle değiştirir ve paketi internet sağlayıcınıza iletir.
5. Paket, yönlendiriciden yönlendiriciye atlayarak hedef sunucuya ulaşır. Her yönlendirici yalnızca bir sonraki adımı bilir.
6. Tarayıcınız sunucunun **443 numaralı portuna** bir **TCP** bağlantısı açar (ya da HTTP/3 kullanılıyorsa **UDP** üzerinden QUIC bağlantısı kurar).
7. **TLS** el sıkışması yapılır: sunucu sertifikasını gösterir, iki taraf şifreleme anahtarlarında anlaşır.
8. Tarayıcı şifreli kanal üzerinden **HTTP** isteğini gönderir; sunucu sayfayı paketler hâlinde yollar.
9. Paketler cihazınızda sıraya dizilir, eksik olan varsa yeniden istenir ve sayfa ekrana çizilir.

Bütün bunlar, iyi bir bağlantıda bir saniyeden kısa sürer. Yavaşlık yaşadığınızda sorunun bu adımlardan hangisinde olduğunu bulmak, çözümün yarısıdır: ad çözülmüyorsa DNS'e, hiç yanıt yoksa ağ geçidine ve güvenlik duvarına, yanıt geç geliyorsa gecikme ve paket kaybına bakılır.

## Hangi durumda hangisi?

| İhtiyacınız | Kullanacağınız şey |
|---|---|
| Ortak Wi-Fi'da trafiği gizlemek | VPN |
| Evdeki bir cihaza dışarıdan erişmek | Port yönlendirme (CGNAT varsa VPN ya da tünel servisi) |
| Yalnızca tarayıcı trafiğini başka yerden çıkarmak | Vekil sunucu |
| Bağlantının neden yavaş olduğunu anlamak | Önce `ping`, sonra `traceroute` |
| Alan adı açılmıyor ama IP ile açılıyor | DNS ayarlarını denetlemek |
| Cihaz IP alamıyor (169.254.x.x) | DHCP sunucusunu, yani yönlendiriciyi denetlemek |
| Ağı bölümlere ayırmak | Alt ağlar ve güvenlik duvarı kuralları |

## Sık sorulan sorular

:::faq
Q: IP adresim konumumu gösterir mi?
A: Yaklaşık olarak. Genel IP adresiniz internet sağlayıcınızı ve çoğu zaman şehrinizi gösterir, ama ev adresinizi göstermez. Kesin kimlik bilgisi yalnızca sağlayıcının kayıtlarında bulunur.
Q: Dinamik IP ile statik IP arasındaki fark nedir?
A: Dinamik IP, sağlayıcının size geçici olarak verdiği ve zaman zaman değişen adrestir. Statik IP hiç değişmez; evde sunucu barındırmak ya da uzaktan erişim için gereklidir ve genellikle ek ücretlidir.
Q: IPv4 adresleri tükendiyse internet nasıl çalışmaya devam ediyor?
A: NAT sayesinde. Milyarlarca cihaz özel adreslerle, çok daha az sayıda genel adresin arkasında çalışıyor. Kalıcı çözüm ise yaygınlaşmakta olan IPv6'dır.
Q: DNS sunucumu değiştirmek interneti hızlandırır mı?
A: İndirme hızınızı değiştirmez, ama alan adlarının çözülme süresini kısaltabilir; bu da sayfaların açılmaya başlamasını bir miktar hızlandırır.
Q: VPN kullanınca internet neden yavaşlıyor?
A: Trafiğiniz şifrelenir ve önce VPN sunucusuna uğrar. Şifreleme az bir işlem yükü, dolambaçlı yol ise ek gecikme getirir. Yakın ve yoğun olmayan bir sunucu seçmek farkı azaltır.
Q: Yönlendirici ile ağ geçidi aynı şey mi?
A: Ev ağlarında pratikte evet: ağ geçidi görevini yönlendiriciniz yapar. Kavram olarak ağ geçidi bir roldür, yönlendirici ise o rolü üstlenen cihazdır.
Q: Hız testinde gördüğüm indirme, yükleme ve ping ne anlama gelir?
A: İndirme ve yükleme, bağlantınızın iki yöndeki bant genişliğidir. Ping ise test sunucusuna olan gidiş-dönüş gecikmesidir.
:::

## Özet

- Veriler paketlere bölünür; her paket gönderen ve alıcı IP adresini taşır.
- IPv4 adresleri yetersiz olduğu için ev ağları özel adres ve NAT kullanır; IPv6 bu sorunu kökten çözer.
- DHCP cihaza adres verir, DNS adları IP'ye çevirir, ağ geçidi paketi dış dünyaya taşır.
- TCP güvenilir ama daha ağır, UDP hızlı ama garantisizdir.
- OSI yedi, TCP/IP dört katmanla aynı süreci anlatır; her katman veriye kendi başlığını ekler.
- VPN tüm trafiği şifreli bir tünelden geçirir; vekil sunucu aracılık eder; güvenlik duvarı kurallara göre süzer.
- Bant genişliği kapasiteyi, gecikme tepki süresini ölçer; ikisi farklı şeylerdir.

## Kaynaklar

- [RFC 791 – Internet Protocol (IPv4)](https://www.rfc-editor.org/rfc/rfc791)
- [RFC 8200 – Internet Protocol, Version 6 (IPv6) Specification](https://www.rfc-editor.org/rfc/rfc8200)
- [RFC 1918 – Address Allocation for Private Internets](https://www.rfc-editor.org/rfc/rfc1918)
- [RFC 4632 – Classless Inter-domain Routing (CIDR)](https://www.rfc-editor.org/rfc/rfc4632)
- [RFC 9293 – Transmission Control Protocol (TCP)](https://www.rfc-editor.org/rfc/rfc9293)
- [RFC 768 – User Datagram Protocol](https://www.rfc-editor.org/rfc/rfc768)
- [RFC 1034 – Domain Names: Concepts and Facilities](https://www.rfc-editor.org/rfc/rfc1034)
- [RFC 2131 – Dynamic Host Configuration Protocol](https://www.rfc-editor.org/rfc/rfc2131)
- [RFC 9000 – QUIC: A UDP-Based Multiplexed and Secure Transport](https://www.rfc-editor.org/rfc/rfc9000)
- [IANA – Service Name and Transport Protocol Port Number Registry](https://www.iana.org/assignments/service-names-port-numbers/service-names-port-numbers.xhtml)
- [ISO/IEC 7498-1 – Open Systems Interconnection: Basic Reference Model](https://www.iso.org/standard/20269.html)
- [Cloudflare Learning Center – What is DNS?](https://www.cloudflare.com/learning/dns/what-is-dns/)
