<?php
/** @var string $title @var string $lead @var string $kicker @var array $posts @var int $page @var int $pages @var string $basePath @var array $crumbs */
use App\Core\View;
?>
<div class="wrap">
  <header class="page-head">
    <nav aria-label="Sayfa yolu"><ol class="crumbs">
      <?php foreach ($crumbs as $i => [$name, $u]): ?><li><?php if ($i < count($crumbs) - 1): ?><a href="<?= e($u) ?>"><?= e($name) ?></a><?php else: ?><span aria-current="page"><?= e($name) ?></span><?php endif; ?></li><?php endforeach; ?>
    </ol></nav>
    <?php if ($kicker !== ''): ?><span class="kicker"><?= e($kicker) ?></span><?php endif; ?>
    <h1><?= e($title) ?></h1>
    <?php if ($lead !== ''): ?><p><?= e($lead) ?></p><?php endif; ?>
  </header>
  <?php if ($posts): ?>
  <div class="grid">
    <?php foreach ($posts as $i => $post): ?><?= View::partial('partials/card', ['post' => $post, 'eager' => $i === 0, 'h' => 2]) ?><?php endforeach; ?>
  </div>
  <?php if ($pages > 1): ?>
  <nav class="pager" aria-label="Sayfalama">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <?php if ($i === $page): ?><span aria-current="page"><?= $i ?></span><?php else: ?><a href="<?= e($basePath . ($i > 1 ? '?page=' . $i : '')) ?>" aria-label="Sayfa <?= $i ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
  </nav>
  <?php endif; ?>
  <?php else: ?><p class="empty">Bu bölümde henüz yazı yok.</p><?php endif; ?>
</div>
