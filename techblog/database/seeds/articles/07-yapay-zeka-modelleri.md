---
title: Every AI Model Explained: Yapay Zekâ Model Türleri ve Nasıl Çalıştıkları
slug: every-ai-model-explained
category: yapay-zeka
tags: yapay zekâ, llm, difüzyon, embedding, bağlam penceresi, açık kaynak
description: Dil modelleri (GPT, Claude, Gemini, Llama, Mistral, DeepSeek), görsel, video, ses ve embedding modelleri; açık ve kapalı modeller, parametre ve bağlam penceresi.
excerpt: LLM nedir, difüzyon modeli nasıl resim üretir, embedding ne işe yarar? Dil, görsel, video, ses ve embedding modellerinin nasıl çalıştığını; açık ve kapalı model ayrımını, parametre ve bağlam penceresi kavramlarını sade bir dille anlatıyoruz.
cover: /assets/covers/ai-models.svg
cover_alt: Katmanlar hâlinde birbirine bağlı düğümlerden oluşan yapay sinir ağı çizimi
series: Every X Explained
series_order: 7
featured: 1
date: 2026-10-26 09:00
---
“Yapay zekâ” dediğimizde bugün çoğu zaman tek bir şeyi, sohbet eden bir dil modelini kastediyoruz. Oysa metin yazan, resim çizen, video üreten, konuşmayı yazıya döken ve belgeleri anlamlarına göre arayan sistemler birbirinden oldukça farklı çalışan model türleridir. Hangi işe hangi modelin uygun olduğunu anlamak için önce bu türleri tanımak gerekir.

Bu yazı bilerek **kavramsal** tutulmuştur: hangi model türü nedir ve nasıl çalışır sorusunu yanıtlar. Belirli modellerin güncel karşılaştırması hızla eskidiği için ayrı ve düzenli güncellenen iki yazıda yer alır. Bu yazı, üç bölümlük bir dizinin ilk parçasıdır:

1. **Model türleri ve çalışma biçimleri** (bu yazı)
2. [Every AI Benchmark Explained](/post/every-ai-benchmark-explained): kıyaslamalar neyi ölçer, skorlar nasıl okunur?
3. [Best AI for Every Task](/post/best-ai-for-every-task): hangi iş için hangi model, güncel verilerle.

## Temel kavramlar

### Model nedir?

Bir **yapay zekâ modeli**, veriden öğrenilmiş çok büyük bir matematiksel fonksiyondur. Girdi alır (bir metin, bir görsel, bir ses kaydı), bunu sayılara çevirir, milyarlarca çarpma ve toplama işleminden geçirir ve bir çıktı üretir. Geleneksel yazılımdan farkı şudur: kuralları bir programcı yazmaz; model, örneklerden kendisi çıkarır.

Modellerin neredeyse tamamı **yapay sinir ağlarıdır**: katmanlar hâlinde dizilmiş, birbirine bağlı basit hesap birimleri. Her bağlantının bir **ağırlığı** vardır ve öğrenme dediğimiz şey, bu ağırlıkların ayarlanmasından ibarettir.

### Parametre

**Parametre**, modelin öğrenme sırasında ayarlanan sayılarıdır; yani o ağırlıklar. “70 milyar parametreli model” dendiğinde, dosyasında 70 milyar ayarlanmış sayı bulunan bir model kastedilir. Parametre sayısı modelin kapasitesi hakkında kaba bir fikir verir, ama zekâsının doğrudan ölçüsü değildir: daha iyi eğitilmiş küçük bir model, kötü eğitilmiş büyük bir modeli geçebilir.

Parametre sayısı pratikte en çok donanım gereksinimini belirler. Her parametre standart duyarlıkta 2 bayt yer kaplar; 70 milyar parametreli bir model yaklaşık 140 GB bellek ister. **Nicemleme** (quantization) ile parametreler 4 bite kadar sıkıştırılabilir; bu, aynı modeli kabaca 40 GB'a indirir ve kaliteden az bir ödün verir.

:::chart
{"type":"bar","log":true,"title":"Açıklanmış parametre sayıları: seçilmiş açık modeller","labels":["BERT-large (2018)","GPT-2 (2019)","Mistral 7B (2023)","Llama 2 70B (2023)","GPT-3 (2020)","Llama 3.1 405B (2024)","DeepSeek-V3 (2024)"],"datasets":[{"label":"Parametre (milyar)","data":[0.34,1.5,7.3,70,175,405,671]}],"unit":"milyar","yTitle":"Parametre sayısı (milyar, logaritmik)","source":"Modellerin özgün makaleleri ve model kartları (Google, OpenAI, Mistral AI, Meta, DeepSeek)","sourceUrl":"https://arxiv.org/abs/2005.14165","date":"2018–2024","note":"Yalnızca üreticisinin parametre sayısını açıkladığı modeller gösterilmiştir. Kapalı modellerin çoğu bu sayıyı açıklamaz."}
:::

