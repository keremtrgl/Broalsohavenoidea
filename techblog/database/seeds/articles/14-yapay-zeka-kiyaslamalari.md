---
title: Every AI Benchmark Explained: Yapay Zekâ Kıyaslamaları Neyi Ölçer?
slug: every-ai-benchmark-explained
category: yapay-zeka
tags: yapay zekâ, benchmark, swe-bench, lmarena, arc-agi, kıyaslama
description: MMLU, GPQA, HLE, ARC-AGI, FrontierMath, SWE-bench, Terminal-Bench, OSWorld, MMMU ve LMArena: her kıyaslama neyi ölçer, skor nasıl okunur, sınırları neler?
excerpt: “SWE-bench'te yüzde 97”, “LMArena'da 1526 Elo” ne anlama geliyor? Yapay zekâ kıyaslamalarının neyi ölçtüğünü, skorların nasıl okunacağını, veri sızıntısından üretici beyanına kadar sınırlarını ve doğru okuma için bir kontrol listesini sunuyoruz.
cover: /assets/covers/ai-benchmarks.svg
cover_alt: Bir cetvel, yükselen çubuk grafik ve onay işaretli bir sınav kâğıdından oluşan çizim
series: Every X Explained
series_order: 14
featured: 1
show_updated: 1
date: 2026-11-11 09:00
updated: 2026-11-12 09:00
---
Yeni bir yapay zekâ modeli duyurulduğunda tanıtımın yarısı sayılardan oluşur: şu kıyaslamada yüzde 95, bu sıralamada birinci, ötekinde rekor. Bu sayılar gerçek bir şey söyler, ama ne söylediklerini anlamak için neyin, nasıl ve kim tarafından ölçüldüğünü bilmek gerekir. Aynı modelin aynı kıyaslamadaki skoru, ölçümü yapana ve ölçümün yapılış biçimine göre on puandan fazla değişebilir.

Bu yazı, yapay zekâ dizimizin ikinci bölümüdür:

1. [Every AI Model Explained](/post/every-ai-model-explained): model türleri ve çalışma biçimleri.
2. **Kıyaslamalar neyi ölçer?** (bu yazı)
3. [Best AI for Every Task](/post/best-ai-for-every-task): hangi iş için hangi model, güncel verilerle.

Önce kıyaslamanın ne olduğunu ve sınırlarını, sonra kategori kategori bütün önemli kıyaslamaları ele alacağız. Yazıdaki güncel skorlar, yönetim panelinden güncellenen ortak veri dosyalarından okunur; her skorun yanında kaynağı ve tarihi yer alır.

## Temel kavramlar

### Kıyaslama nedir?

**Kıyaslama** (benchmark), modellerin karşılaştırılabilmesi için hazırlanmış standart bir sınavdır: sabit bir soru ya da görev kümesi, belirli bir puanlama yöntemi ve çoğu zaman bir sıralama tablosu. Amaç, “bu model şundan daha iyi” iddiasını ölçülebilir kılmaktır.

Bir kıyaslamanın üç bileşeni vardır:

- **Veri kümesi:** Sorular ya da görevler. Çoktan seçmeli bilgi sorularından, çözülmesi saatler süren yazılım görevlerine kadar değişir.
- **Değerlendirme düzeneği** (harness): Modele sorunun nasıl sorulduğu, hangi araçların verildiği, kaç deneme hakkı tanındığı ve ne kadar düşünmesine izin verildiği.
- **Puanlama:** Yanıtın doğru sayılıp sayılmayacağına nasıl karar verildiği.

### Skorlar nasıl hesaplanır?

| Yöntem | Nasıl çalışır | Örnek |
|---|---|---|
| Doğruluk yüzdesi | Doğru yanıtların toplam soruya oranı | MMLU, GPQA, ARC-AGI |
| Otomatik testler | Modelin ürettiği kod, gizli birim testlerinden geçmeli | HumanEval, SWE-bench |
| Durum denetimi | Görev sonunda sistemin beklenen duruma ulaşıp ulaşmadığına bakılır | OSWorld, τ-bench, Terminal-Bench |
| İnsan oylaması (Elo) | İnsanlar iki anonim yanıttan iyisini seçer | LMArena |
| Model hakemliği | Başka bir model, yanıtı bir ölçüte göre puanlar | Açık uçlu yanıtlarda |
| Dağılım ölçütleri | Üretilen içeriğin istatistikleri gerçek verininkiyle karşılaştırılır | FID |

Çoktan seçmeli sınavlarda **şans düzeyini** akılda tutmak gerekir: dört seçenekli bir sınavda rastgele işaretleyen biri yüzde 25 alır. Yüzde 30'luk bir skor bu yüzden neredeyse hiçbir şey bilmemek demektir.

### Kıyaslamalar neden önemli?

Kıyaslamalar olmadan ilerlemeyi ölçmenin tek yolu izlenimler olurdu. Araştırmacılar hangi yöntemin işe yaradığını, şirketler hangi modeli seçeceklerini, düzenleyiciler bir modelin hangi yeteneklere ulaştığını kıyaslamalarla izler. İyi bir kıyaslama alana yön de verir: 2012'de ImageNet yarışması derin öğrenme devrimini tetiklemişti.

## Kıyaslamaların sınırları

Bir skoru doğru okumak, ölçümün nerede yanıltabileceğini bilmekle başlar.

### Veri sızıntısı (contamination)

Dil modelleri internetin büyük bölümüyle eğitilir. Bir kıyaslamanın soruları ve yanıtları internette yayımlandıysa, modelin eğitim verisine girmiş olabilir. Bu durumda model soruyu çözmez, **hatırlar**; skor yeteneği değil ezberi ölçer. Buna veri sızıntısı ya da kirlenme denir.

:::svg Veri sızıntısı: yayımlanan sınav soruları eğitim verisine karışırsa model yanıtları ezberler. Aynı model, daha önce görmediği yeni sorularda belirgin biçimde daha düşük skor alır.
<svg viewBox="0 0 720 300" role="img" aria-label="Akış: kıyaslama soruları internette yayımlanır, web taramasıyla eğitim verisine girer, model bu veriyle eğitilir. Sonuçta eski sorularda yüzde 92, hiç yayımlanmamış yeni sorularda yüzde 61 skor alınır" data-dur="11">
<rect x="20" y="30" width="150" height="70" rx="10" class="s-bg3 l-fg w2"/><text x="95" y="58" text-anchor="middle" class="t-b">Kıyaslama</text><text x="95" y="80" text-anchor="middle" class="t-s">soru + yanıt</text>
<rect x="250" y="30" width="170" height="70" rx="10" class="s-bg3 l-fg w2"/><text x="335" y="58" text-anchor="middle" class="t-b">İnternet</text><text x="335" y="80" text-anchor="middle" class="t-s">makale, forum, kod deposu</text>
<rect x="500" y="30" width="200" height="70" rx="10" class="s-bg3 l-red w2"/><text x="600" y="58" text-anchor="middle" class="t-b">Eğitim verisi</text><text x="600" y="80" text-anchor="middle" class="t-s">milyarlarca sayfa</text>
<path d="M170 65h80M420 65h80M600 100v40" class="l-mut w2" fill="none"/>
<rect x="500" y="140" width="200" height="56" rx="10" class="s-soft l-acc w2"/><text x="600" y="173" text-anchor="middle" class="t-b">Model</text>
<g data-a="show .02"><text x="210" y="52" text-anchor="middle" class="t-s t-b">yayımlanır</text></g>
<rect x="174" y="55" width="20" height="20" rx="4" class="s-red" data-a="move 52 0 .04 .2"/>
<g data-a="show .24"><text x="460" y="52" text-anchor="middle" class="t-s t-b">taranır</text></g>
<rect x="424" y="55" width="20" height="20" rx="4" class="s-red" data-a="move 52 0 .26 .42"/>
<rect x="590" y="104" width="20" height="20" rx="4" class="s-red" data-a="move 0 20 .46 .58"/>
<text x="20" y="150" class="t-b" data-a="show .62">Sonuç</text>
<text x="20" y="186" class="t-s" data-a="show .62">Yayımlanmış eski sorular</text><rect x="210" y="170" width="258" height="22" rx="5" class="s-red" data-o="l" data-a="grow x .64 .78"/><text x="478" y="187" class="t-b" data-a="show .78">%92</text>
<text x="20" y="232" class="t-s" data-a="show .8">Hiç yayımlanmamış yeni sorular</text><rect x="210" y="216" width="171" height="22" rx="5" class="s-acc" data-o="l" data-a="grow x .82 .92"/><text x="391" y="233" class="t-b" data-a="show .92">%61</text>
<text x="20" y="276" class="t-s" data-a="show .92">Aradaki fark yetenek değil ezberdir. (Sayılar örnektir; gerçek bir ölçüm değildir.)</text>
</svg>
:::

