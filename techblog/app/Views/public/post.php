<?php
/** @var array $post @var array $r @var array $tags @var array $related @var array $nav @var array $crumbs @var string $updated @var array $seriesPosts */
use App\Core\View;

$url = url('/post/' . $post['slug']);
$shareText = rawurlencode((string)$post['title']);
$shareUrl = rawurlencode($url);
?>
<div class="progress" id="progress" aria-hidden="true"></div>
<article data-post-id="<?= (int)$post['id'] ?>">
  <header class="wrap post-head">
    <nav aria-label="Sayfa yolu"><ol class="crumbs">
      <?php foreach ($crumbs as $i => [$name, $u]): ?><li><?php if ($i < count($crumbs) - 1): ?><a href="<?= e($u) ?>"><?= e($name) ?></a><?php else: ?><span aria-current="page"><?= e(str_limit($name, 60)) ?></span><?php endif; ?></li><?php endforeach; ?>
    </ol></nav>
    <?php if ($post['category_name']): ?><a class="kicker" href="/category/<?= e($post['category_slug']) ?>"><?= e($post['category_name']) ?></a><?php endif; ?>
    <h1><?= e($post['title']) ?></h1>
    <?php if ((int)$post['show_updated'] === 1): ?>
    <p class="badge-upd"><?= icon('clock') ?> Son güncelleme: <time datetime="<?= e(date('Y-m-d', (int)strtotime($updated))) ?>"><?= e(tr_date($updated)) ?></time></p>
    <?php endif; ?>
    <p class="post-lead"><?= e($post['excerpt']) ?></p>
    <div class="meta">
      <span><?= e($post['author_name'] ?: setting('site_name', 'TechBlog')) ?></span>
      <span>Yayın: <time datetime="<?= e(date('Y-m-d', (int)strtotime((string)$post['published_at']))) ?>"><?= e(tr_date($post['published_at'])) ?></time></span>
      <span>Tahmini okuma süresi: <?= (int)$post['reading_minutes'] ?> dk</span>
      <span><?= num((int)$r['words']) ?> sözcük</span>
    </div>
    <?php if ($post['cover_image']): ?>
    <figure class="cover"><img src="<?= e($post['cover_image']) ?>" alt="<?= e($post['cover_alt']) ?>" width="1200" height="630" fetchpriority="high"></figure>
    <?php endif; ?>
  </header>

  <div class="wrap post-grid">
    <?php if (count($r['toc']) > 2): ?>
    <nav class="toc" id="toc" aria-label="İçindekiler">
      <p class="toc-title">İçindekiler</p>
      <ol>
        <?php foreach ($r['toc'] as $t): ?><li class="l<?= (int)$t['level'] ?>"><a href="#<?= e($t['id']) ?>"><?= e($t['text']) ?></a></li><?php endforeach; ?>
      </ol>
    </nav>
    <?php endif; ?>
    <div>
      <div class="prose" id="content">
        <?= $r['html'] /* rendered and sanitized by BlockRenderer */ ?>
      </div>

      <?php if ($tags): ?>
      <ul class="tags" aria-label="Etiketler">
        <?php foreach ($tags as $t): ?><li><a href="/tag/<?= e($t['slug']) ?>">#<?= e($t['name']) ?></a></li><?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <div class="share">
        <span>Paylaş:</span>
        <a class="btn btn-ghost" href="https://twitter.com/intent/tweet?text=<?= $shareText ?>&amp;url=<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer">X</a>
        <a class="btn btn-ghost" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
        <a class="btn btn-ghost" href="https://wa.me/?text=<?= $shareText ?>%20<?= $shareUrl ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
        <button class="btn btn-ghost" type="button" data-copy-link="<?= e($url) ?>">Bağlantıyı kopyala</button>
        <button class="btn btn-ghost" type="button" data-native-share hidden>Paylaş…</button>
      </div>

      <nav class="pn" aria-label="Önceki ve sonraki yazı">
        <?php if ($nav['prev']): ?><a class="prev" href="/post/<?= e($nav['prev']['slug']) ?>" rel="prev"><small>← Önceki yazı</small><?= e($nav['prev']['title']) ?></a><?php endif; ?>
        <?php if ($nav['next']): ?><a class="next" href="/post/<?= e($nav['next']['slug']) ?>" rel="next"><small>Sonraki yazı →</small><?= e($nav['next']['title']) ?></a><?php endif; ?>
      </nav>

      <section class="comments" id="comments" aria-labelledby="h-comments">
        <h2 id="h-comments">Yorumlar</h2>
        <div id="comment-form-slot">
          <div class="signin-box"><span>Yorum yazmak için giriş yapmalısınız.</span><a class="btn" href="/login?next=<?= e('/post/' . $post['slug']) ?>">Giriş yap</a></div>
        </div>
        <div id="comment-list" aria-live="polite"><p class="hint">Yorumlar yükleniyor…</p></div>
        <noscript><p class="hint">Yorumları görmek için JavaScript gereklidir.</p></noscript>
      </section>
    </div>
  </div>
</article>

<?php if (count($seriesPosts) > 1): ?>
<section class="section section-alt" aria-labelledby="h-series">
  <div class="wrap">
    <div class="section-head"><h2 id="h-series">“<?= e($post['series']) ?>” serisinin diğer yazıları</h2></div>
    <ol class="series">
      <?php foreach ($seriesPosts as $s): ?><li><a href="/post/<?= e($s['slug']) ?>"<?= $s['slug'] === $post['slug'] ? ' aria-current="page"' : '' ?>><?= e($s['title']) ?></a></li><?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>

<?php if ($related): ?>
<section class="section" aria-labelledby="h-related">
  <div class="wrap">
    <div class="section-head"><h2 id="h-related">İlgili yazılar</h2></div>
    <div class="grid"><?php foreach ($related as $rp): ?><?= View::partial('partials/card', ['post' => $rp]) ?><?php endforeach; ?></div>
  </div>
</section>
<?php endif; ?>
