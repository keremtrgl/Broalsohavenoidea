<?php
/** @var string $next @var array $firebase */
use App\Core\Csrf;
use App\Services\Seo;

$configured = $firebase['apiKey'] !== '' && $firebase['projectId'] !== '';
?>
<div class="wrap">
  <div class="auth-card" id="auth" data-next="<?= e($next) ?>" data-csrf="<?= e(Csrf::token()) ?>">
    <h1>Giriş yap</h1>
    <p class="hint">Yorum yazmak ve yönetim paneline erişmek için giriş yapın.</p>
    <?php if (!$configured): ?>
      <p class="form-err" role="alert">Firebase yapılandırması eksik. Site yöneticisi <code>.env</code> dosyasındaki FIREBASE_* değerlerini doldurmalıdır.</p>
    <?php else: ?>
    <p class="form-err" id="auth-err" role="alert" hidden></p>
    <p class="notice" id="auth-msg" role="status" hidden></p>
    <button type="button" class="btn" id="btn-google">Google ile devam et</button>
    <div class="or">veya e-posta ile</div>
    <div class="tabs" role="tablist" aria-label="E-posta ile giriş türü">
      <button type="button" role="tab" id="tab-in" aria-selected="true" aria-controls="auth-form">Giriş</button>
      <button type="button" role="tab" id="tab-up" aria-selected="false" aria-controls="auth-form">Kayıt ol</button>
    </div>
    <form class="form" id="auth-form" role="tabpanel" aria-labelledby="tab-in" novalidate>
      <div class="field" id="row-name" hidden><label for="a-name">Adınız</label><input type="text" id="a-name" maxlength="80" autocomplete="name"></div>
      <div class="field"><label for="a-email">E-posta</label><input type="email" id="a-email" autocomplete="email" required></div>
      <div class="field"><label for="a-pass">Parola</label><input type="password" id="a-pass" autocomplete="current-password" minlength="8" required><span class="hint" id="pass-hint" hidden>En az 8 karakter.</span></div>
      <button type="submit" class="btn" id="btn-email">Giriş yap</button>
      <button type="button" class="linkbtn" id="btn-reset">Parolamı unuttum</button>
    </form>
    <noscript><p class="form-err">Giriş için JavaScript gereklidir.</p></noscript>
    <script type="application/json" id="firebase-cfg"><?= Seo::jsonLd($firebase) ?></script>
    <script type="module" src="<?= e(asset('login.js')) ?>"></script>
    <?php endif; ?>
    <p class="hint">Giriş yaparak <a href="/privacy">gizlilik politikasını</a> kabul etmiş olursunuz.</p>
  </div>
</div>