### Eğitim ve çıkarım

Bir modelin hayatında iki ayrı aşama vardır. **Eğitim** (training), modelin dev veri kümeleri üzerinde ağırlıklarını ayarladığı, haftalar süren ve binlerce ekran kartı gerektiren aşamadır. **Çıkarım** (inference) ise eğitilmiş modelin kullanılmasıdır: siz bir soru sorarsınız, model yanıt üretir. Çıkarım sırasında model yeni bir şey öğrenmez; ağırlıkları sabittir. Bir sohbette söylediklerinizi “hatırlaması”, öğrenmesinden değil, konuşma geçmişinin her seferinde yeniden girdi olarak verilmesinden kaynaklanır.

Eğitimin de kendi içinde aşamaları vardır. **Ön eğitimde** model, genel dil ya da görüntü bilgisini devasa bir veri kümesinden edinir. **İnce ayarda** (fine-tuning) bu genel model, daha küçük ve özel bir veriyle belirli bir işe uyarlanır.

### Belirteç (token)

Dil modelleri metni harf harf ya da sözcük sözcük değil, **belirteç** adı verilen parçalar hâlinde işler. Bir belirteç kısa bir sözcük, bir sözcük parçası ya da bir noktalama işareti olabilir. İngilizcede bir belirteç ortalama dört karaktere denk gelir. Türkçe gibi eklemeli dillerde sözcükler daha çok parçaya bölündüğü için aynı metin genellikle daha fazla belirteç tutar. Modellerin hem bağlam sınırı hem de API ücretleri belirteç üzerinden hesaplanır.

### Bağlam penceresi

**Bağlam penceresi**, modelin tek seferde “görebildiği” en fazla belirteç sayısıdır: sistem yönergesi, konuşma geçmişi, yüklediğiniz belgeler ve modelin yazmakta olduğu yanıt bu pencereye sığmak zorundadır. Pencere dolduğunda en eski içerik düşer ya da özetlenir; model onu artık göremez.

Pencere büyüklüğü birkaç yılda çarpıcı biçimde arttı: ilk yaygın modeller birkaç sayfalık metni görebilirken, bugünün üst düzey modelleri bir milyon belirteci, yani birkaç kalın kitabı tek seferde alabiliyor.

:::chart
{"type":"bar","log":true,"title":"Bağlam penceresinin büyümesi: seçilmiş modeller","labels":["GPT-3 (2020)","GPT-3.5 Turbo (2022)","GPT-4 (2023)","Claude 2 (2023)","GPT-4 Turbo (2023)","Claude 3 (2024)","Gemini 1.5 Pro (2024)"],"datasets":[{"label":"Bağlam penceresi (bin belirteç)","data":[2,4,8,100,128,200,1000]}],"unit":"bin belirteç","yTitle":"Bin belirteç (logaritmik)","source":"Üreticilerin model belgeleri (OpenAI, Anthropic, Google)","sourceUrl":"https://platform.openai.com/docs/models","date":"2020–2024","note":"Değerler modellerin çıkış dönemindeki standart sürümleri içindir ve yuvarlanmıştır (yaklaşık)."}
:::

:::svg Bağlam penceresi: model yalnızca pencerenin içindeki belirteçleri görür. Konuşma uzadıkça en eski bölümler pencerenin dışında kalır.
<svg viewBox="0 0 720 200" role="img" aria-label="Uzun bir belirteç dizisi üzerinde sağa doğru kayan bir pencere. Pencerenin içindeki belirteçler görünür, solda kalanlar soluk ve erişilemez durumdadır" data-dur="10">
<g class="s-line"><rect x="20" y="70" width="40" height="40" rx="5"/><rect x="66" y="70" width="40" height="40" rx="5"/><rect x="112" y="70" width="40" height="40" rx="5"/><rect x="158" y="70" width="40" height="40" rx="5"/><rect x="204" y="70" width="40" height="40" rx="5"/><rect x="250" y="70" width="40" height="40" rx="5"/><rect x="296" y="70" width="40" height="40" rx="5"/><rect x="342" y="70" width="40" height="40" rx="5"/><rect x="388" y="70" width="40" height="40" rx="5"/><rect x="434" y="70" width="40" height="40" rx="5"/><rect x="480" y="70" width="40" height="40" rx="5"/><rect x="526" y="70" width="40" height="40" rx="5"/><rect x="572" y="70" width="40" height="40" rx="5"/><rect x="618" y="70" width="40" height="40" rx="5"/><rect x="664" y="70" width="40" height="40" rx="5"/></g>
<g class="s-acc"><rect x="434" y="70" width="40" height="40" rx="5" data-a="show .25"/><rect x="480" y="70" width="40" height="40" rx="5" data-a="show .4"/><rect x="526" y="70" width="40" height="40" rx="5" data-a="show .55"/><rect x="572" y="70" width="40" height="40" rx="5" data-a="show .7"/><rect x="618" y="70" width="40" height="40" rx="5" data-a="show .82"/></g>
<rect x="14" y="60" width="330" height="60" rx="10" class="s-none l-acc w4" data-a="move 322 0 .2 .85 show"/>
<text x="20" y="40" class="t-b">Konuşma (belirteçler)</text><text x="704" y="40" text-anchor="end" class="t-s">yeni belirteçler →</text>
<text x="20" y="150" class="t-s" data-a="show .5">← pencerenin dışında kalanlar: model artık göremez</text>
<text x="704" y="176" text-anchor="end" class="t-s t-acc t-b">çerçeve = bağlam penceresi</text>
</svg>
:::

