<?php
/** @var string $content @var string $pageTitle @var string $active */
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Csp;
use App\Core\Database as DB;
use App\Core\Session;
use App\Services\Seo;

$u = Auth::user();
$isAdmin = Auth::is('admin');
$pending = (int)DB::val("SELECT COUNT(*) FROM comments WHERE status = 'pending'");
$unread = $isAdmin ? (int)DB::val('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0') : 0;
$boot = Csp::inline('script-src', '(function(d){var t;try{t=localStorage.getItem("theme")}catch(e){}if(t!=="light"&&t!=="dark"){t=matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"}d.documentElement.dataset.theme=t})(document);');
$nav = [
    ['dashboard', '/admin', 'Gösterge paneli', 'layers', false],
    ['posts', '/admin/posts', 'Yazılar', 'book', false],
    ['media', '/admin/media', 'Medya', 'device', false],
    ['categories', '/admin/categories', 'Kategoriler', 'globe', false],
    ['tags', '/admin/tags', 'Etiketler', 'code', false],
    ['comments', '/admin/comments', 'Yorumlar', 'user', false],
    ['ai', '/admin/ai', 'Yapay zekâ verileri', 'ai', false],
    ['messages', '/admin/messages', 'Mesajlar', 'rss', true],
    ['users', '/admin/users', 'Kullanıcılar', 'user', true],
    ['settings', '/admin/settings', 'Site ayarları', 'chip', true],
    ['system', '/admin/system', 'Sistem', 'network', true],
    ['audit', '/admin/audit', 'Denetim günlüğü', 'clock', true],
];
?>
<!doctype html>
<html lang="tr" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="csrf" content="<?= e(Csrf::token()) ?>">
<title><?= e($pageTitle) ?> — Yönetim paneli</title>
<script><?= $boot ?></script>
<link rel="stylesheet" href="<?= e(asset('admin.css')) ?>">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<script type="module" src="<?= e(asset('admin.js')) ?>"></script>
</head>
<body>
<a class="skip" href="#main">İçeriğe geç</a>
<div class="adm">
  <nav class="side" id="side" aria-label="Yönetim menüsü">
    <a class="logo" href="/admin"><img src="/assets/img/logo.svg" alt="" width="32" height="32"><span>Yönetim</span></a>
    <?php foreach ($nav as [$key, $href, $label, $ic, $adminOnly]):
        if ($adminOnly && !$isAdmin) { continue; }
        if ($key === 'messages'): ?><span class="sep">Yönetici</span><?php endif; ?>
    <a class="nl" href="<?= $href ?>"<?= $active === $key ? ' aria-current="page"' : '' ?>><?= icon($ic) ?><?= e($label) ?>
      <?php if ($key === 'comments' && $pending > 0): ?><span class="badge" aria-label="<?= $pending ?> onay bekleyen yorum"><?= $pending ?></span><?php endif; ?>
      <?php if ($key === 'messages' && $unread > 0): ?><span class="badge" aria-label="<?= $unread ?> okunmamış mesaj"><?= $unread ?></span><?php endif; ?>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="main">
    <header class="top">
      <button class="ibtn side-btn" type="button" data-side-toggle aria-controls="side" aria-expanded="false" aria-label="Menüyü aç / kapat"><?= icon('menu') ?></button>
      <h1><?= e($pageTitle) ?></h1>
      <span class="who"><?= e($u['name'] ?? '') ?> · <?= e($u['role'] ?? '') ?></span>
      <button class="ibtn" type="button" data-keys-open aria-label="Klavye kısayolları (?)"><kbd>?</kbd></button>
      <button class="ibtn" type="button" data-theme-toggle aria-label="Açık / koyu temayı değiştir"><span class="i-moon"><?= icon('moon') ?></span><span class="i-sun"><?= icon('sun') ?></span></button>
      <a class="btn btn-ghost btn-s" href="/" target="_blank" rel="noopener">Siteyi gör</a>
      <form method="post" action="/logout"><?= csrf_field() ?><button class="btn btn-ghost btn-s" type="submit">Çıkış</button></form>
    </header>
    <main class="body" id="main">
<?= $content ?>
    </main>
  </div>
</div>

<dialog class="dlg" id="confirm-dlg" aria-labelledby="confirm-title">
  <h2 id="confirm-title">Emin misiniz?</h2>
  <p id="confirm-text"></p>
  <div class="dlg-actions"><button type="button" class="btn btn-ghost" data-confirm-no>Vazgeç</button><button type="button" class="btn btn-danger" data-confirm-yes>Evet, devam et</button></div>
</dialog>
<dialog class="dlg" id="keys-dlg" aria-labelledby="keys-title">
  <h2 id="keys-title">Klavye kısayolları</h2>
  <div class="keys">
    <span><kbd>g</kbd> <kbd>d</kbd></span><span>Gösterge paneli</span>
    <span><kbd>g</kbd> <kbd>p</kbd></span><span>Yazılar</span>
    <span><kbd>g</kbd> <kbd>m</kbd></span><span>Medya kütüphanesi</span>
    <span><kbd>g</kbd> <kbd>c</kbd></span><span>Yorumlar</span>
    <span><kbd>n</kbd></span><span>Yeni yazı</span>
    <span><kbd>/</kbd></span><span>Sayfadaki arama kutusuna git</span>
    <span><kbd>Ctrl</kbd> + <kbd>S</kbd></span><span>Yazıyı kaydet (düzenleyicide)</span>
    <span><kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>P</kbd></span><span>Önizleme (düzenleyicide)</span>
    <span><kbd>?</kbd></span><span>Bu pencereyi aç</span>
    <span><kbd>Esc</kbd></span><span>Açık pencereyi kapat</span>
  </div>
  <div class="dlg-actions"><button type="button" class="btn" data-close>Kapat</button></div>
</dialog>
<div class="toasts" id="toasts" role="status" aria-live="polite"></div>
<script type="application/json" id="flash"><?= Seo::jsonLd(Session::pullFlash()) ?></script>
</body>
</html>
