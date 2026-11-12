<?php
/** @var string $q @var array $results */
use App\Core\View;
?>
<div class="wrap">
  <header class="page-head">
    <h1>Arama</h1>
    <form class="form" action="/search" method="get" role="search">
      <div class="field"><label for="q">Aranacak sözcük</label>
        <input type="search" id="q" name="q" value="<?= e($q) ?>" minlength="2" maxlength="80" required></div>
      <div><button class="btn" type="submit">Ara</button></div>
    </form>
  </header>
  <?php if ($q !== ''): ?>
    <p aria-live="polite"><strong><?= count($results) ?></strong> sonuç bulundu: “<?= e($q) ?>”</p>
    <?php if ($results): ?>
    <div class="grid"><?php foreach ($results as $post): ?><?= View::partial('partials/card', ['post' => $post, 'h' => 2]) ?><?php endforeach; ?></div>
    <?php else: ?><p class="empty">Sonuç bulunamadı. Daha kısa veya farklı bir sözcük deneyin.</p><?php endif; ?>
  <?php endif; ?>
</div>
