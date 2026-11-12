<?php /** @var array $s @var array $social */
$g = static fn(string $k, mixed $d = '') => $s[$k] ?? $d;
$menu = (array)$g('menu', []);
$menu[] = ['label' => '', 'url' => ''];
?>
<form method="post" action="/admin/settings" class="body" id="settings-form">
  <?= csrf_field() ?>
  <section class="panel">
    <h2>Genel</h2>
    <div class="fgrid">
      <div class="field"><label for="site_name">Site adı</label><input type="text" id="site_name" name="site_name" value="<?= e($g('site_name')) ?>" maxlength="80" required></div>
      <div class="field"><label for="site_tagline">Slogan</label><input type="text" id="site_tagline" name="site_tagline" value="<?= e($g('site_tagline')) ?>" maxlength="120"></div>
      <div class="field"><label for="contact_email">İletişim e-postası</label><input type="email" id="contact_email" name="contact_email" value="<?= e($g('contact_email')) ?>" maxlength="190"><small>İletişim formu mesajları bu adrese de gönderilir.</small></div>
      <div class="field"><label for="home_series">Ana sayfadaki seri</label><input type="text" id="home_series" name="home_series" value="<?= e($g('home_series', 'Every X Explained')) ?>" maxlength="100"></div>
    </div>
    <div class="field"><label for="site_description">Site açıklaması</label><textarea id="site_description" name="site_description" maxlength="300"><?= e($g('site_description')) ?></textarea></div>
    <div class="field"><label for="footer_text">Alt bilgi metni</label><textarea id="footer_text" name="footer_text" maxlength="300"><?= e($g('footer_text')) ?></textarea></div>
  </section>
  <section class="panel">
    <h2>Logo ve renkler</h2>
    <div class="fgrid">
      <div class="field"><label for="site_logo">Logo yolu</label><input class="mono" type="text" id="site_logo" name="site_logo" value="<?= e($g('site_logo', '/assets/img/logo.svg')) ?>" maxlength="300"><small>Medya kütüphanesindeki bir görselin yolu (/uploads/…). Kare görsel önerilir.</small></div>
      <div class="field"><label for="accent_light">Vurgu rengi (açık tema)</label><input type="color" id="accent_light" name="accent_light" value="<?= e($g('accent_light', '#0a7558')) ?>"><small>Beyaz zeminde en az 4,5:1 kontrast sağlayan koyu bir ton seçin.</small></div>
      <div class="field"><label for="accent_dark">Vurgu rengi (koyu tema)</label><input type="color" id="accent_dark" name="accent_dark" value="<?= e($g('accent_dark', '#3ddc97')) ?>"><small>Koyu zeminde okunabilen açık bir ton seçin.</small></div>
    </div>
  </section>
  <section class="panel">
    <h2>Menü</h2>
    <div id="menu-rows">
      <?php foreach ($menu as $i => $item): ?>
      <div class="menu-row">
        <div><label class="sr-only" for="ml-<?= $i ?>">Menü etiketi <?= $i + 1 ?></label><input type="text" id="ml-<?= $i ?>" name="menu_label[]" value="<?= e($item['label'] ?? '') ?>" maxlength="40" placeholder="Etiket"></div>
        <div><label class="sr-only" for="mu-<?= $i ?>">Menü bağlantısı <?= $i + 1 ?></label><input class="mono" type="text" id="mu-<?= $i ?>" name="menu_url[]" value="<?= e($item['url'] ?? '') ?>" maxlength="300" placeholder="/archive veya https://…"></div>
        <button type="button" class="btn btn-ghost btn-s" data-row-remove aria-label="Bu menü satırını kaldır">Kaldır</button>
      </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="btn btn-ghost btn-s" data-row-add="#menu-rows">Satır ekle</button>
    <p class="muted">Boş bırakılan satırlar kaydedilmez. Sıra, satırların sırasıdır.</p>
  </section>
  <section class="panel">
    <h2>Sosyal bağlantılar</h2>
    <div class="fgrid">
      <?php foreach ($social as $k => $label): ?>
      <div class="field"><label for="social_<?= $k ?>"><?= e($label) ?></label><input type="url" id="social_<?= $k ?>" name="social_<?= $k ?>" value="<?= e($g('social', [])[$k] ?? '') ?>" maxlength="300" placeholder="https://…"></div>
      <?php endforeach; ?>
    </div>
  </section>
  <section class="panel">
    <h2>Analitik ve çerez izni</h2>
    <p class="muted">Analitik betikleri yalnızca ziyaretçi çerez bandında izin verdikten sonra yüklenir. Güvenlik politikası (CSP) gereği serbest biçimli kod yapıştırılamaz; bunun yerine ölçüm kimliği veya betik adresi girilir ve gerekli alan adları CSP’ye kendiliğinden eklenir.</p>
    <div class="fgrid">
      <div class="field"><label for="analytics_ga4">Google Analytics 4 ölçüm kimliği</label><input class="mono" type="text" id="analytics_ga4" name="analytics_ga4" value="<?= e($g('analytics_ga4')) ?>" maxlength="20" placeholder="G-XXXXXXXXXX"></div>
      <div class="field"><label for="analytics_script">Başka bir analitik betiği (URL)</label><input class="mono" type="url" id="analytics_script" name="analytics_script" value="<?= e($g('analytics_script')) ?>" maxlength="300" placeholder="https://plausible.io/js/script.js"></div>
    </div>
  </section>
  <section class="panel">
    <h2>Yorumlar ve bakım</h2>
    <label class="check"><input type="checkbox" name="comments_moderation" value="1"<?= $g('comments_moderation', true) ? ' checked' : '' ?>> Yorumlar yayımlanmadan önce onay beklesin</label>
    <label class="check"><input type="checkbox" name="maintenance_mode" value="1"<?= $g('maintenance_mode', false) ? ' checked' : '' ?>> Bakım modu (ziyaretçiler bakım sayfasını görür; editör ve yöneticiler siteyi kullanmaya devam eder)</label>
  </section>
  <div class="toolbar"><button class="btn" type="submit">Ayarları kaydet</button></div>
</form>
