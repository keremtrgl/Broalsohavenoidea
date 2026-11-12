<?php /** @var array $models @var array $benchmarks @var array $scores @var string $updated */
use App\Services\AiData;
$modelNames = array_column($models, 'name', 'id');
$benchNames = array_column($benchmarks, 'name', 'id');
$j = static fn(array $a): string => e(json_encode($a, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
?>
<p class="muted">Veriler <code>data/ai-models.json</code> ve <code>data/ai-benchmarks.json</code> dosyalarında tutulur. Kaydettiğinizde ilgili yazıların önbelleği kendiliğinden temizlenir. Son güncelleme: <strong><?= e(tr_date($updated)) ?></strong>. <strong>Doğrulayamadığınız skoru girmeyin</strong>; boş kalan hücreler sitede “—” olarak görünür.</p>

<section class="panel">
  <div class="toolbar"><h2 class="grow">Modeller (<?= count($models) ?>)</h2><button class="btn btn-s" type="button" data-open="#model-dlg" data-reset="#model-form">Model ekle</button></div>
  <div class="table-wrap"><table>
    <thead><tr><th scope="col">Model</th><th scope="col">Sağlayıcı</th><th scope="col">Tür</th><th scope="col">Açık</th><th scope="col">Fiyat (girdi / çıktı)</th><th scope="col">Yayında</th><th scope="col"><span class="sr-only">İşlemler</span></th></tr></thead>
    <tbody>
    <?php foreach ($models as $m): ?>
      <tr><th scope="row"><?= e($m['name']) ?><br><span class="muted mono"><?= e($m['id']) ?></span></th><td><?= e($m['provider'] ?? '') ?></td><td><?= e(AiData::TYPES[$m['type'] ?? 'llm'] ?? '') ?></td>
        <td><?= !empty($m['open']) ? 'Evet' : 'Hayır' ?></td>
        <td><?= isset($m['price_in'], $m['price_out']) ? '$' . e((string)$m['price_in']) . ' / $' . e((string)$m['price_out']) : '—' ?></td>
        <td><?= !empty($m['published']) ? '<span class="pill pill-ok">yayında</span>' : '<span class="pill">gizli</span>' ?></td>
        <td><div class="row-actions"><button class="btn btn-ghost btn-s" type="button" data-open="#model-dlg" data-fill-form="#model-form" data-values='<?= $j($m) ?>'>Düzenle</button>
          <form method="post" action="/admin/ai/model/delete" data-confirm="“<?= e($m['name']) ?>” ve bu modele ait bütün skorlar silinsin mi?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($m['id']) ?>"><button class="btn btn-danger btn-s" type="submit">Sil</button></form></div></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
</section>

<section class="panel">
  <div class="toolbar"><h2 class="grow">Kıyaslamalar (<?= count($benchmarks) ?>)</h2><button class="btn btn-s" type="button" data-open="#bench-dlg" data-reset="#bench-form">Kıyaslama ekle</button></div>
  <div class="table-wrap"><table>
    <thead><tr><th scope="col">Kıyaslama</th><th scope="col">Kategori</th><th scope="col">Birim</th><th scope="col">Yön</th><th scope="col"><span class="sr-only">İşlemler</span></th></tr></thead>
    <tbody>
    <?php foreach ($benchmarks as $b): ?>
      <tr><th scope="row"><?= e($b['name']) ?><br><span class="muted mono"><?= e($b['id']) ?></span></th><td><?= e(AiData::CATEGORIES[$b['category'] ?? ''] ?? '') ?></td><td><?= e($b['unit'] ?? '') ?: '—' ?></td><td><?= !empty($b['higher_is_better']) ? 'Yüksek iyi' : 'Düşük iyi' ?></td>
        <td><div class="row-actions"><button class="btn btn-ghost btn-s" type="button" data-open="#bench-dlg" data-fill-form="#bench-form" data-values='<?= $j($b) ?>'>Düzenle</button>
          <form method="post" action="/admin/ai/benchmark/delete" data-confirm="“<?= e($b['name']) ?>” ve bütün skorları silinsin mi?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($b['id']) ?>"><button class="btn btn-danger btn-s" type="submit">Sil</button></form></div></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
</section>

<section class="panel">
  <div class="toolbar"><h2 class="grow">Skorlar (<?= count($scores) ?>)</h2>
    <label class="sr-only" for="score-filter">Skorlarda süz</label><input type="search" id="score-filter" placeholder="Model veya kıyaslama süz…" data-filter="#score-rows" data-search-focus>
    <button class="btn btn-s" type="button" data-open="#score-dlg" data-reset="#score-form">Skor ekle</button></div>
  <div class="table-wrap"><table>
    <thead><tr><th scope="col">Model</th><th scope="col">Kıyaslama</th><th scope="col">Skor</th><th scope="col">Kaynak</th><th scope="col">Tarih</th><th scope="col">Bildiren</th><th scope="col"><span class="sr-only">İşlemler</span></th></tr></thead>
    <tbody id="score-rows">
    <?php foreach ($scores as $s): ?>
      <tr><th scope="row"><?= e($modelNames[$s['model']] ?? $s['model']) ?></th><td><?= e($benchNames[$s['benchmark']] ?? $s['benchmark']) ?></td><td><strong><?= e((string)$s['value']) ?></strong></td>
        <td><a href="<?= e($s['source_url'] ?? '') ?>" target="_blank" rel="noopener noreferrer"><?= e($s['source_name'] ?? 'kaynak') ?></a></td><td><?= e($s['date'] ?? '') ?></td>
        <td><?= ($s['reported_by'] ?? '') === 'vendor' ? '<span class="pill pill-warn">üretici</span>' : '<span class="pill pill-ok">bağımsız</span>' ?></td>
        <td><div class="row-actions"><button class="btn btn-ghost btn-s" type="button" data-open="#score-dlg" data-fill-form="#score-form" data-values='<?= $j($s) ?>'>Düzenle</button>
          <form method="post" action="/admin/ai/score/delete" data-confirm="Bu skor silinsin mi?"><?= csrf_field() ?><input type="hidden" name="model" value="<?= e($s['model']) ?>"><input type="hidden" name="benchmark" value="<?= e($s['benchmark']) ?>"><button class="btn btn-danger btn-s" type="submit">Sil</button></form></div></td></tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
</section>

<dialog class="dlg" id="model-dlg" aria-labelledby="model-dlg-t">
  <h2 id="model-dlg-t">Model</h2>
  <form method="post" action="/admin/ai/model" id="model-form">
    <?= csrf_field() ?>
    <div class="fgrid">
      <div class="field"><label for="m-id">Kimlik</label><input class="mono" type="text" id="m-id" name="id" maxlength="60" pattern="[a-z0-9_\-.]+" required><small>Küçük harf, rakam, tire. Düzenlerken değiştirmeyin.</small></div>
      <div class="field"><label for="m-name">Ad</label><input type="text" id="m-name" name="name" maxlength="100" required></div>
      <div class="field"><label for="m-provider">Sağlayıcı</label><input type="text" id="m-provider" name="provider" maxlength="80" required></div>
      <div class="field"><label for="m-type">Tür</label><select id="m-type" name="type"><?php foreach (AiData::TYPES as $k => $label): ?><option value="<?= $k ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="m-license">Lisans</label><input type="text" id="m-license" name="license" maxlength="80"></div>
      <div class="field"><label for="m-release">Çıkış (YYYY-AA)</label><input type="text" id="m-release" name="release" maxlength="10" pattern="\d{4}(-\d{2})?"></div>
      <div class="field"><label for="m-ctx">Bağlam penceresi (belirteç)</label><input type="number" id="m-ctx" name="context_window" min="0" step="1"></div>
      <div class="field"><label for="m-pin">Girdi fiyatı ($ / 1M)</label><input type="number" id="m-pin" name="price_in" min="0" step="0.001"></div>
      <div class="field"><label for="m-pout">Çıktı fiyatı ($ / 1M)</label><input type="number" id="m-pout" name="price_out" min="0" step="0.001"></div>
      <div class="field"><label for="m-purl">Fiyat kaynağı (URL)</label><input type="url" id="m-purl" name="price_source_url" maxlength="500"><small>Fiyat girildiyse zorunlu.</small></div>
      <div class="field"><label for="m-pdate">Fiyat tarihi</label><input type="date" id="m-pdate" name="price_date"></div>
      <div class="field"><label for="m-url">Model sayfası (URL)</label><input type="url" id="m-url" name="url" maxlength="500"></div>
    </div>
    <div class="field"><label for="m-best">En uygun kullanım</label><input type="text" id="m-best" name="best_for" maxlength="200"></div>
    <fieldset class="toolbar"><legend class="lbl">Girdi türleri</legend>
      <?php foreach (['text' => 'Metin', 'image' => 'Görsel', 'audio' => 'Ses', 'video' => 'Video', 'pdf' => 'PDF'] as $k => $label): ?><label class="check"><input type="checkbox" name="modalities[]" value="<?= $k ?>"> <?= $label ?></label><?php endforeach; ?>
    </fieldset>
    <div class="toolbar"><label class="check"><input type="checkbox" name="open" value="1"> Açık ağırlıklı</label><label class="check"><input type="checkbox" name="free_tier" value="1"> Ücretsiz kullanım seçeneği var</label><label class="check"><input type="checkbox" name="published" value="1" checked> Yayında</label></div>
    <div class="dlg-actions"><button type="button" class="btn btn-ghost" data-close>Vazgeç</button><button type="submit" class="btn">Kaydet</button></div>
  </form>
</dialog>

<dialog class="dlg" id="bench-dlg" aria-labelledby="bench-dlg-t">
  <h2 id="bench-dlg-t">Kıyaslama</h2>
  <form method="post" action="/admin/ai/benchmark" id="bench-form">
    <?= csrf_field() ?>
    <div class="fgrid">
      <div class="field"><label for="b-id">Kimlik</label><input class="mono" type="text" id="b-id" name="id" maxlength="60" pattern="[a-z0-9_]+" required></div>
      <div class="field"><label for="b-name">Ad</label><input type="text" id="b-name" name="name" maxlength="100" required></div>
      <div class="field"><label for="b-cat">Kategori</label><select id="b-cat" name="category"><?php foreach (AiData::CATEGORIES as $k => $label): ?><option value="<?= $k ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="b-unit">Birim</label><input type="text" id="b-unit" name="unit" maxlength="10" placeholder="% veya boş (Elo)"></div>
      <div class="field"><label for="b-url">Resmî sayfa (URL)</label><input type="url" id="b-url" name="url" maxlength="500"></div>
    </div>
    <div class="field"><label for="b-desc">Açıklama</label><textarea id="b-desc" name="description" maxlength="400"></textarea></div>
    <label class="check"><input type="checkbox" name="higher_is_better" value="1" checked> Yüksek skor daha iyi</label>
    <div class="dlg-actions"><button type="button" class="btn btn-ghost" data-close>Vazgeç</button><button type="submit" class="btn">Kaydet</button></div>
  </form>
</dialog>

<dialog class="dlg" id="score-dlg" aria-labelledby="score-dlg-t">
  <h2 id="score-dlg-t">Skor</h2>
  <form method="post" action="/admin/ai/score" id="score-form">
    <?= csrf_field() ?>
    <div class="fgrid">
      <div class="field"><label for="s-model">Model</label><select id="s-model" name="model" required><?php foreach ($modelNames as $id => $name): ?><option value="<?= e($id) ?>"><?= e($name) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="s-bench">Kıyaslama</label><select id="s-bench" name="benchmark" required><?php foreach ($benchNames as $id => $name): ?><option value="<?= e($id) ?>"><?= e($name) ?></option><?php endforeach; ?></select></div>
      <div class="field"><label for="s-value">Skor</label><input type="number" id="s-value" name="value" step="any" required></div>
      <div class="field"><label for="s-date">Ölçüm tarihi (zorunlu)</label><input type="date" id="s-date" name="date" required></div>
      <div class="field"><label for="s-src">Kaynak adı (zorunlu)</label><input type="text" id="s-src" name="source_name" maxlength="100" required placeholder="ör. LMArena"></div>
      <div class="field"><label for="s-url">Kaynak URL (zorunlu)</label><input type="url" id="s-url" name="source_url" maxlength="500" required></div>
      <div class="field"><label for="s-by">Bildiren</label><select id="s-by" name="reported_by"><option value="independent">Bağımsız ölçüm</option><option value="vendor">Üretici beyanı</option></select></div>
      <div class="field"><label for="s-note">Not</label><input type="text" id="s-note" name="note" maxlength="200"></div>
    </div>
    <div class="dlg-actions"><button type="button" class="btn btn-ghost" data-close>Vazgeç</button><button type="submit" class="btn">Kaydet</button></div>
  </form>
</dialog>