## Dil modelleri (LLM)

**Büyük dil modeli** (LLM, Large Language Model), metin üreten model türüdür. GPT (OpenAI), Claude (Anthropic), Gemini (Google), Llama (Meta), Mistral, DeepSeek, Qwen (Alibaba) ve Grok (xAI) ailelerinin hepsi dil modelidir.

### Nasıl çalışır?

Bir dil modelinin yaptığı iş şaşırtıcı ölçüde yalındır: **bir sonraki belirteci tahmin eder**. Kendisine verilen metne bakar ve sözlüğündeki her belirteç için “sıradaki bu olur” olasılığını hesaplar. Bu olasılıklardan biri seçilir, metnin sonuna eklenir ve işlem baştan yinelenir. Paragraflar, kodlar, şiirler bu döngünün binlerce kez dönmesiyle ortaya çıkar.

Seçimin ne kadar “cesur” yapılacağını **sıcaklık** (temperature) ayarı belirler: düşük sıcaklıkta model hep en olası belirteci seçer ve tutarlı, öngörülebilir yanıtlar verir; yüksek sıcaklıkta daha az olası seçeneklere de şans tanır ve daha yaratıcı ama daha dağınık olur.

:::svg Dil modeli her adımda bir sonraki belirteç için olasılık hesaplar, birini seçer ve metne ekler. Döngü, yanıt tamamlanana kadar sürer.
<svg viewBox="0 0 720 280" role="img" aria-label="Türkiye'nin başkenti sözcüklerinden oluşan girdi modele girer; model sıradaki belirteç için olasılıklar üretir: Ankara yüzde 92, İstanbul yüzde 5, bir yüzde 2. En olası belirteç seçilip metne eklenir" data-dur="10">
<rect x="20" y="24" width="96" height="40" rx="8" class="s-bg3 l-fg w2"/><text x="68" y="49" text-anchor="middle" class="t-m">Türkiye</text>
<rect x="122" y="24" width="56" height="40" rx="8" class="s-bg3 l-fg w2"/><text x="150" y="49" text-anchor="middle" class="t-m">'nin</text>
<rect x="184" y="24" width="110" height="40" rx="8" class="s-bg3 l-fg w2"/><text x="239" y="49" text-anchor="middle" class="t-m">başkenti</text>
<g data-a="show .78"><rect x="300" y="24" width="96" height="40" rx="8" class="s-acc"/><text x="348" y="49" text-anchor="middle" class="t-m t-ink">Ankara</text></g>
<path d="M157 64v40" class="l-mut w2" fill="none"/><circle cx="157" cy="70" r="7" class="s-blue" data-a="move 0 30 .02 .14"/>
<rect x="40" y="106" width="234" height="80" rx="14" class="s-soft l-acc w2"/><text x="157" y="140" text-anchor="middle" class="t-b">Dil modeli</text><text x="157" y="162" text-anchor="middle" class="t-s">milyarlarca parametre</text>
<path d="M274 146h76" class="l-mut w2" fill="none"/><circle cx="280" cy="146" r="7" class="s-blue" data-a="move 64 0 .18 .3"/>
<text x="690" y="110" text-anchor="end" class="t-s t-b">sıradaki belirteç olasılıkları</text>
<text x="360" y="140" class="t-m">Ankara</text><rect x="440" y="126" width="240" height="18" rx="4" class="s-acc" data-o="l" data-a="grow x .32 .5"/><text x="690" y="140" class="t-s">%92</text>
<text x="360" y="170" class="t-m">İstanbul</text><rect x="440" y="156" width="14" height="18" rx="4" class="s-mut" data-o="l" data-a="grow x .36 .5"/><text x="462" y="170" class="t-s">%5</text>
<text x="360" y="200" class="t-m">bir</text><rect x="440" y="186" width="6" height="18" rx="3" class="s-mut" data-o="l" data-a="grow x .4 .5"/><text x="454" y="200" class="t-s">%2</text>
<g data-a="show .58"><path d="M400 120c-40-20-50-30-52-50m-6 12 6-12 8 10" class="l-acc w3 rc" fill="none"/><text x="430" y="84" class="t-s t-acc t-b">seç ve metne ekle</text></g>
<text x="20" y="240" class="t-s" data-a="show .82">Yeni metin yeniden modele verilir ve bir sonraki belirteç için aynı döngü yinelenir.</text>
</svg>
:::

