<?php /** @var int $page @var int $pages @var string $base (URL with trailing ? or &) */
if ($pages > 1): ?>
<nav class="pager" aria-label="Sayfalama">
  <?php for ($i = 1; $i <= min($pages, 50); $i++): ?>
    <?php if ($i === $page): ?><span aria-current="page"><?= $i ?></span><?php else: ?><a href="<?= e($base . 'page=' . $i) ?>" aria-label="Sayfa <?= $i ?>"><?= $i ?></a><?php endif; ?>
  <?php endfor; ?>
</nav>
<?php endif; ?>