Kıyaslama hazırlayanların buna karşı kullandığı yöntemler vardır: soruları **gizli tutmak** (ARC-AGI'nin yarı gizli kümesi, FrontierMath), soruları **sürekli yenilemek** (LiveCodeBench) ve dosyalara, taranmayı önlemeye yönelik özel işaret dizgileri eklemek.

### Doygunluk

Modeller bir kıyaslamada yüzde 90'ın üzerine çıktığında kalan birkaç puan çoğu zaman yetenek farkını değil, sınavın kendi hatalarını (yanlış yanıt anahtarı, belirsiz soru) ve rastlantıyı yansıtır. Kıyaslama artık modelleri ayırt edemez; buna **doygunluk** denir. MMLU, GSM8K, HumanEval ve Needle-in-a-Haystack bu duruma gelmiştir. Alan bu yüzden sürekli daha zor sınavlar üretir.

### Üretici beyanı ve bağımsız ölçüm

Bir skoru modelin üreticisi de açıklayabilir, bağımsız bir kuruluş da ölçebilir. İkisi aynı ağırlığı taşımaz. Üretici; en iyi sonucu veren düzeneği, en yüksek düşünme ayarını ve en elverişli alt kümeyi seçme özgürlüğüne sahiptir. Bağımsız ölçüm yapan kuruluşlar (Artificial Analysis, Epoch AI, ARC Prize, Vals AI, LMArena) bütün modellere aynı koşulları uygular. Bu sitede üretici kaynaklı skorlar “üretici beyanı” etiketiyle gösterilir.

Bağımsızlık da mutlak değildir. Ocak 2025'te, FrontierMath kıyaslamasının hazırlanmasını OpenAI'ın finanse ettiği ve soruların büyük bölümüne erişimi olduğu ortaya çıktı; Epoch AI bunu baştan açıklamadığı için eleştirildi ve sonradan ayrıntıları yayımladı. Bir kıyaslamayı kimin finanse ettiği de bilinmesi gereken bir bilgidir.

### Aynı sınav, farklı düzenek

Aynı kıyaslamanın farklı tablolarında çok farklı sayılar görebilirsiniz. SWE-bench Verified bunun iyi bir örneğidir: kıyaslamanın resmî “yalnızca bash” tablosunda Şubat 2026 itibarıyla en yüksek skor yüzde 76,8 iken, Vals AI'ın aynı görevlerde kendi düzeneğiyle yaptığı Eylül 2026 ölçümünde en üst sıradaki model yüzde 97'ye ulaşmıştır. Aradaki farkın bir bölümü aradan geçen sürede çıkan yeni modellerden, bir bölümü ise düzenek farkından gelir: modele verilen araçlar, adım sınırı ve düşünme bütçesi sonucu doğrudan etkiler.

:::chart
{"type":"bar","horizontal":true,"title":"SWE-bench Verified: Vals AI ölçümü (mini-SWE-agent düzeneği)","labels":["Claude Opus 5","DeepSeek V4 Pro 0813","GPT-5.6 Sol","Grok 4.6","GPT-5.6 Terra","GLM 5.3","Claude Fable 5","Kimi K3","GPT-5.6 Luna","Qwen 3.8 27B","Qwen 3.8 Max","Gemini 3.8 Flash"],"datasets":[{"label":"Çözülen görev oranı","data":[97,96.4,96.2,95.6,95.4,95.4,95,93.4,93,86,85.6,80]}],"unit":"%","xTitle":"Çözülen görev oranı (%)","source":"Vals AI – SWE-bench Verified","sourceUrl":"https://www.vals.ai/benchmarks/swebench","date":"2026-09-01","note":"Bağımsız ölçüm. Tabloda bir önceki kuşak modeller yer alır; ilk dokuz modelin yüzde 93'ün üzerinde olması kıyaslamanın doygunluğa ulaştığını gösterir."}
:::

Bu grafik iki dersi birlikte verir: ilk dokuz model arasındaki fark yalnızca dört puandır ve bu aralıkta sıralama, ölçüm gürültüsünden ayırt edilemez. SWE-bench Verified artık en iyi modelleri birbirinden ayıramamaktadır.

### Kıyaslamaya göre eniyileme

İktisatçı Charles Goodhart'a atfedilen bir ilke vardır: bir ölçüt hedef hâline geldiğinde iyi bir ölçüt olmaktan çıkar. Bir kıyaslama önem kazandıkça, model geliştirenler bilerek ya da bilmeyerek o sınava özgü örüntülere uyum sağlar. Model sınavda yükselir, ama gerçek kullanımdaki yeteneği aynı ölçüde artmayabilir.

### Raporlanmayanlar

Tek bir skor şunları söylemez: yanıtın **maliyeti** (aynı skora bir model on kat fazla ücretle ulaşmış olabilir), **süresi**, sonucun **yinelenebilirliği** (aynı soruyu on kez sorduğunuzda kaç kez doğru yanıtlıyor?) ve **güven aralığı** (200 soruluk bir sınavda iki puanlık fark istatistiksel olarak anlamsızdır).

## Genel bilgi ve akıl yürütme

### MMLU ve MMLU-Pro

**MMLU** (2020), 57 alanda yaklaşık 16 bin dört seçenekli sorudan oluşur: lise kimyasından hukuka, tıptan felsefeye. Yıllarca “genel bilgi” ölçütünün standardı oldu. Soru örneği: bir ilacın etki mekanizması sorulur ve dört seçenek verilir. Bugün üst düzey modeller yüzde 90'ın üzerindedir ve kıyaslama doygundur; ayrıca sorularının bir bölümünde yanıt anahtarı hataları olduğu gösterilmiştir.

**MMLU-Pro** (2024), seçenek sayısını ona çıkararak şans düzeyini yüzde 10'a indirir ve ezberle çözülen kolay soruları ayıklayıp akıl yürütme gerektirenleri artırır.

**Nasıl okunur:** MMLU'da yüzde 88 ile 91 arasındaki fark anlamlı değildir. Bu kıyaslama artık yalnızca küçük modellerin ne kadar geride olduğunu göstermeye yarar.

### GPQA Diamond

**GPQA** (2023), biyoloji, fizik ve kimyada doktora düzeyinde çoktan seçmeli sorulardan oluşur. “Google-proof” olarak tasarlanmıştır: alan dışından yetkin kişiler, internete sınırsız erişimle ve yarım saatten fazla uğraşarak bile yalnızca yaklaşık yüzde 34 doğru yanıtlayabilmiştir; alan uzmanlarının doğruluğu ise yaklaşık yüzde 65'tir. **Diamond** alt kümesi, en güvenilir 198 sorudur.

**Nasıl okunur:** Dört seçenekli olduğu için taban yüzde 25'tir. Yalnızca 198 soru olduğundan bir sorunun ağırlığı yarım puandır; küçük farklar gürültüdür. Üst düzey modeller uzman düzeyini çoktan geçmiştir.

### Humanity's Last Exam (HLE)

**HLE** (2025), Center for AI Safety ve Scale AI'ın, yüzlerce alan uzmanının katkısıyla hazırladığı 2.500 soruluk bir sınavdır. Amacı, doygunluğa ulaşan akademik sınavların yerine geçmektir. Sorular, hazırlandıkları dönemin en iyi modellerinin yanıtlayamadıkları arasından seçilmiştir; bir bölümü görsel içerir. Yanıtlar kısa ve kesindir; doğruluğu otomatik denetlenir. Örnek soru türü: bir kuş türünün iskeletindeki belirli bir kemik yapısının sayısı ya da eski bir yazıttaki bir ifadenin çevirisi gibi, yalnızca o alanın uzmanının bilebileceği ayrıntılar.

:::ai
{"widget":"benchmark-chart","benchmark":"hle"}
:::

**Nasıl okunur:** İlk çıktığında en iyi modeller tek haneli yüzdeler alıyordu. Bugün en yüksek skorlar bile soruların yarısı dolayındadır; yani sınav hâlâ ayırt edicidir. HLE yalnızca doğruluğu değil, modelin kendi yanıtına duyduğu güvenin ne kadar isabetli olduğunu da raporlar.

### ARC-AGI

François Chollet'nin 2019'da önerdiği **ARC-AGI**, bilgiyi değil **yeni bir kuralı öğrenme** yeteneğini ölçer. Her görevde birkaç renkli ızgara çifti (girdi ve çıktı) gösterilir; model, aradaki dönüşüm kuralını çıkarıp yeni bir girdiye uygulamalıdır. Görevler insanlar için kolay, ezbere dayanan sistemler için zordur; çünkü her görev daha önce görülmemiş bir kural içerir.

Kıyaslamanın üç kuşağı vardır. **ARC-AGI-1** büyük ölçüde çözülmüştür. **ARC-AGI-2** (2025) birden çok kuralın birlikte uygulanmasını gerektirir. **ARC-AGI-3** ise durağan bulmacalar yerine, kuralları hiç anlatılmayan etkileşimli oyun ortamları sunar: model deneyerek keşfetmek, amacı kendisi bulmak ve plan yapmak zorundadır.

:::ai
{"widget":"benchmark-chart","benchmark":"arc_agi_3"}
:::

**Nasıl okunur:** ARC Prize yalnızca doğruluğu değil, **görev başına maliyeti** de raporlar; aynı skora on kat pahalıya ulaşan bir sistem aynı değerde sayılmaz. Güncel tabloda ARC-AGI-2'de birçok model yüzde 90'ın üzerindeyken, ARC-AGI-3'te modeller arasındaki fark çok büyüktür: sınav, keşif gerektiren ortamlarda hâlâ güçlü bir ayırt edicidir.

## Matematik

### GSM8K ve MATH

**GSM8K** (2021), ilkokul ve ortaokul düzeyinde yaklaşık 8.500 sözel problem içerir: “Bir manavda 23 elma var, 9'u satılıyor…” türünden, birkaç adımlı aritmetik. **MATH** (2021) ise lise yarışma düzeyinde 12.500 problemden oluşur ve beş zorluk kademesi vardır. İkisi de doygundur; üst düzey modeller neredeyse tam puan alır ve sorular yıllardır internette bulunduğu için sızıntı riski yüksektir.

**Nasıl okunur:** Bu iki sınavdaki yüksek skor bugün bir övünç değil, asgari beklentidir.

### AIME

**AIME**, ABD'de lise öğrencilerinin girdiği bir matematik sınavıdır: 15 soru, her birinin yanıtı 0 ile 999 arasında bir tam sayı. Her yıl yeni sorular hazırlandığı için, modelin bilgi kesim tarihinden sonraki yılın sınavı sızıntıya karşı doğal bir koruma sağlar.

**Nasıl okunur:** Yalnızca 15 soru vardır; tek bir soru yaklaşık 6,7 puandır. Skorlar genellikle birkaç denemenin ortalaması olarak verilir. Hangi yılın sınavı olduğuna mutlaka bakın.

### FrontierMath

Epoch AI'ın 2024'te duyurduğu **FrontierMath**, uzman matematikçilerin bu sınav için yazdığı, hiç yayımlanmamış özgün problemlerden oluşur. Dört kademesi vardır: ilk üçü lisans düzeyinden araştırma eşiğine uzanır, dördüncüsü araştırma düzeyindedir. Yanıtlar bir sayı ya da matematiksel nesne olduğu için otomatik denetlenebilir, ama tahmin edilemeyecek kadar büyük ya da özgüldür.

:::ai
{"widget":"benchmark-chart","benchmark":"frontiermath"}
:::

**Nasıl okunur:** Sınav duyurulduğunda en iyi modeller yüzde 2'nin altındaydı; iki yıl içinde ilk üç kademede yüzde 90'ların üzerine çıkıldı. Bu, alandaki ilerlemenin hızının iyi bir göstergesidir. Yukarıda değinilen finansman tartışmasını da akılda tutmak gerekir. Kademe 4 ve henüz çözülmemiş açık problemlerden oluşan bölümler ayırt ediciliğini korumaktadır.

## Kodlama

### HumanEval

**HumanEval** (2021), 164 Python fonksiyonundan oluşur: modele fonksiyonun imzası ve açıklaması verilir, gövdeyi yazması istenir ve sonuç gizli birim testleriyle denetlenir. Ölçüt **pass@1**'dir: ilk denemede testlerden geçen çözümlerin oranı. Tek fonksiyonluk, kendi içinde tamamlanmış küçük görevlerdir ve çoktan doygunluğa ulaşmıştır.

### LiveCodeBench

**LiveCodeBench** (2024), sızıntı sorununa doğrudan yanıt verir: LeetCode, AtCoder ve Codeforces gibi yarışma sitelerinden sürekli yeni problem toplar ve her problemi yayımlanma tarihiyle etiketler. Bir modeli, yalnızca bilgi kesim tarihinden **sonra** yayımlanmış problemlerle değerlendirebilirsiniz.

**Nasıl okunur:** Hangi tarih aralığındaki problemlerin kullanıldığına bakın. Bir modelin skoru, kesim tarihinden sonraki problemlerde belirgin biçimde düşüyorsa bu sızıntı işaretidir.

### SWE-bench Verified

**SWE-bench** (2023), gerçek yazılım mühendisliğini ölçmeyi amaçlar. Görevler, on iki popüler açık kaynak Python projesinin GitHub depolarından alınmış gerçek hata kayıtlarıdır. Modele deponun hatanın düzeltilmesinden önceki hâli ve hata kaydının metni verilir; model koda bakıp bir yama üretmelidir. Yama, o hatayı düzelten gerçek değişiklikle birlikte eklenmiş testlerden geçerse ve var olan testleri bozmazsa görev çözülmüş sayılır.

**SWE-bench Verified** (2024), yazılım mühendislerinin tek tek inceleyerek doğruladığı 500 görevlik alt kümedir; belirsiz tanımlı ya da çözülemez görevler ayıklanmıştır.

:::svg Bir SWE-bench görevinin adımları: model hata kaydını okur, depoyu inceler, bir yama yazar; gizli testler önce başarısız, yamadan sonra başarılı olmalıdır.
<svg viewBox="0 0 720 300" role="img" aria-label="Beş adım: bir, hata kaydı okunur; iki, model depodaki dosyaları inceler; üç, bir yama yazar; dört, gizli testler çalıştırılır; beş, başarısız olan testler geçer ve var olan testler bozulmazsa görev çözülmüş sayılır" data-dur="14">
<g data-a="show .02"><rect x="20" y="20" width="200" height="110" rx="10" class="s-bg3 l-fg w2"/><text x="36" y="46" class="t-b">1 · Hata kaydı</text><text x="36" y="70" class="t-m">"Boş listede</text><text x="36" y="88" class="t-m">ortalama() çöküyor"</text><text x="36" y="114" class="t-s">gerçek bir GitHub kaydı</text></g>
<g data-a="show .2"><path d="M220 75h40m-8-5 8 5-8 5" class="l-mut w2 rc" fill="none"/><rect x="260" y="20" width="200" height="110" rx="10" class="s-bg3 l-fg w2"/><text x="276" y="46" class="t-b">2 · Depoyu incele</text><text x="276" y="70" class="t-m">stats/core.py</text><text x="276" y="88" class="t-m">tests/test_core.py</text><text x="276" y="114" class="t-s">binlerce dosya arasından</text></g>
<rect x="270" y="58" width="130" height="16" rx="3" class="s-none l-acc w2" data-a="show .26 .38"/>
<g data-a="show .4"><path d="M460 75h40m-8-5 8 5-8 5" class="l-mut w2 rc" fill="none"/><rect x="500" y="20" width="200" height="110" rx="10" class="s-soft l-acc w2"/><text x="516" y="46" class="t-b">3 · Yama yaz</text><text x="516" y="70" class="t-m t-red">- return sum(x)/len(x)</text><text x="516" y="88" class="t-m t-acc">+ if not x: return 0</text><text x="516" y="106" class="t-m t-acc">+ return sum(x)/len(x)</text></g>
<g data-a="show .58"><path d="M600 130v40" class="l-mut w2" fill="none"/><rect x="380" y="170" width="320" height="110" rx="10" class="s-bg3 l-fg w2"/><text x="396" y="196" class="t-b">4 · Gizli testler çalışır</text><text x="396" y="222" class="t-m">test_bos_liste</text><text x="396" y="244" class="t-m">test_tek_eleman</text><text x="396" y="266" class="t-m">test_negatif_sayilar</text></g>
<g data-a="show .64 .78"><text x="660" y="222" text-anchor="end" class="t-m t-red t-b">BAŞARISIZ</text></g>
<g data-a="show .78"><text x="660" y="222" text-anchor="end" class="t-m t-acc t-b">GEÇTİ</text></g>
<g data-a="show .7"><text x="660" y="244" text-anchor="end" class="t-m t-acc t-b">GEÇTİ</text><text x="660" y="266" text-anchor="end" class="t-m t-acc t-b">GEÇTİ</text></g>
<g data-a="show .84"><path d="M380 225h-40m8-5-8 5 8 5" class="l-mut w2 rc" fill="none"/><rect x="20" y="170" width="320" height="110" rx="10" class="s-acc"/><text x="36" y="200" class="t-b t-ink">5 · Sonuç: çözüldü</text><text x="36" y="228" class="t-s t-ink">Önceden başarısız olan test artık geçiyor</text><text x="36" y="248" class="t-s t-ink">ve var olan testlerin hiçbiri bozulmadı.</text><text x="36" y="268" class="t-s t-ink">Kısmi puan yoktur: ya çözüldü ya çözülmedi.</text></g>
</svg>
:::

**Nasıl okunur:** Skor, çözülen görevlerin yüzdesidir. En kritik soru, hangi **düzenekle** ölçüldüğüdür: modelin çevresindeki ajan yazılımı, verilen araçlar ve adım sınırı sonucu ciddi biçimde değiştirir. Görevlerin tamamı Python'dadır ve kaynak depolar herkese açık olduğu için sızıntı riski vardır. Yukarıdaki grafikte gördüğümüz gibi kıyaslama doygunluğa ulaşmıştır; bu yüzden SWE-bench Pro gibi daha zor türevleri ve aşağıdaki Terminal-Bench öne çıkmaktadır.

### Terminal-Bench

**Terminal-Bench**, modeli yalıtılmış bir Linux kapsayıcısının komut satırına bırakır ve gerçekçi görevler verir: bir yazılımı kaynak koddan derlemek, bozuk bir sunucu yapılandırmasını onarmak, bir veri işleme hattı kurmak, bir güvenlik açığını bulmak. Görev sonunda kapsayıcının durumu testlerle denetlenir. Kıyaslama, zorlaştırılan yeni sürümlerle güncellenmektedir.

:::ai
{"widget":"benchmark-chart","benchmark":"terminal_bench_4"}
:::

**Nasıl okunur:** Skorlar SWE-bench'e göre çok daha düşük ve birbirinden ayrıktır; yani sınav bugünün modellerini ayırt edebilmektedir. Tabloda aynı modelin farklı **düşünme ayarlarıyla** ayrı ayrı yer aldığına ve sonuçların birkaç puan değiştiğine dikkat edin: “hangi model” kadar “hangi ayarla” sorusu da önemlidir.

### Aider Polyglot

Açık kaynaklı kodlama aracı Aider'ın geliştiricilerinin hazırladığı bu kıyaslama, Exercism alıştırma sitesinin en zor 225 probleminden oluşur ve altı dili kapsar: C++, Go, Java, JavaScript, Python ve Rust. Modele iki deneme hakkı verilir; ilk denemede testler başarısız olursa hata çıktısını görüp düzeltebilir.

**Nasıl okunur:** İki ayrı şey ölçülür: problemi çözme oranı ve modelin dosyaları **doğru biçimde düzenleyebilme** oranı. İkincisi, bir modelin kodlama araçlarıyla ne kadar sorunsuz çalışacağının pratik bir göstergesidir.

## Ajanlar ve araç kullanımı

Bu kıyaslamalar tek bir yanıtı değil, modelin araçlar kullanarak çok adımlı bir işi baştan sona tamamlamasını ölçer.

### τ-bench (tau-bench)

Sierra'nın 2024'te yayımladığı **τ-bench**, müşteri hizmetleri senaryolarını benzetir: bir perakende mağazası ve bir havayolu. Model bir müşteri temsilcisi rolündedir; karşısında başka bir dil modelinin canlandırdığı bir müşteri vardır. Temsilci, veritabanını sorgulayan ve değiştiren araçları kullanarak isteği yerine getirmeli, bunu yaparken de kendisine verilen **şirket kurallarına** uymalıdır (örneğin “ekonomi sınıfı biletler değiştirilemez”). Görev sonunda veritabanının durumu beklenenle karşılaştırılır.

**Nasıl okunur:** τ-bench'in özgün katkısı **pass^k** ölçütüdür: aynı görev *k* kez yinelendiğinde modelin **her seferinde** başarılı olma oranı. Bir kez başaran ama sekiz denemenin yarısında hata yapan bir temsilci, gerçek bir işletme için güvenilir değildir. Sonraki sürümleri (τ² ve τ³) telekom ve bankacılık gibi alanlar eklemiştir.

### OSWorld

**OSWorld** (2024), modele gerçek bir masaüstü işletim sistemi verir: model ekran görüntülerine bakar, fare ve klavye komutları üretir. 369 görev; hesap tablosu düzenlemek, dosya yönetmek, bir e-postanın ekini başka bir uygulamada açmak gibi gündelik bilgisayar işlerini kapsar. Kıyaslamayı hazırlayanların ölçümünde insanların başarı oranı yaklaşık yüzde 72'dir.

**Nasıl okunur:** Adım sayısı sınırına ve modelin yalnızca ekran görüntüsüyle mi yoksa ek erişilebilirlik bilgisiyle mi çalıştığına bakın. Görevlerin tanım ve denetim hataları düzeltilerek yayımlanan “Verified” sürümünün skorları, özgün sürümle doğrudan karşılaştırılamaz.

### WebArena

**WebArena** (2023), modelin tarayıcıda görev yapmasını ölçer. Gerçek siteleri kullanmak yerine, bir alışveriş sitesi, bir forum, bir kod barındırma platformu ve bir içerik yönetim sisteminin kendi sunucularında çalışan kopyalarını kullanır; böylece sonuçlar yinelenebilir. 812 görev içerir (“en çok satan ürünün yorumlarını özetle”, “şu depoya bir sorun kaydı aç”). İnsan başarı oranı yaklaşık yüzde 78 olarak ölçülmüştür.

### BrowseComp

OpenAI'ın 2025'te yayımladığı **BrowseComp**, 1.266 sorudan oluşur. Sorular, yanıtı internette **bulunması çok zor** ama bulunduğunda doğrulanması kolay olacak biçimde tersten kurgulanmıştır: birkaç koşulu aynı anda sağlayan bir kişi, makale ya da olay sorulur ve yanıt onlarca sayfayı gezip ipuçlarını birleştirmeyi gerektirir.

**Nasıl okunur:** Arama aracı olmayan bir modelin skoru sıfıra yakındır. Sınav, tek bir aramayı değil, sebatı ve arama stratejisini ölçer; “derin araştırma” ürünlerinin karşılaştırılmasında kullanılır.

## Çok kipli anlama

### MMMU

**MMMU** (2023), 30 üniversite dersinden yaklaşık 11.500 görsel içeren soru barındırır: devre şemaları, tıbbi görüntüler, kimyasal yapılar, grafikler, nota yazıları. Model yalnızca görseli görmekle kalmamalı, alan bilgisini kullanarak akıl yürütmelidir. **MMMU-Pro**, yalnızca metinle çözülebilen soruları ayıklar, seçenek sayısını artırır ve soruyu görselin içine gömülü hâlde sorar.

:::ai
{"widget":"benchmark-chart","benchmark":"mmmu_pro"}
:::

### MathVista

**MathVista** (2023), görsel bağlamda matematiksel akıl yürütmeyi ölçen yaklaşık 6.000 örnekten oluşur: bir grafikten değer okuyup hesap yapmak, bir geometri şeklindeki açıyı bulmak, bir tablodaki verilerle soru yanıtlamak.

### Video-MME

**Video-MME** (2024), 900 video ve 2.700 çoktan seçmeli soru içerir. Videolar kısa (iki dakikadan az), orta ve uzun (bir saate kadar) olmak üzere üç gruptadır. Uzun videolar, modelin bir saatlik içerik boyunca bilgiyi aklında tutup tutamadığını sınar.

**Nasıl okunur:** Çok kipli sınavlarda, sorunun görsele **gerçekten** ihtiyaç duyup duymadığı önemlidir. Skorun altyazılı mı altyazısız mı ölçüldüğüne ve videodan kaç kare örneklendiğine bakın.

## Uzun bağlam

Bir modelin bir milyon belirteçlik bağlam penceresi olması, o pencerenin tamamını iyi kullanabildiği anlamına gelmez.

### Needle-in-a-Haystack

En basit sınamadır: çok uzun bir metnin (samanlık) rastgele bir yerine ilgisiz bir cümle (iğne) gizlenir ve modele o cümledeki bilgi sorulur. Metnin uzunluğu ve iğnenin konumu değiştirilerek bir ısı haritası çıkarılır. Güncel modeller bu sınamayı neredeyse kusursuz geçer; artık ayırt edici değildir.

### RULER

NVIDIA araştırmacılarının 2024'te yayımladığı **RULER**, iğne fikrini zorlaştırır: birden çok iğneyi bulmak, birbirine zincirlenmiş değişkenleri izlemek, uzun bir metindeki en sık geçen sözcükleri saymak gibi 13 görev içerir. Görevler yapay olarak üretildiği için uzunluk istenen düzeye ayarlanabilir ve sızıntı sorunu yoktur. RULER, pek çok modelin **iddia ettiği** bağlam uzunluğunun çok altında bozulmaya başladığını göstermiştir.

### MRCR

**MRCR** (çok turlu eşgönderim çözümleme), uzun bir sohbetin içine birbirine çok benzeyen istekler gizler; örneğin kullanıcı farklı noktalarda sekiz kez “tapirler hakkında bir şiir yaz” der. Modelden sonunda “üçüncü tapir şiirini aynen yaz” istenir. Model yalnızca bilgiyi bulmamalı, birbirine çok benzeyen parçaları **sırasıyla ayırt etmelidir**.

**Nasıl okunur:** Uzun bağlam sınavlarında skor her zaman bir **uzunlukla** birlikte verilir. 128 bin belirteçte yüzde 95 alan bir model, bir milyon belirteçte yüzde 60'a düşebilir. Kendi belge uzunluğunuza karşılık gelen değere bakın.

:::ai
{"widget":"benchmark-chart","benchmark":"aa_lcr"}
:::

Yukarıdaki grafik, Artificial Analysis'in yaklaşık 100 bin belirteçlik belge kümelerinde birden çok belgeyi birleştirmeyi gerektiren kendi uzun bağlam kıyaslamasının sonuçlarını gösterir.

## İnsan tercihi: LMArena

**LMArena** (eski adıyla LMSYS Chatbot Arena), 2023'te Berkeley'deki araştırmacıların başlattığı ve bugün bağımsız bir şirket olarak süren bir platformdur. Yöntemi yalındır: kullanıcı bir soru yazar, iki **anonim** model yanıt verir, kullanıcı hangisinin daha iyi olduğunu seçer; model adları ancak oydan sonra açıklanır. Milyonlarca oydan her model için bir puan hesaplanır.

### Elo sistemi nasıl çalışır?

Puanlama, satrançtan gelen **Elo** sistemine dayanır. Temel fikir: bir karşılaşmanın sonucu ne kadar **beklenmedikse**, puanlar o kadar çok değişir.

İki modelin puan farkından, her birinin beklenen kazanma olasılığı hesaplanır. 100 puan fark, güçlü olanın yaklaşık yüzde 64 olasılıkla; 400 puan fark, yaklaşık yüzde 91 olasılıkla kazanacağı anlamına gelir. Sonuç açıklandığında her modelin puanı, gerçek sonuç ile beklenti arasındaki farkla orantılı olarak güncellenir.

:::svg Elo güncellemesi: favori kazandığında puanlar az değişir; zayıf görülen model kazandığında puanlar çok daha fazla değişir.
<svg viewBox="0 0 720 320" role="img" aria-label="İki senaryo. Birincisinde 1500 puanlı model A, 1400 puanlı model B'yi yener: beklenen olasılık yüzde 64 olduğu için A yalnızca 12 puan kazanır. İkincisinde B kazanır: beklenmedik sonuç olduğu için B 20 puan kazanır, A 20 puan kaybeder" data-dur="14">
<text x="20" y="28" class="t-b">Başlangıç</text>
<rect x="150" y="10" width="150" height="56" rx="10" class="s-bg3 l-blue w3"/><text x="225" y="34" text-anchor="middle" class="t-b">Model A</text><text x="225" y="56" text-anchor="middle" class="t-m">1500</text>
<rect x="420" y="10" width="150" height="56" rx="10" class="s-bg3 l-acc2 w3"/><text x="495" y="34" text-anchor="middle" class="t-b">Model B</text><text x="495" y="56" text-anchor="middle" class="t-m">1400</text>
<text x="360" y="34" text-anchor="middle" class="t-s">beklenen</text><text x="360" y="54" text-anchor="middle" class="t-s t-b">%64 – %36</text>
<path d="M20 86h680" class="l-line w1 dash" fill="none"/>
<g data-a="show .06"><text x="20" y="118" class="t-b">Senaryo 1</text><text x="20" y="138" class="t-s">A kazanır (beklenen)</text>
<rect x="150" y="100" width="150" height="56" rx="10" class="s-blue"/><text x="225" y="124" text-anchor="middle" class="t-b t-w">1512</text><text x="225" y="146" text-anchor="middle" class="t-s t-w">+12</text>
<rect x="420" y="100" width="150" height="56" rx="10" class="s-bg3 l-acc2 w2"/><text x="495" y="124" text-anchor="middle" class="t-b">1388</text><text x="495" y="146" text-anchor="middle" class="t-s">−12</text>
<text x="590" y="132" class="t-s">küçük değişim</text></g>
<rect x="306" y="120" width="12" height="16" rx="3" class="s-blue" data-o="l" data-a="grow x .1 .2"/>
<path d="M20 180h680" class="l-line w1 dash" fill="none"/>
<g data-a="show .5"><text x="20" y="212" class="t-b">Senaryo 2</text><text x="20" y="232" class="t-s">B kazanır (sürpriz)</text>
<rect x="150" y="194" width="150" height="56" rx="10" class="s-bg3 l-blue w2"/><text x="225" y="218" text-anchor="middle" class="t-b">1480</text><text x="225" y="240" text-anchor="middle" class="t-s">−20</text>
<rect x="420" y="194" width="150" height="56" rx="10" class="s-acc2"/><text x="495" y="218" text-anchor="middle" class="t-b t-w">1420</text><text x="495" y="240" text-anchor="middle" class="t-s t-w">+20</text>
<text x="590" y="226" class="t-s">büyük değişim</text></g>
<text x="20" y="284" class="t-s">Formül: yeni puan = eski puan + K × (sonuç − beklenen olasılık). Örnekte K = 32; kazanan için sonuç = 1.</text>
<text x="20" y="304" class="t-s">Senaryo 1: 32 × (1 − 0,64) ≈ +12 · Senaryo 2: 32 × (1 − 0,36) ≈ +20</text>
</svg>
:::

LMArena bugün klasik Elo'nun istatistiksel olarak daha sağlam bir akrabası olan Bradley-Terry modelini kullanır: puanlar oyların sırasına bağlı kalmadan, bütün oylar birlikte değerlendirilerek hesaplanır ve her puanın yanında bir güven aralığı verilir. Yorumlama biçimi aynıdır.

:::ai
{"widget":"benchmark-table","benchmark":"lmarena_text"}
:::

**Nasıl okunur:**

- **Puanın kendisi değil, fark önemlidir.** 1526 ile 1522 arasındaki dört puanlık fark, güven aralıklarının içindedir; iki model fiilen eşittir. Yaklaşık 20 puandan küçük farkları sıralama olarak yorumlamayın.
- **Arena tercihi ölçer, doğruluğu değil.** İnsanlar akıcı, kendinden emin, iyi biçimlendirilmiş ve uzun yanıtları tercih etme eğilimindedir; bu yanıtların doğru olup olmadığını çoğu zaman denetlemezler.
- **Sorular gündelik sorulardır.** Arena puanı, uzmanlık gerektiren ya da çok adımlı işlerdeki yeteneği zayıf yansıtır. Bu yüzden kodlama, yaratıcı yazım, arama ve görsel anlama için ayrı arenalar vardır.
- **Kimlerin oy verdiği** önemlidir: oy verenler dünya nüfusunun rastgele bir örneği değildir.
- 2025'te yayımlanan bir çalışma, büyük sağlayıcıların çok sayıda özel sürümü arenada deneyip yalnızca en iyisini yayımlayabildiğini ve bunun sıralamayı onların lehine etkileyebileceğini savunmuştur; platform bu eleştirilere yöntemini açıklayarak yanıt vermiştir.

## Görsel, video ve ses üretimi

Üretilen bir görselin “iyi” olup olmadığını tek bir doğru yanıtla ölçmek mümkün değildir. Bu alanda iki tür ölçüt kullanılır.

### Otomatik ölçütler: FID ve CLIP skoru

**FID** (Fréchet Inception Distance, 2017), üretilen görsellerin istatistiksel dağılımının gerçek fotoğraflarınkine ne kadar benzediğini ölçer. Binlerce üretilmiş ve gerçek görsel bir görüntü tanıma ağından geçirilir ve iki kümenin özellik dağılımları arasındaki uzaklık hesaplanır. **Düşük olan iyidir**; sıfır, iki dağılımın aynı olduğu anlamına gelir.

**CLIP skoru**, görselin metne ne kadar **uyduğunu** ölçer. CLIP adlı model hem görseli hem metni aynı vektör uzayına gömer; ikisinin vektörleri arasındaki benzerlik skoru verir. **Yüksek olan iyidir.**

İkisinin de ciddi sınırları vardır. FID tek bir görseli değerlendiremez, yalnızca büyük kümeleri karşılaştırır ve dayandığı ağın eski olması nedeniyle estetik kaliteyi, el ve yazı gibi ayrıntılardaki hataları iyi yakalayamaz. CLIP skoru ise “kedinin solunda duran köpek” gibi uzamsal ilişkileri ve sayıları zayıf ölçer. Videoda FID'in karşılığı olan **FVD**, zamansal tutarlılığı da hesaba katar. Konuşma üretiminde ise anlaşılırlık, üretilen sesin yeniden yazıya dökülmesiyle hesaplanan **sözcük hata oranı** (WER) ile, doğallık ise insan dinleyicilerin verdiği **ortalama görüş puanı** (MOS) ile ölçülür.

### Arena sıralamaları

Otomatik ölçütlerin yetersizliği nedeniyle bu alanda da insan tercihi öne çıkmıştır. Görsel, video ve ses arenalarında kullanıcıya aynı istemle üretilmiş iki anonim çıktı gösterilir ve hangisini tercih ettiği sorulur; sonuçlar Elo puanına dönüştürülür.

:::ai
{"widget":"benchmark-chart","benchmark":"aa_t2i"}
:::

**Nasıl okunur:** Bu arenalar genel beğeniyi ölçer. Sizin işiniz belirli bir şey gerektiriyorsa (görsel içinde doğru yazı, belirli bir üslup, marka tutarlılığı, bir karakterin sahneler arasında aynı kalması) genel sıralama yanıltabilir. Fiyata da bakın: yukarıdaki sıralamada en üstteki model ile birkaç sıra altındaki model arasında görsel başına maliyet kat kat değişir.

## Kıyaslamaların özeti

| Kıyaslama | Kategori | Ne ölçer | Puanlama | Bugünkü durumu |
|---|---|---|---|---|
| MMLU / MMLU-Pro | Bilgi | 57 alanda akademik bilgi | Çoktan seçmeli doğruluk | MMLU doygun |
| GPQA Diamond | Akıl yürütme | Doktora düzeyi fen soruları | Çoktan seçmeli doğruluk | Uzman düzeyi aşıldı |
| HLE | Akıl yürütme | Uzmanların yazdığı çok zor sorular | Kesin yanıt eşleşmesi | Ayırt edici |
| ARC-AGI-2 / 3 | Akıl yürütme | Yeni kuralları keşfedip uygulama | Doğruluk ve görev maliyeti | ARC-AGI-3 çok ayırt edici |
| GSM8K, MATH | Matematik | Okul ve yarışma düzeyi problemler | Kesin yanıt | Doygun |
| AIME | Matematik | Lise yarışma sınavı (15 soru) | Tam sayı yanıt | Üst düzeyde doygun |
| FrontierMath | Matematik | Yayımlanmamış ileri düzey problemler | Otomatik doğrulama | Kademe 4 ayırt edici |
| HumanEval | Kodlama | Tek fonksiyonluk Python görevleri | Birim testleri | Doygun |
| LiveCodeBench | Kodlama | Sürekli yenilenen yarışma problemleri | Birim testleri | Sızıntıya dirençli |
| SWE-bench Verified | Kodlama | Gerçek depolarda hata düzeltme | Gizli testler | Doygunluğa ulaştı |
| Terminal-Bench | Kodlama / ajan | Komut satırında gerçekçi görevler | Durum denetimi | Ayırt edici |
| Aider Polyglot | Kodlama | Altı dilde kod düzenleme | Birim testleri | Üst düzeyde yüksek |
| τ-bench | Ajan | Kurallara uyarak müşteri hizmeti | Veritabanı durumu, pass^k | Güvenilirliği ölçer |
| OSWorld | Ajan | Masaüstü bilgisayar kullanımı | Durum denetimi | Ayırt edici |
| WebArena | Ajan | Tarayıcıda görev tamamlama | Durum denetimi | Ayırt edici |
| BrowseComp | Ajan | Zor bulunan bilgiyi webde arama | Kesin yanıt | Arama araçlarını ölçer |
| MMMU / MMMU-Pro | Çok kipli | Görselli üniversite soruları | Çoktan seçmeli doğruluk | Pro sürümü ayırt edici |
| MathVista | Çok kipli | Görsel bağlamda matematik | Doğruluk | Üst düzeyde yüksek |
| Video-MME | Çok kipli | Bir saate kadar video anlama | Çoktan seçmeli doğruluk | Uzun videolarda ayırt edici |
| Needle-in-a-Haystack | Uzun bağlam | Uzun metinde tek bir bilgiyi bulma | Doğruluk | Doygun |
| RULER, MRCR | Uzun bağlam | Uzun bağlamda izleme ve ayırt etme | Uzunluğa göre doğruluk | Ayırt edici |
| LMArena | İnsan tercihi | Anonim ikili karşılaştırmada beğeni | Elo | Sürekli güncellenir |
| FID, CLIP skoru | Üretim | Görsel gerçekçiliği ve metne uyum | Dağılım uzaklığı, benzerlik | Sınırlı; arenalar tercih edilir |

## Hangi kıyaslamaya bakmalıyım?

| İhtiyacınız | Bakmanız gereken kıyaslamalar |
|---|---|
| Kod yazdırmak, kodlama ajanı kullanmak | Terminal-Bench, SWE-bench türevleri, Aider Polyglot, LMArena kodlama arenası |
| Zor matematik ve bilim problemleri | FrontierMath, HLE, GPQA |
| Yeni ve alışılmadık problemlere uyum | ARC-AGI-2 ve ARC-AGI-3 |
| Müşteri hizmetleri, iş akışı otomasyonu | τ-bench (özellikle pass^k) |
| Bilgisayar ve tarayıcı kullanan ajanlar | OSWorld, WebArena |
| Kaynaklı web araştırması | BrowseComp, LMArena arama arenası |
| Görsel, PDF ve grafik okuma | MMMU-Pro, MathVista |
| Uzun belgelerle çalışma | RULER, MRCR, AA-LCR; kendi belge uzunluğunuzdaki değerler |
| Gündelik sohbet ve yazı | LMArena metin ve yaratıcı yazım arenaları |
| Görsel, video ve ses üretimi | İlgili arena sıralamaları ve fiyat |

## Bir kıyaslama skorunu doğru okuma kontrol listesi

Bir duyuruda ya da haberde bir skor gördüğünüzde şu soruları sorun:

+ **Kim ölçtü?** Üretici mi, bağımsız bir kuruluş mu? Kıyaslamayı kim finanse etti?
+ **Hangi sürüm ve hangi alt küme?** “SWE-bench” ile “SWE-bench Verified”, “MMMU” ile “MMMU-Pro” aynı sınav değildir.
+ **Hangi düzenekle?** Modele hangi araçlar verildi, kaç deneme hakkı tanındı, hangi düşünme ayarı kullanıldı?
+ **Ne zaman ölçüldü?** Aylar önceki bir skor, bugünkü model sürümünü yansıtmayabilir.
+ **Şans düzeyi ve insan düzeyi nedir?** Dört seçenekli bir sınavda yüzde 25 sıfır demektir.
+ **Kıyaslama doygun mu?** En iyi modellerin hepsi yüzde 90'ın üzerindeyse aradaki fark bir şey söylemez.
+ **Fark anlamlı mı?** Soru sayısı kaç, güven aralığı verilmiş mi? Birkaç puanlık ya da birkaç Elo'luk fark çoğu zaman gürültüdür.
+ **Sızıntı olasılığı var mı?** Sorular herkese açık mı, modelin bilgi kesim tarihinden önce mi yayımlandı?
+ **Maliyet ve süre ne?** Aynı skora on kat fazla ücretle ve on kat uzun sürede ulaşılmış olabilir.
+ **Tek bir denemenin mi, yinelenen denemelerin mi sonucu?** Güvenilirlik, tek seferlik başarıdan farklıdır.
+ **Bu sınav benim işime benziyor mu?** En önemli soru budur. Hiçbir genel kıyaslama, kendi verinizle ve kendi görevinizle yapacağınız küçük bir denemenin yerini tutmaz.

:::tip Kendi küçük kıyaslamanızı kurun
Gerçekten yaptığınız işten yirmi ile elli arasında örnek toplayın, doğru yanıtları belirleyin ve aday modelleri bu örneklerle deneyin. Bu küçük küme, sızıntıya uğramamış olduğu ve tam olarak sizin ihtiyacınızı ölçtüğü için, herhangi bir genel sıralamadan daha değerli bir karar aracıdır.
:::

## Sık sorulan sorular

:::faq
Q: Bir model bir kıyaslamada birinciyse en iyi model o mudur?
A: Yalnızca o kıyaslamanın ölçtüğü şeyde ve o ölçüm koşullarında. Farklı kıyaslamalarda farklı modeller öne çıkar. “En iyi model” sorusu ancak belirli bir iş için sorulduğunda anlamlıdır.
Q: Üretici beyanı skorlara güvenmeli miyim?
A: Tümüyle yok saymak gerekmez, ama bağımsız bir ölçümle doğrulanana kadar ihtiyatla karşılamak gerekir. Üreticiler en elverişli düzeneği ve ayarı seçebilir. Bağımsız kuruluşların aynı koşullarda yaptığı ölçümler daha güvenilirdir.
Q: Veri sızıntısı olduğunu nasıl anlarım?
A: Kesin olarak anlamak zordur. İşaretlerden biri, modelin eski ve herkese açık sorularda çok yüksek, bilgi kesim tarihinden sonra yayımlanan benzer sorularda belirgin biçimde düşük skor almasıdır. Gizli ya da sürekli yenilenen soru kümeleri kullanan kıyaslamalar bu riske karşı daha dayanıklıdır.
Q: Elo puanındaki 50 puanlık fark ne anlama gelir?
A: Güçlü olan modelin, ikili bir karşılaştırmada yaklaşık yüzde 57 olasılıkla tercih edileceği anlamına gelir. Yani on karşılaştırmanın yaklaşık altısında. Fark gerçektir ama ezici değildir.
Q: Kıyaslamalar neden bu kadar çabuk eskiyor?
A: Modeller hızla geliştiği için sınavlar birkaç yıl, bazen birkaç ay içinde doygunluğa ulaşıyor. Ayrıca yayımlanan her sınavın soruları zamanla eğitim verilerine karışabiliyor. Bu yüzden alan sürekli daha zor ve sızıntıya daha dirençli sınavlar üretiyor.
Q: Bileşik endeksler ne işe yarar?
A: Artificial Analysis Intelligence Index ya da Epoch Capabilities Index gibi endeksler, birçok kıyaslamayı tek bir sayıda birleştirir. Genel bir bakış için kullanışlıdırlar, ama hangi sınavların hangi ağırlıkla katıldığını bilmeden yorumlanmamalıdır; iki modelin toplam puanı aynı olup güçlü oldukları alanlar tümüyle farklı olabilir.
Q: Türkçe için bir kıyaslama var mı?
A: Çok dilli sınavların Türkçe bölümleri ve akademik gruplarca hazırlanmış Türkçe kümeler vardır, ancak güncel üst düzey modellerin tamamını aynı koşullarda ve düzenli olarak ölçen, yaygın kabul görmüş bağımsız bir Türkçe sıralaması bulunmamaktadır. Türkçe kullanım için en güvenilir yöntem, kendi örneklerinizle deneme yapmaktır.
:::

## Özet

- Kıyaslama, modelleri karşılaştırmak için hazırlanmış standart bir sınavdır; soru kümesi, değerlendirme düzeneği ve puanlama yönteminden oluşur.
- Skorları dört şey bozabilir: veri sızıntısı, doygunluk, üretici beyanı ile bağımsız ölçüm farkı ve kıyaslamaya göre eniyileme.
- Aynı sınavın skoru düzeneğe ve düşünme ayarına göre ciddi biçimde değişir; “kim, hangi koşulda ölçtü” sorusu skorun kendisi kadar önemlidir.
- MMLU, GSM8K, HumanEval ve SWE-bench Verified doygunluğa ulaşmıştır; HLE, ARC-AGI-3, Terminal-Bench ve FrontierMath'in üst kademesi bugün ayırt edicidir.
- LMArena, insan tercihini Elo ile ölçer; küçük puan farkları anlamsızdır ve tercih doğrulukla aynı şey değildir.
- Görsel, video ve ses üretiminde otomatik ölçütler sınırlıdır; arena sıralamaları ve fiyat birlikte değerlendirilmelidir.
- En güvenilir kıyaslama, kendi işinizden topladığınız örneklerle yaptığınız denemedir.

Kıyaslamaları nasıl okuyacağınızı öğrendiniz. Dizinin son yazısında bu bilgiyi uyguluyoruz: [Best AI for Every Task](/post/best-ai-for-every-task), güncel modelleri kullanım alanlarına göre karşılaştırır.

## Kaynaklar

- [ARC Prize – Leaderboard](https://arcprize.org/leaderboard)
- [Artificial Analysis – LLM Leaderboard](https://artificialanalysis.ai/leaderboards/models)
- [Artificial Analysis – Text to Image Leaderboard](https://artificialanalysis.ai/image/leaderboard/text-to-image)
- [Vals AI – SWE-bench Verified](https://www.vals.ai/benchmarks/swebench)
- [SWE-bench resmî sitesi ve sıralamaları](https://www.swebench.com/)
- [LMArena (arena.ai) – Text Arena](https://arena.ai/leaderboard/chat/text)
- [BenchLeader – kıyaslama sıralamaları dizini (LMArena, Epoch AI ve Artificial Analysis verilerinin derlemesi)](https://www.benchleader.com/benchmarks)
- [Epoch AI – FrontierMath](https://epoch.ai/frontiermath)
- [Epoch AI – Benchmarking Hub](https://epoch.ai/benchmarks)
- [Hendrycks ve ark. (2020) – “Measuring Massive Multitask Language Understanding” (MMLU)](https://arxiv.org/abs/2009.03300)
- [Wang ve ark. (2024) – “MMLU-Pro”](https://arxiv.org/abs/2406.01574)
- [Rein ve ark. (2023) – “GPQA: A Graduate-Level Google-Proof Q&A Benchmark”](https://arxiv.org/abs/2311.12022)
- [Phan ve ark. (2025) – “Humanity's Last Exam”](https://arxiv.org/abs/2501.14249)
- [Chollet (2019) – “On the Measure of Intelligence” (ARC)](https://arxiv.org/abs/1911.01547)
- [Cobbe ve ark. (2021) – “Training Verifiers to Solve Math Word Problems” (GSM8K)](https://arxiv.org/abs/2110.14168)
- [Chen ve ark. (2021) – “Evaluating Large Language Models Trained on Code” (HumanEval)](https://arxiv.org/abs/2107.03374)
- [Jain ve ark. (2024) – “LiveCodeBench”](https://arxiv.org/abs/2403.07974)
- [Jimenez ve ark. (2023) – “SWE-bench: Can Language Models Resolve Real-World GitHub Issues?”](https://arxiv.org/abs/2310.06770)
- [OpenAI (2024) – “Introducing SWE-bench Verified”](https://openai.com/index/introducing-swe-bench-verified/)
- [Terminal-Bench](https://www.tbench.ai/)
- [Aider – Polyglot leaderboard](https://aider.chat/docs/leaderboards/)
- [Yao ve ark. (2024) – “τ-bench”](https://arxiv.org/abs/2406.12045)
- [Xie ve ark. (2024) – “OSWorld”](https://arxiv.org/abs/2404.07972)
- [Zhou ve ark. (2023) – “WebArena”](https://arxiv.org/abs/2307.13854)
- [OpenAI (2025) – “BrowseComp: a benchmark for browsing agents”](https://openai.com/index/browsecomp/)
- [Yue ve ark. (2023) – “MMMU”](https://arxiv.org/abs/2311.16502)
- [Lu ve ark. (2023) – “MathVista”](https://arxiv.org/abs/2310.02255)
- [Fu ve ark. (2024) – “Video-MME”](https://arxiv.org/abs/2405.21075)
- [Hsieh ve ark. (2024) – “RULER: What's the Real Context Size of Your Long-Context Language Models?”](https://arxiv.org/abs/2404.06654)
- [Vodrahalli ve ark. (2024) – “Michelangelo: Long Context Evaluations Beyond Haystacks” (MRCR)](https://arxiv.org/abs/2409.12640)
- [Chiang ve ark. (2024) – “Chatbot Arena: An Open Platform for Evaluating LLMs by Human Preference”](https://arxiv.org/abs/2403.04132)
- [Singh ve ark. (2025) – “The Leaderboard Illusion”](https://arxiv.org/abs/2504.20879)
- [Heusel ve ark. (2017) – “GANs Trained by a Two Time-Scale Update Rule…” (FID)](https://arxiv.org/abs/1706.08500)
- [Hessel ve ark. (2021) – “CLIPScore”](https://arxiv.org/abs/2104.08718)