### Transformer ve dikkat

Bugünkü dil modellerinin tamamına yakını, 2017'de Google araştırmacılarının “Attention Is All You Need” makalesinde tanıttığı **Transformer** mimarisine dayanır. Transformer'ın temel buluşu **dikkat** (attention) mekanizmasıdır: model her belirteci işlerken metindeki diğer bütün belirteçlere bakar ve hangilerinin o an önemli olduğunu tartar. “Kedi halının üzerine uzandı çünkü **o** yumuşaktı” cümlesinde “o” sözcüğünün halıyı mı kediyi mi gösterdiğini çözebilmesi bu sayededir.

### Akıl yürüten modeller

Yeni kuşak modellerin çoğu, yanıt vermeden önce bir **düşünme** aşamasından geçebilir: model önce görünmeyen (ya da özetlenerek gösterilen) bir taslak alanda sorunu adım adım çözer, sonra yanıtı yazar. Bu, matematik ve çok adımlı problemlerde doğruluğu belirgin biçimde artırır; karşılığında yanıt süresi ve maliyet yükselir. Pek çok modelde bu düşünme miktarı “düşük”, “yüksek” gibi kademelerle ayarlanabilir.

### Halüsinasyon

Dil modelleri doğruyu bildiği için değil, olası göründüğü için metin üretir. Bu yüzden zaman zaman kendinden emin bir dille yanlış bilgi, var olmayan bir kaynak ya da uydurma bir alıntı üretebilirler. Buna **halüsinasyon** denir. Modeli web aramasına ya da belgelerinize bağlamak (aşağıdaki RAG) bu riski azaltır, ama sıfırlamaz. Önemli bilgileri kaynağından doğrulamak gerekir.

### Araç kullanımı ve ajanlar

Bir dil modeli kendi başına yalnızca metin üretir. **Araç kullanımı** (function calling) ile model, “şu aramayı yap”, “şu kodu çalıştır”, “şu dosyayı oku” gibi yapılandırılmış istekler üretir; çevresindeki yazılım bu isteği yerine getirip sonucu modele geri verir. Bu döngüyü bir hedefe ulaşana kadar kendi kendine sürdüren sistemlere **ajan** (agent) denir. Kod yazıp test eden, hata alınca düzelten kodlama ajanları bunun en yaygın örneğidir.

### Sistem yönergesi ve istem

Bir dil modeline verilen girdiye **istem** (prompt) denir. Uygulamaların çoğu, kullanıcının yazdığı metnin önüne gizli bir **sistem yönergesi** ekler: modelin nasıl davranacağını, hangi üslupla konuşacağını ve neleri yapmaması gerektiğini anlatan talimatlar. Aynı model, farklı sistem yönergeleriyle bir müşteri temsilcisi, bir kod yardımcısı ya da bir çeviri aracı gibi davranabilir. Bu yüzden bir sohbet uygulamasında gördüğünüz davranış yalnızca modelin değil, onu saran yönergenin ve araçların da ürünüdür.

İstemin nasıl yazıldığı sonucu ciddi biçimde etkiler. Açık bir görev tanımı, yeterli bağlam, istenen çıktının biçimi ve bir iki örnek vermek, çoğu zaman daha güçlü bir modele geçmekten daha büyük bir iyileşme sağlar.

### Küçük ve büyük modeller

Her sağlayıcı genellikle aynı kuşaktan birkaç boyutta model sunar: en yetenekli ama yavaş ve pahalı bir amiral gemisi, dengeli bir orta model ve çok hızlı, çok ucuz küçük bir model. Küçük modeller sınıflandırma, özetleme, veri çıkarma gibi yüksek hacimli ve görece basit işlerde çoğu zaman yeterlidir ve yanıtı çok daha çabuk verir. Büyük modeller ise çok adımlı akıl yürütme, karmaşık kod ve uzun görevlerde fark yaratır. İyi tasarlanmış sistemler çoğunlukla ikisini birlikte kullanır: kolay işler küçük modele, zor işler büyük modele yönlendirilir.

## Çok kipli modeller

**Çok kipli** (multimodal) modeller birden fazla veri türünü anlayabilir: metnin yanında görsel, ses, video ya da PDF. Güncel üst düzey dil modellerinin çoğu çok kiplidir; bir fotoğrafı açıklayabilir, bir grafikten veri okuyabilir, el yazısını çözebilir.

Bunun arkasındaki fikir şudur: görsel küçük karelere bölünür ve her kare, tıpkı bir sözcük gibi, bir sayı dizisine çevrilir. Model bu “görsel belirteçleri” metin belirteçleriyle aynı dikkat mekanizmasından geçirir. Girdi türlerini anlamak ile çıktı türlerini üretmek ayrı yeteneklerdir: bir model görseli anlayıp yalnızca metin üretebilir.

