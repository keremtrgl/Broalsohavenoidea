<?php /** @var array $items @var int $page @var int $pages @var int $total */
use App\Core\View;
use App\Models\Media;
?>
<form class="drop" id="drop" novalidate>
  <strong>Görselleri buraya sürükleyip bırakın</strong>
  <span>veya</span>
  <label class="btn btn-ghost" for="file-in">Dosya seç</label>
  <input class="sr-only" type="file" id="file-in" accept=".jpg,.jpeg,.png,.webp,.avif,.svg,image/jpeg,image/png,image/webp,image/avif,image/svg+xml" multiple>
  <div class="field"><label for="upload-alt">Alternatif metin (zorunlu)</label><input type="text" id="upload-alt" maxlength="300" placeholder="Görselde ne olduğunu kısaca anlatın" required></div>
  <small>JPG, PNG, WebP, AVIF veya SVG · en fazla 8 MB. Görseller WebP<?= function_exists('imageavif') ? ' ve AVIF' : '' ?> biçimine çevrilir, 480–1600 px genişliklerinde <code>srcset</code> sürümleri üretilir; SVG dosyaları temizlenir.</small>
  <div id="upload-status" aria-live="polite"></div>
</form>
<p class="muted"><?= num($total) ?> görsel</p>
<div class="mgrid">
  <?php foreach ($items as $m): $thumb = Media::variants($m)['webp'][0]['path'] ?? $m['path']; ?>
  <div class="mitem">
    <img src="<?= e($thumb) ?>" alt="<?= e($m['alt']) ?>" width="<?= (int)$m['width'] ?>" height="<?= (int)$m['height'] ?>" loading="lazy">
    <form method="post" action="/admin/media/<?= (int)$m['id'] ?>/update">
      <?= csrf_field() ?>
      <label class="sr-only" for="alt-<?= (int)$m['id'] ?>">Alternatif metin</label>
      <input type="text" id="alt-<?= (int)$m['id'] ?>" name="alt" value="<?= e($m['alt']) ?>" maxlength="300" required>
      <small class="muted mono"><?= e($m['path']) ?><br><?= (int)$m['width'] ?>×<?= (int)$m['height'] ?> · <?= num((int)$m['size'] / 1024, 0) ?> kB</small>
      <div class="row-actions"><button class="btn btn-ghost btn-s" type="submit">Kaydet</button><button class="btn btn-danger btn-s" type="submit" formaction="/admin/media/<?= (int)$m['id'] ?>/delete" data-confirm-btn="Bu görsel ve tüm boyutları silinsin mi? Yazılarda kullanılıyorsa bozuk görünür.">Sil</button></div>
    </form>
  </div>
  <?php endforeach; ?>
</div>
<?php if (!$items): ?><p class="muted">Kütüphane boş. İlk görseli yukarıdan yükleyin.</p><?php endif; ?>
<?= View::partial('partials/pager', ['page' => $page, 'pages' => $pages, 'base' => '/admin/media?']) ?>
