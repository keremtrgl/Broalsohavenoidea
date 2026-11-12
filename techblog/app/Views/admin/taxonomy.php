<?php /** @var string $kind @var string $base @var array $items @var array $icons */
$isCat = $kind === 'category';
?>
<div class="cols">
  <section class="panel">
    <h2><?= $isCat ? 'Kategoriler' : 'Etiketler' ?> <span class="muted">— sıralamak için sürükleyin veya ↑ ↓ düğmelerini kullanın</span></h2>
    <ul class="sortable" data-sortable="<?= e($base) ?>/reorder">
      <?php foreach ($items as $it): ?>
      <li data-id="<?= (int)$it['id'] ?>" draggable="true">
        <button type="button" class="handle" aria-label="<?= e($it['name']) ?>: yukarı taşı" data-move="-1">↑</button>
        <button type="button" class="handle" aria-label="<?= e($it['name']) ?>: aşağı taşı" data-move="1">↓</button>
        <span class="grow"><strong><?= e($it['name']) ?></strong> <span class="muted mono">/<?= e($it['slug']) ?></span> <span class="muted">· <?= (int)$it['post_count'] ?> yazı</span></span>
        <button type="button" class="btn btn-ghost btn-s" data-fill='<?= e(json_encode(['id' => (int)$it['id'], 'name' => $it['name'], 'slug' => $it['slug'], 'description' => $it['description'] ?? '', 'icon' => $it['icon'] ?? ''], JSON_UNESCAPED_UNICODE)) ?>'>Düzenle</button>
        <form method="post" action="<?= e($base) ?>/<?= (int)$it['id'] ?>/delete" data-confirm="“<?= e($it['name']) ?>” silinsin mi? Yazılar silinmez, yalnızca bağlantısı kalkar."><?= csrf_field() ?><button class="btn btn-danger btn-s" type="submit">Sil</button></form>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php if (!$items): ?><p class="muted">Henüz kayıt yok.</p><?php endif; ?>
  </section>
  <section class="panel">
    <h2 id="tax-form-title">Yeni <?= $isCat ? 'kategori' : 'etiket' ?></h2>
    <form method="post" action="<?= e($base) ?>/save" id="tax-form" class="form">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="0">
      <div class="field"><label for="t-name">Ad</label><input type="text" id="t-name" name="name" maxlength="100" required></div>
      <div class="field"><label for="t-slug">Kısa ad (URL)</label><input class="mono" type="text" id="t-slug" name="slug" maxlength="120" placeholder="addan otomatik üretilir"></div>
      <?php if ($isCat): ?>
      <div class="field"><label for="t-desc">Açıklama</label><textarea id="t-desc" name="description" maxlength="300"></textarea></div>
      <div class="field"><label for="t-icon">Simge</label><select id="t-icon" name="icon"><?php foreach ($icons as $ic): ?><option value="<?= e($ic) ?>"><?= e($ic) ?></option><?php endforeach; ?></select></div>
      <?php endif; ?>
      <div class="toolbar"><button class="btn" type="submit">Kaydet</button><button class="btn btn-ghost" type="reset" data-reset-id>Temizle</button></div>
    </form>
  </section>
</div>