## Görsel üreten modeller

Metinden görsel üreten modeller (Midjourney, GPT Image, Imagen, Flux, Stable Diffusion gibi) çoğunlukla **difüzyon** yöntemiyle çalışır.

### Difüzyon nasıl çalışır?

Eğitim sırasında gerçek görsellere adım adım gürültü eklenir, ta ki geriye yalnızca karıncalanma kalana kadar. Model, bu süreci **tersine çevirmeyi** öğrenir: gürültülü bir görselden gürültünün bir kısmını tahmin edip çıkarmayı. Üretim sırasında tam bir gürültüyle başlanır ve model, yazdığınız metnin yönlendirmesiyle onlarca adımda gürültüyü temizleyerek bir görsel ortaya çıkarır.

Hesabı hafifletmek için bu işlem çoğu zaman piksellerin üzerinde değil, görselin sıkıştırılmış bir temsili olan **gizil uzayda** (latent space) yapılır; “latent diffusion” adı buradan gelir. Yeni modellerin bir bölümü ise difüzyon yerine ya da onunla birlikte, görseli dil modellerine benzer biçimde parça parça üreten yöntemler kullanır; bu yaklaşım özellikle görsel içindeki yazıların doğru çıkmasında başarılıdır.

:::svg Difüzyon: üretim saf gürültüyle başlar; model her adımda gürültünün bir kısmını temizler ve metindeki tarife uyan görsel ortaya çıkar.
<svg viewBox="0 0 720 240" role="img" aria-label="Dört kare: ilki tamamen gürültü, sonrakilerde gürültü azalır ve son karede dağ ile güneşten oluşan net bir görsel belirir. Üstte istem metni: gün batımında dağ" data-dur="10">
<rect x="20" y="14" width="250" height="36" rx="8" class="s-bg3 l-fg w2"/><text x="145" y="38" text-anchor="middle" class="t-m">istem: "gün batımında dağ"</text>
<g class="s-bg3 l-fg w2"><rect x="20" y="70" width="150" height="130" rx="8"/><rect x="200" y="70" width="150" height="130" rx="8"/><rect x="380" y="70" width="150" height="130" rx="8"/><rect x="560" y="70" width="150" height="130" rx="8"/></g>
<g class="s-mut"><circle cx="40" cy="90" r="4"/><circle cx="70" cy="120" r="5"/><circle cx="110" cy="96" r="4"/><circle cx="150" cy="130" r="5"/><circle cx="52" cy="160" r="5"/><circle cx="96" cy="180" r="4"/><circle cx="132" cy="170" r="5"/><circle cx="86" cy="146" r="4"/><circle cx="140" cy="86" r="4"/><circle cx="34" cy="130" r="4"/><circle cx="120" cy="140" r="4"/><circle cx="60" cy="104" r="4"/></g>
<g data-a="show .2"><path d="M210 190l50-50 34 30 26-22 22 42z" class="s-line"/><circle cx="310" cy="104" r="16" class="s-line"/><g class="s-mut"><circle cx="230" cy="100" r="4"/><circle cx="280" cy="124" r="4"/><circle cx="330" cy="150" r="4"/><circle cx="250" cy="160" r="4"/><circle cx="300" cy="180" r="4"/></g></g>
<g data-a="show .45"><path d="M390 190l50-56 34 32 26-24 22 48z" class="s-mut"/><circle cx="490" cy="104" r="17" class="s-acc2"/><g class="s-mut"><circle cx="410" cy="100" r="3"/><circle cx="510" cy="150" r="3"/></g></g>
<g data-a="show .7"><rect x="562" y="72" width="146" height="126" rx="6" class="s-soft"/><circle cx="670" cy="106" r="18" class="s-acc2"/><path d="M566 196l54-64 36 36 26-26 24 54z" class="s-acc"/><path d="M620 132l14 14M682 142l8 12" class="l-fg w1" fill="none"/></g>
<path d="M170 135h30m-8-5 8 5-8 5M350 135h30m-8-5 8 5-8 5M530 135h30m-8-5 8 5-8 5" class="l-mut w2 rc" fill="none"/>
<text x="95" y="222" text-anchor="middle" class="t-s">saf gürültü</text><text x="275" y="222" text-anchor="middle" class="t-s">adım 10</text><text x="455" y="222" text-anchor="middle" class="t-s">adım 30</text><text x="635" y="222" text-anchor="middle" class="t-s t-b">sonuç</text>
</svg>
:::

## Video üreten modeller

Video modelleri (Veo, Sora, Kling, Runway ve benzerleri), görsel üretimindeki fikri zamana yayar: tek bir kare yerine birbirini izleyen karelerin tamamı birlikte üretilir. Asıl zorluk **zamansal tutarlılıktır**; bir karakterin yüzü, giysisi ve arka plan kareden kareye aynı kalmalı, nesneler fizik kurallarına uygun hareket etmelidir.

