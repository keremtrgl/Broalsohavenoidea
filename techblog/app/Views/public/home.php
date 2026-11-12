<?php
/** @var array $featured @var array $latest @var array $categories @var array $series @var string $seriesName */
use App\Core\View;
?>
<section class="hero">
  <div class="wrap hero-in">
    <div>
      <span class="kicker">Teknoloji rehberleri</span>
      <h1>Karmaşık teknolojiyi <em>tek yazıda</em> anlayın</h1>
      <p><?= e((string)setting('site_description', 'Programlama dillerinden ağ donanımına, işlemcilerden yapay zekâ modellerine: her kavramı görsellerle, tablolarla ve kaynak göstererek açıklıyoruz.')) ?></p>
      <div class="hero-cta">
        <a class="btn" href="/series/<?= e(slugify($seriesName)) ?>">Seriye başla <?= icon('arrow') ?></a>
        <a class="btn btn-ghost" href="/archive">Tüm yazılar</a>
      </div>
    </div>
    <div class="hero-art fx" data-dur="8">
      <svg viewBox="0 0 560 420" role="img" aria-label="Ortadaki işlemciden sunucuya, telefona, buluta ve dizüstü bilgisayara veri paketleri taşıyan devre yolları çizimi">
        <g fill="none" class="l-line w2 rc">
          <path d="M280 210H120V96M280 210h160V96M280 210H120v114M280 210h160v114"/>
          <path d="M280 150V60M280 270v90" class="dash"/>
        </g>
        <rect x="216" y="146" width="128" height="128" rx="18" class="s-bg2 l-acc w3"/>
        <rect x="240" y="170" width="80" height="80" rx="10" class="s-soft l-acc w2"/>
        <g class="l-acc w3 rc"><path d="M246 146v-16M270 146v-16M294 146v-16M318 146v-16M246 290v-16M270 290v-16M294 290v-16M318 290v-16M216 176h-16M216 200h-16M216 224h-16M216 248h-16M360 176h-16M360 200h-16M360 224h-16M360 248h-16"/></g>
        <text x="280" y="216" text-anchor="middle" class="t-m">CPU</text>
        <circle cx="280" cy="210" r="56" class="s-none l-acc w2" data-a="wave 3"/>
        <g><rect x="76" y="44" width="88" height="60" rx="10" class="s-bg2 l-fg w2"/><path d="M90 62h60M90 76h60M90 90h34" class="l-mut w2 rc"/><circle cx="146" cy="90" r="4" class="s-acc" data-a="blink 1.4"/></g>
        <g><rect x="410" y="36" width="60" height="68" rx="10" class="s-bg2 l-fg w2"/><rect x="420" y="46" width="40" height="38" rx="4" class="s-bg3"/><circle cx="440" cy="94" r="4" class="s-mut"/></g>
        <g><path d="M92 352c-16 0-26-10-26-22s10-22 24-22c4-12 14-18 26-18 16 0 28 10 30 24 12 0 20 8 20 19s-8 19-20 19z" class="s-bg2 l-fg w2 rc"/></g>
        <g><rect x="396" y="318" width="92" height="56" rx="8" class="s-bg2 l-fg w2"/><path d="M382 384h120" class="l-fg w3 rc"/><path d="M410 336h40M410 350h60" class="l-mut w2 rc"/></g>
        <g><rect x="252" y="24" width="56" height="36" rx="8" class="s-bg2 l-fg w2"/><text x="280" y="47" text-anchor="middle" class="t-m">&lt;/&gt;</text></g>
        <g><rect x="252" y="360" width="56" height="36" rx="8" class="s-bg2 l-fg w2"/><text x="280" y="383" text-anchor="middle" class="t-m">0101</text></g>
        <circle cx="280" cy="210" r="7" class="s-acc" data-a="route -160,0 -160,-110 0 .3"/>
        <circle cx="280" cy="210" r="7" class="s-acc2" data-a="route 160,0 160,-110 .2 .5"/>
        <circle cx="280" cy="210" r="7" class="s-blue" data-a="route -160,0 -160,110 .45 .75"/>
        <circle cx="280" cy="210" r="7" class="s-acc" data-a="route 160,0 160,110 .65 .95"/>
        <circle cx="280" cy="64" r="5" class="s-pur" data-a="move 0 80 .1 .35"/>
        <circle cx="280" cy="356" r="5" class="s-pur" data-a="move 0 -80 .55 .8"/>
      </svg>
    </div>
  </div>
</section>

<?php if ($featured): ?>
<section class="section" aria-labelledby="h-featured">
  <div class="wrap">
    <div class="section-head"><h2 id="h-featured">Öne çıkanlar</h2></div>
    <div class="featured">
      <?php foreach ($featured as $i => $post): ?><?= View::partial('partials/card', ['post' => $post, 'eager' => $i === 0]) ?><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($categories): ?>
<section class="section section-alt" aria-labelledby="h-cats">
  <div class="wrap">
    <div class="section-head"><h2 id="h-cats">Kategoriler</h2></div>
    <div class="cats">
      <?php foreach ($categories as $c): ?>
      <a class="cat" href="/category/<?= e($c['slug']) ?>">
        <span class="cat-ic"><?= icon((string)$c['icon']) ?></span>
        <span><strong><?= e($c['name']) ?></strong><span><?= e($c['description']) ?> · <?= (int)$c['post_count'] ?> yazı</span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section" aria-labelledby="h-latest">
  <div class="wrap">
    <div class="section-head"><h2 id="h-latest">Son yazılar</h2><a href="/archive">Tümünü gör</a></div>
    <?php if ($latest): ?>
    <div class="grid">
      <?php foreach ($latest as $post): ?><?= View::partial('partials/card', ['post' => $post]) ?><?php endforeach; ?>
    </div>
    <?php else: ?><p class="empty">Henüz yayımlanmış yazı yok.</p><?php endif; ?>
  </div>
</section>

<?php if ($series): ?>
<section class="section section-alt" aria-labelledby="h-series">
  <div class="wrap">
    <div class="section-head"><h2 id="h-series">“<?= e($seriesName) ?>” serisi</h2><a href="/series/<?= e(slugify($seriesName)) ?>">Seri sayfası</a></div>
    <ol class="series">
      <?php foreach ($series as $s): ?><li><a href="/post/<?= e($s['slug']) ?>"><?= e($s['title']) ?></a></li><?php endforeach; ?>
    </ol>
  </div>
</section>
<?php endif; ?>
