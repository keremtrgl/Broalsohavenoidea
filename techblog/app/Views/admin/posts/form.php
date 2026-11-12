<?php /** @var ?array $post @var array $categories @var array $allTags @var array $tags @var array $seriesList @var int $revCount */
use App\Services\AiData;
use App\Services\Seo;

$v = static fn(string $k, mixed $d = '') => $post[$k] ?? $d;
$pub = $v('published_at') ? date('Y-m-d\TH:i', (int)strtotime((string)$v('published_at'))) : '';
$editorCfg = [
    'content' => json_decode((string)$v('content_json', '{"blocks":[]}'), true) ?: ['blocks' => []],
    'benchmarks' => array_map(static fn($b) => ['id' => $b['id'], 'name' => $b['name']], AiData::benchmarks()['benchmarks']),
];
?>
<form method="post" action="/admin/posts/save" id="post-form" class="edit" novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int)$v('id', 0) ?>">
  <input type="hidden" name="content_json" id="content_json">
  <div class="edit-main">
    <div class="field"><label class="sr-only" for="title">Başlık</label>
      <input class="title-in" type="text" id="title" name="title" value="<?= e($v('title')) ?>" placeholder="Yazı başlığı" maxlength="200" required></div>
    <div class="field"><label for="slug">Kısa ad (URL)</label>
      <input class="mono" type="text" id="slug" name="slug" value="<?= e($v('slug')) ?>" maxlength="160" placeholder="başlıktan otomatik üretilir" pattern="[a-z0-9\-]*" data-auto="<?= $post ? '0' : '1' ?>">
      <small>Adres: /post/<span id="slug-prev"><?= e($v('slug', '…')) ?></span></small></div>
    <div class="editor-box"><div id="editor" aria-label="İçerik düzenleyici"></div></div>
    <p class="muted" id="editor-stats" aria-live="polite"><?= $post ? num((int)$post['word_count']) . ' sözcük · tahmini okuma ' . (int)$post['reading_minutes'] . ' dk' : 'Okuma süresi kaydederken otomatik hesaplanır.' ?></p>
    <div class="panel">
      <h2>Özet ve SEO</h2>
      <div class="fgrid">
        <div class="field"><label for="excerpt">Özet</label><textarea id="excerpt" name="excerpt" maxlength="400"><?= e($v('excerpt')) ?></textarea><small>Boş bırakılırsa içerikten üretilir.</small></div>
        <div class="field"><label for="meta_description">Meta açıklaması</label><textarea id="meta_description" name="meta_description" maxlength="320"><?= e($v('meta_description')) ?></textarea><small>Önerilen: 120–160 karakter. <span id="md-count"></span></small></div>
        <div class="field"><label for="meta_title">Meta başlığı</label><input type="text" id="meta_title" name="meta_title" value="<?= e($v('meta_title')) ?>" maxlength="200"><small>Boşsa yazı başlığı kullanılır.</small></div>
        <div class="field"><label for="canonical_url">Canonical URL</label><input type="url" id="canonical_url" name="canonical_url" value="<?= e($v('canonical_url')) ?>" maxlength="300" placeholder="https://…"><small>Boşsa yazının kendi adresi.</small></div>
        <div class="field"><label for="og_image">OG görseli</label><input class="mono" type="text" id="og_image" name="og_image" value="<?= e($v('og_image')) ?>" maxlength="300" placeholder="/uploads/… veya /assets/…">
          <small>Boşsa kapak görseli kullanılır. <button type="button" class="linkbtn" data-media-pick="#og_image">Kütüphaneden seç</button></small></div>
      </div>
    </div>
  </div>

  <aside class="edit-side" aria-label="Yayın ayarları">
    <div class="panel">
      <div class="field"><label for="status">Durum</label>
        <select id="status" name="status"><option value="draft"<?= $v('status', 'draft') === 'draft' ? ' selected' : '' ?>>Taslak</option><option value="published"<?= $v('status') === 'published' ? ' selected' : '' ?>>Yayımla / zamanla</option></select></div>
      <div class="field"><label for="published_at">Yayın zamanı</label><input type="datetime-local" id="published_at" name="published_at" value="<?= e($pub) ?>"><small>Gelecekte bir zaman seçerseniz yazı o anda kendiliğinden yayına girer.</small></div>
      <div class="savebar"><button class="btn" type="submit" id="save-btn">Kaydet</button><button class="btn btn-ghost" type="button" id="preview-btn">Önizle</button></div>
      <?php if ($post): ?><p class="muted">Son güncelleme: <?= e(tr_date($post['updated_at'], true)) ?> · <a href="/admin/posts/<?= (int)$post['id'] ?>/revisions">Sürüm geçmişi (<?= $revCount ?>)</a></p><?php endif; ?>
    </div>
    <div class="panel">
      <div class="field"><label for="category_id">Kategori</label>
        <select id="category_id" name="category_id"><option value="0">— Kategorisiz —</option>
        <?php foreach ($categories as $c): ?><option value="<?= (int)$c['id'] ?>"<?= (int)$v('category_id', 0) === (int)$c['id'] ? ' selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="tags">Etiketler</label><input type="text" id="tags" name="tags" value="<?= e(implode(', ', $tags)) ?>" list="tag-list" maxlength="600"><small>Virgülle ayırın. Yeni etiketler kendiliğinden oluşturulur.</small>
        <datalist id="tag-list"><?php foreach ($allTags as $t): ?><option value="<?= e($t) ?>"></option><?php endforeach; ?></datalist></div>
      <div class="field"><label for="series">Seri</label><input type="text" id="series" name="series" value="<?= e($v('series')) ?>" list="series-list" maxlength="100">
        <datalist id="series-list"><?php foreach ($seriesList as $s): ?><option value="<?= e($s) ?>"></option><?php endforeach; ?></datalist></div>
      <div class="field"><label for="series_order">Serideki sırası</label><input type="number" id="series_order" name="series_order" value="<?= (int)$v('series_order', 0) ?>" min="0" max="999"></div>
      <label class="check"><input type="checkbox" name="is_featured" value="1"<?= (int)$v('is_featured', 0) ? ' checked' : '' ?>> Ana sayfada öne çıkar</label>
      <label class="check"><input type="checkbox" name="show_updated" value="1"<?= (int)$v('show_updated', 0) ? ' checked' : '' ?>> “Son güncelleme” rozetini göster</label>
    </div>
    <div class="panel">
      <span class="lbl" id="cover-lbl">Kapak görseli</span>
      <div class="cover-prev" id="cover-prev"><?php if ($v('cover_image')): ?><img src="<?= e($v('cover_image')) ?>" alt=""><?php else: ?>Görsel seçilmedi<?php endif; ?></div>
      <div class="field"><label for="cover_image">Görsel yolu</label><input class="mono" type="text" id="cover_image" name="cover_image" value="<?= e($v('cover_image')) ?>" maxlength="300" placeholder="/uploads/… veya /assets/…"></div>
      <div class="field"><label for="cover_alt">Alternatif metin (zorunlu)</label><input type="text" id="cover_alt" name="cover_alt" value="<?= e($v('cover_alt')) ?>" maxlength="300"></div>
      <button type="button" class="btn btn-ghost btn-s" data-media-pick="#cover_image" data-media-alt="#cover_alt">Kütüphaneden seç</button>
    </div>
  </aside>
</form>

<dialog class="dlg dlg-wide" id="preview-dlg" aria-labelledby="preview-title">
  <h2 id="preview-title">Önizleme</h2>
  <div class="prose" id="preview-body"></div>
  <div class="dlg-actions"><button type="button" class="btn" data-close>Kapat</button></div>
</dialog>
<dialog class="dlg dlg-wide" id="media-dlg" aria-labelledby="media-title">
  <h2 id="media-title">Medya kütüphanesinden seç</h2>
  <div class="mgrid" id="media-grid"></div>
  <div class="dlg-actions"><a class="btn btn-ghost" href="/admin/media" target="_blank" rel="noopener">Yeni görsel yükle</a><button type="button" class="btn" data-close>Kapat</button></div>
</dialog>
<script type="application/json" id="editor-cfg"><?= Seo::jsonLd($editorCfg) ?></script>