Bu yüzden video üretimi çok pahalıdır: birkaç saniyelik bir klip, yüzlerce görsele eşdeğer hesaplama ister. Güncel modeller birkaç saniyeden bir dakikaya uzanan klipler üretir; yeni kuşak modellerin bir bölümü görüntüyle eşzamanlı ses ve konuşma da üretebilir.

## Ses modelleri

“Ses modeli” başlığı altında dört ayrı iş vardır:

| Tür | Ne yapar | Örnek |
|---|---|---|
| Konuşma tanıma (STT / ASR) | Sesi yazıya döker | Whisper |
| Metinden konuşma (TTS) | Yazıyı doğal sese çevirir | ElevenLabs |
| Ses klonlama | Kısa bir örnekten bir sesi taklit eder | TTS modellerinin bir özelliği |
| Müzik üretimi | Tariften şarkı, beste ya da efekt üretir | Suno |

Ayrıca **sesten sese** çalışan gerçek zamanlı modeller vardır: konuşmayı önce yazıya çevirmeden doğrudan işler ve sesli yanıt verir. Aradaki dönüşüm adımları kalktığı için gecikme çok düşüktür ve ses tonu, vurgu gibi ayrıntılar korunur; sesli asistanların doğal hissettirmesi bu sayededir.

Ses klonlama, dolandırıcılık ve izinsiz taklit için kötüye kullanılabildiğinden pek çok servis sesin sahibinden açık onay ister.

## Embedding modelleri

**Embedding** (gömme) modeli metin üretmez; bir metni, anlamını temsil eden uzun bir sayı dizisine, yani bir **vektöre** çevirir. Bu vektörlerin güzelliği şudur: anlamca yakın metinlerin vektörleri de uzayda birbirine yakın düşer. “Köpek maması” ile “yavru köpek beslenmesi” hiçbir ortak sözcük içermese bile komşudur; “kira sözleşmesi” ise çok uzaktadır.

Bu özellik üç yaygın kullanımın temelidir:

- **Anlamsal arama:** Sorgunun vektörüne en yakın belgeler bulunur; sözcük eşleşmesi gerekmez.
- **RAG (erişimle güçlendirilmiş üretim):** Kullanıcının sorusuyla ilgili belge parçaları embedding ile bulunur ve dil modeline bağlam olarak verilir. Böylece model, eğitim verisinde olmayan şirket içi belgeler hakkında kaynak göstererek yanıt verebilir.
- **Kümeleme ve öneri:** Benzer içerikler gruplanır, benzer ürünler önerilir.

:::svg Embedding uzayı: anlamca yakın metinler birbirine yakın noktalara yerleşir. Bir sorgu geldiğinde en yakın komşuları getirilir.
<svg viewBox="0 0 720 300" role="img" aria-label="İki boyutlu bir uzayda üç küme: hayvanlarla ilgili metinler, taşıtlarla ilgili metinler ve hukukla ilgili metinler. Yavru köpek beslenmesi sorgusu hayvan kümesinin içine düşer ve en yakın iki komşusuna bağlanır" data-dur="9">
<rect x="20" y="16" width="680" height="268" rx="12" class="s-bg2 l-line w2"/>
<g><circle cx="150" cy="96" r="9" class="s-acc"/><text x="166" y="100" class="t-s">köpek maması</text><circle cx="60" cy="150" r="9" class="s-acc"/><text x="76" y="154" class="t-s">kedi kumu</text><circle cx="200" cy="170" r="9" class="s-acc"/><text x="216" y="174" class="t-s">veteriner randevusu</text></g>
<g><circle cx="530" cy="80" r="9" class="s-blue"/><text x="546" y="84" class="t-s">lastik değişimi</text><circle cx="580" cy="130" r="9" class="s-blue"/><text x="596" y="134" class="t-s">motor yağı</text><circle cx="500" cy="140" r="9" class="s-blue"/><text x="440" y="164" class="t-s">araç muayenesi</text></g>
<g><circle cx="420" cy="230" r="9" class="s-pur"/><text x="436" y="234" class="t-s">kira sözleşmesi</text><circle cx="360" cy="258" r="9" class="s-pur"/><text x="376" y="262" class="t-s">vekâletname</text></g>
<g data-a="show .15"><circle cx="190" cy="132" r="11" class="s-acc2"/><circle cx="190" cy="132" r="26" class="s-none l-acc2 w2" data-a="wave 2.2"/><text x="60" y="220" class="t-s t-acc2 t-b">sorgu: "yavru köpek beslenmesi"</text><path d="M150 208l34-64" class="l-acc2 w1 dash" fill="none"/></g>
<g data-a="show .5"><path d="M190 132 150 96M190 132l10 38" class="l-acc2 w3 rc" fill="none"/><text x="250" y="60" class="t-s t-b">en yakın komşular getirilir</text></g>
</svg>
:::

## Açık ve kapalı modeller

Modeller, ağırlıklarının paylaşılıp paylaşılmadığına göre ikiye ayrılır.

