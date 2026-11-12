<?php
/** @var string $content @var array $seo */
use App\Core\Assets;
use App\Core\Csp;
use App\Services\Seo;

$siteName = (string)setting('site_name', 'TechBlog');
$title = (string)($seo['title'] ?? $siteName);
$fullTitle = !empty($seo['raw_title']) ? $title : $title . ' — ' . $siteName;
$desc = str_limit((string)($seo['description'] ?? setting('site_description', '')), 160);
$canonical = (string)($seo['canonical'] ?? '');
$ogImage = (string)($seo['og_image'] ?? url('/assets/img/og-default.png'));
$menu = (array)setting('menu', [['label' => 'Yazılar', 'url' => '/archive'], ['label' => 'Hakkında', 'url' => '/about'], ['label' => 'İletişim', 'url' => '/contact']]);
$path = App\Core\Request::path();

$accent = ':root{--acc:' . e((string)setting('accent_light', '#0a7558')) . '}:root[data-theme=dark]{--acc:' . e((string)setting('accent_dark', '#3ddc97')) . '}';
$critical = Csp::inline('style-src', Assets::inline('critical.css') . $accent);
$boot = Csp::inline('script-src', '(function(d){var t;try{t=localStorage.getItem("theme")}catch(e){}if(t!=="light"&&t!=="dark"){t=matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"}d.documentElement.dataset.theme=t;var l=d.createElement("link");l.rel="stylesheet";l.href="' . asset('app.css') . '";d.head.appendChild(l)})(document);');

$ga = (string)setting('analytics_ga4', '');
$script = (string)setting('analytics_script', '');
if ($ga !== '') {
    Csp::add('script-src', 'https://www.googletagmanager.com');
    foreach (['https://www.google-analytics.com', 'https://region1.google-analytics.com', 'https://www.googletagmanager.com'] as $src) {
        Csp::add('connect-src', $src);
    }
    Csp::add('img-src', 'https://www.google-analytics.com');
}
if ($script !== '' && ($o = parse_url($script)) && isset($o['host'])) {
    Csp::add('script-src', 'https://' . $o['host']);
    Csp::add('connect-src', 'https://' . $o['host']);
}
?>
<!doctype html>
<html lang="tr" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta name="robots" content="<?= e($seo['robots'] ?? 'index, follow, max-image-preview:large') ?>">
<?php if ($canonical !== ''): ?><link rel="canonical" href="<?= e($canonical) ?>">
<?php endif; ?>
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:locale" content="tr_TR">
<meta property="og:type" content="<?= e($seo['og_type'] ?? 'website') ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:url" content="<?= e($canonical ?: url($path)) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<?php if (!empty($seo['published'])): ?><meta property="article:published_time" content="<?= e(date('c', (int)strtotime($seo['published']))) ?>">
<meta property="article:modified_time" content="<?= e(date('c', (int)strtotime($seo['modified'] ?? $seo['published']))) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($title) ?>">
<meta name="twitter:description" content="<?= e($desc) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<meta name="theme-color" content="#f6f7f9" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#0a0f15" media="(prefers-color-scheme: dark)">
<link rel="preload" href="/assets/fonts/inter-tr.v1.woff2" as="font" type="font/woff2" crossorigin>
<?php if (!empty($lcpImage)): ?><link rel="preload" href="<?= e($lcpImage) ?>" as="image" fetchpriority="high">
<?php endif; ?>
<style><?= $critical ?></style>
<script><?= $boot ?></script>
<noscript><link rel="stylesheet" href="<?= e(asset('app.css')) ?>"></noscript>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="alternate" type="application/rss+xml" title="<?= e($siteName) ?> RSS" href="<?= e(url('/feed.xml')) ?>">
<?php foreach (($seo['jsonld'] ?? []) as $ld): ?><script type="application/ld+json"><?= Seo::jsonLd($ld) ?></script>
<?php endforeach; ?>
<script type="module" src="<?= e(asset('app.js')) ?>"></script>
</head>
<body>
<a class="skip" href="#main">İçeriğe geç</a>
<header class="hdr">
  <div class="wrap hdr-in">
    <a class="logo" href="/" aria-label="<?= e($siteName) ?> ana sayfa">
      <img src="<?= e((string)setting('site_logo', '/assets/img/logo.svg')) ?>" alt="" width="32" height="32">
      <span><?= e($siteName) ?></span>
    </a>
    <nav class="nav" id="nav" aria-label="Ana menü">
      <?php foreach ($menu as $item): $u = (string)($item['url'] ?? '/'); ?>
      <a href="<?= e($u) ?>"<?= $u === $path ? ' aria-current="page"' : '' ?>><?= e((string)($item['label'] ?? '')) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="hdr-tools">
      <button class="ibtn" type="button" data-search-open aria-label="Ara (kısayol: /)"><?= icon('search') ?></button>
      <button class="ibtn" type="button" data-theme-toggle aria-label="Açık / koyu temayı değiştir"><span class="i-moon"><?= icon('moon') ?></span><span class="i-sun"><?= icon('sun') ?></span></button>
      <span data-auth-slot><a class="ibtn" href="/login" aria-label="Giriş yap"><?= icon('user') ?></a></span>
      <button class="ibtn menu-btn" type="button" data-menu-toggle aria-controls="nav" aria-expanded="false" aria-label="Menüyü aç / kapat"><?= icon('menu') ?></button>
    </div>
  </div>
