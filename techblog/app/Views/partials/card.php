<?php /** @var array $post @var bool $eager @var int $h heading level */ $h = (int)($h ?? 3); ?>
<article class="card">
  <?php if ($post['cover_image']): ?>
  <div class="card-img"><img src="<?= e($post['cover_image']) ?>" alt="<?= e($post['cover_alt']) ?>" width="1200" height="630"<?= !empty($eager) ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"' ?>></div>
  <?php endif; ?>
  <div class="card-body">
    <?php if ($post['category_name']): ?><span class="card-cat"><?= e($post['category_name']) ?></span><?php endif; ?>
    <h<?= $h ?> class="card-t"><a href="/post/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></h<?= $h ?>>
    <p><?= e(str_limit((string)$post['excerpt'], 150)) ?></p>
    <div class="card-meta"><time datetime="<?= e(date('Y-m-d', (int)strtotime((string)$post['published_at']))) ?>"><?= e(tr_date($post['published_at'])) ?></time><span><?= (int)$post['reading_minutes'] ?> dk okuma</span></div>
  </div>
</article>