**Kapalı modeller** (GPT, Claude, Gemini aileleri gibi) yalnızca üreticinin uygulaması ya da API'si üzerinden kullanılır. Ağırlıklar indirilemez; model üreticinin sunucularında çalışır.

**Açık ağırlıklı modellerin** (Llama, Mistral'ın bazı modelleri, DeepSeek, Qwen aileleri gibi) ağırlık dosyaları yayımlanır. İndirip kendi bilgisayarınızda ya da sunucunuzda çalıştırabilir, kendi verinizle ince ayar yapabilirsiniz.

| | Kapalı modeller | Açık ağırlıklı modeller |
|---|---|---|
| Erişim | Uygulama ya da API | İndirilebilir ağırlıklar (ve çoğu zaman API) |
| Veri gizliliği | Veri sağlayıcının sunucusuna gider | Kendi donanımınızda kalabilir |
| Maliyet | Kullanım başına ücret | Donanım ve elektrik; yüksek hacimde ucuzlayabilir |
| Özelleştirme | Sınırlı (yönerge, bazen ince ayar) | Tam (ince ayar, nicemleme, değişiklik) |
| Kurulum | Gerekmez | Teknik bilgi ve güçlü donanım gerekir |
| Süreklilik | Üretici modeli değiştirebilir ya da kaldırabilir | İndirdiğiniz sürüm sizde kalır |

:::warn “Açık ağırlık” ile “açık kaynak” aynı şey değildir
Ağırlıkların indirilebilmesi, modelin açık kaynak tanımını karşıladığı anlamına gelmez. Pek çok model ticari kullanımı, belirli gelir eşiklerini ya da kullanım alanlarını sınırlayan özel lisanslarla yayımlanır; eğitim verisi ve eğitim kodu ise çoğu zaman paylaşılmaz. Bir modeli ticari bir üründe kullanmadan önce lisansını okuyun.
:::

## Model türlerinin karşılaştırması

| Tür | Girdi | Çıktı | Temel yöntem | Tipik kullanım |
|---|---|---|---|---|
| Dil modeli (LLM) | Metin | Metin | Transformer; sonraki belirteç tahmini | Sohbet, yazı, kod, özet, çeviri |
| Çok kipli model | Metin, görsel, ses, video | Metin (bazıları görsel ve ses) | Transformer; ortak belirteç uzayı | Görsel ve belge anlama |
| Görsel modeli | Metin (ve görsel) | Görsel | Difüzyon ya da özbağlanımlı üretim | İllüstrasyon, tasarım, düzenleme |
| Video modeli | Metin, görsel | Video (bazıları sesli) | Zamana yayılmış difüzyon | Kısa klip, reklam, ön görselleştirme |
| Konuşma tanıma | Ses | Metin | Kodlayıcı–çözücü Transformer | Altyazı, toplantı dökümü |
| Metinden konuşma | Metin | Ses | Sinirsel ses üretimi | Seslendirme, sesli asistan |
| Müzik modeli | Metin | Ses | Difüzyon ya da belirteç tabanlı üretim | Şarkı, fon müziği |
| Embedding modeli | Metin (ya da görsel) | Vektör | Kodlayıcı Transformer | Arama, RAG, kümeleme |

## Hangi tür model ne zaman?

| Yapmak istediğiniz | Gereken model türü |
|---|---|
| Soru sormak, metin yazdırmak, kod yazdırmak | Dil modeli |
| Fotoğraf, ekran görüntüsü ya da PDF hakkında soru sormak | Çok kipli dil modeli |
| Zor bir matematik ya da mantık problemi çözmek | Düşünme özelliği açık bir dil modeli |
| Kendi belgeleriniz üzerinde soru-yanıt | Embedding modeli + dil modeli (RAG) |
| Logo, illüstrasyon, ürün görseli üretmek | Görsel modeli |
| Kısa tanıtım videosu üretmek | Video modeli |
| Toplantı kaydını yazıya dökmek | Konuşma tanıma modeli |
| Metni seslendirmek | Metinden konuşma modeli |
| Verinin dışarı çıkmaması gereken işler | Kendi donanımınızda çalışan açık ağırlıklı model |

Belirli modellerin bu işlerdeki güncel başarımını, fiyatlarını ve kıyaslama sonuçlarını dizinin üçüncü yazısında, [Best AI for Every Task](/post/best-ai-for-every-task) başlığında bulabilirsiniz. Oradaki skorların ne anlama geldiğini ise [Every AI Benchmark Explained](/post/every-ai-benchmark-explained) açıklar.

## Sık sorulan sorular

:::faq
Q: Yapay zekâ, makine öğrenmesi ve derin öğrenme arasındaki fark nedir?
A: İç içe kavramlardır. Yapay zekâ en geniş alandır. Makine öğrenmesi, kuralları veriden öğrenen yapay zekâ yöntemleridir. Derin öğrenme ise çok katmanlı sinir ağlarını kullanan makine öğrenmesi alt dalıdır; bu yazıdaki modellerin hepsi derin öğrenme ürünüdür.
Q: Dil modelleri söylediklerini gerçekten anlıyor mu?
A: Bu, araştırmacılar arasında hâlâ tartışılan bir sorudur. Kesin olan şu: modeller dildeki örüntüleri çok ayrıntılı biçimde öğrenir ve bu, pek çok görevde anlamaya benzer davranış üretir. Ama aynı modeller insanın yapmayacağı türden hatalar da yapabilir.
Q: Parametre sayısı fazla olan model her zaman daha mı iyi?
A: Hayır. Eğitim verisinin kalitesi, eğitim yöntemi ve mimari en az boyut kadar önemlidir. Yeni kuşak küçük modeller, birkaç yıl öncesinin çok daha büyük modellerini geçebilmektedir.
Q: Model benimle konuşurken benden öğreniyor mu?
A: Konuşma sırasında hayır; ağırlıklar sabittir. Ancak bazı sağlayıcılar, konuşmaları sonraki modellerin eğitiminde kullanabilir. Bu genellikle ayarlardan kapatılabilir; kurumsal ve API kullanımında çoğu sağlayıcı varsayılan olarak kullanmaz. Sağlayıcınızın veri politikasını okuyun.
Q: Bilgi kesim tarihi nedir?
A: Modelin eğitim verisinin bittiği tarihtir. Model, bu tarihten sonraki olayları kendi başına bilemez. Web aramasına bağlı sistemler bu sınırı güncel kaynakları okuyarak aşar.
Q: Bir yapay zekâ modelini kendi bilgisayarımda çalıştırabilir miyim?
A: Evet, açık ağırlıklı modellerle. Küçük modeller (birkaç milyar parametre) güncel bir dizüstünde çalışır. Büyük modeller için bol belleği olan ekran kartları ya da birleşik belleği yüksek bilgisayarlar gerekir.
Q: İnce ayar mı yapmalıyım, RAG mı kurmalıyım?
A: Modelin güncel ya da size özel bilgilere erişmesini istiyorsanız RAG genellikle daha ucuz ve günceli korumak daha kolaydır. Modelin üslubunu, biçimini ya da dar bir görevdeki davranışını değiştirmek istiyorsanız ince ayar daha uygundur. İkisi birlikte de kullanılabilir.
:::

## Özet

- Bir model, veriden öğrenilmiş parametrelerden oluşan büyük bir fonksiyondur; eğitimde öğrenir, çıkarımda yalnızca kullanılır.
- Dil modelleri metni belirteçlere böler ve her adımda bir sonraki belirteci tahmin eder; görebildikleri metin bağlam penceresiyle sınırlıdır.
- Görsel ve video modelleri çoğunlukla difüzyonla, yani gürültüyü adım adım temizleyerek üretim yapar.
- Ses tarafında konuşma tanıma, metinden konuşma, ses klonlama ve müzik üretimi ayrı işlerdir.
- Embedding modelleri metni anlam vektörlerine çevirir; anlamsal aramanın ve RAG'in temelidir.
- Kapalı modeller API ile kullanılır; açık ağırlıklı modeller indirilip kendi donanımınızda çalıştırılabilir, ama lisansları değişkendir.

## Kaynaklar

- [Vaswani ve ark. (2017) – “Attention Is All You Need”](https://arxiv.org/abs/1706.03762)
- [Brown ve ark. (2020) – “Language Models are Few-Shot Learners” (GPT-3)](https://arxiv.org/abs/2005.14165)
- [Devlin ve ark. (2018) – “BERT: Pre-training of Deep Bidirectional Transformers”](https://arxiv.org/abs/1810.04805)
- [Ho, Jain, Abbeel (2020) – “Denoising Diffusion Probabilistic Models”](https://arxiv.org/abs/2006.11239)
- [Rombach ve ark. (2022) – “High-Resolution Image Synthesis with Latent Diffusion Models”](https://arxiv.org/abs/2112.10752)
- [Radford ve ark. (2022) – “Robust Speech Recognition via Large-Scale Weak Supervision” (Whisper)](https://arxiv.org/abs/2212.04356)
- [Lewis ve ark. (2020) – “Retrieval-Augmented Generation for Knowledge-Intensive NLP Tasks”](https://arxiv.org/abs/2005.11401)
- [Meta – Llama 3.1 model kartı](https://github.com/meta-llama/llama-models/blob/main/models/llama3_1/MODEL_CARD.md)
- [DeepSeek-V3 Technical Report](https://arxiv.org/abs/2412.19437)
- [Mistral AI – “Mistral 7B” makalesi](https://arxiv.org/abs/2310.06825)
- [Open Source Initiative – The Open Source AI Definition](https://opensource.org/ai/open-source-ai-definition)
- [OpenAI – Model belgeleri](https://platform.openai.com/docs/models)