</header>
<main id="main">
<?= $content ?>
</main>
<footer class="ftr">
  <div class="wrap">
    <div class="ftr-in">
      <div>
        <h2><?= e($siteName) ?></h2>
        <p><?= e((string)setting('footer_text', 'Teknolojinin temel kavramlarını kaynak göstererek, sade bir Türkçeyle anlatan bağımsız rehber sitesi.')) ?></p>
      </div>
      <nav aria-label="Site bağlantıları">
        <h2>Site</h2>
        <ul>
          <li><a href="/archive">Tüm yazılar</a></li>
          <li><a href="/about">Hakkında</a></li>
          <li><a href="/contact">İletişim</a></li>
          <li><a href="/privacy">Gizlilik ve çerezler</a></li>
        </ul>
      </nav>
      <nav aria-label="Takip">
        <h2>Takip et</h2>
        <ul>
          <li><a href="/feed.xml">RSS akışı</a></li>
          <?php foreach ((array)setting('social', []) as $k => $u): if (!is_string($u) || !preg_match('#^https?://#', $u)) { continue; } ?>
          <li><a href="<?= e($u) ?>" rel="me noopener noreferrer" target="_blank"><?= e(App\Controllers\Admin\SettingsController::SOCIAL[$k] ?? ucfirst((string)$k)) ?></a></li>
          <?php endforeach; ?>
          <li><button type="button" class="linkbtn" data-cookie-open>Çerez tercihleri</button></li>
        </ul>
      </nav>
    </div>
    <div class="ftr-bot"><span>© <?= date('Y') ?> <?= e($siteName) ?></span><span>Yazılardaki veriler, her yazının sonundaki kaynaklara dayanır.</span></div>
  </div>
</footer>

<dialog class="search-dlg" id="search-dlg" aria-label="Sitede ara">
  <form class="search-top" action="/search" method="get" role="search">
    <label class="sr-only" for="search-q">Aranacak sözcük</label>
    <input type="search" id="search-q" name="q" placeholder="Yazılarda ara…" autocomplete="off" minlength="2" maxlength="80" required>
    <button type="button" class="ibtn" data-search-close aria-label="Aramayı kapat"><?= icon('x') ?></button>
  </form>
  <ul class="search-res" id="search-res" aria-live="polite"><li><span class="hint">En az iki harf yazın. Enter ile tüm sonuçları açabilirsiniz.</span></li></ul>
</dialog>

<div class="cookie" id="cookie" role="region" aria-label="Çerez tercihleri" hidden>
  <p><strong>Çerez tercihleri.</strong> Oturum ve tema gibi zorunlu çerezler her zaman kullanılır. Ziyaret istatistikleri için analitik çerezleri yalnızca siz izin verirseniz çalışır. Ayrıntılar: <a href="/privacy">Gizlilik ve çerez politikası</a>.</p>
  <div class="cookie-actions">
    <button type="button" class="btn btn-ghost" data-consent="deny">Yalnızca zorunlu çerezler</button>
    <button type="button" class="btn btn-ghost" data-consent="allow">Analitik çerezlere izin ver</button>
  </div>
</div>
<div class="toasts" id="toasts" role="status" aria-live="polite"></div>
<script type="application/json" id="site-cfg"><?= Seo::jsonLd(['ga4' => $ga, 'script' => $script]) ?></script>
</body>
</html>
