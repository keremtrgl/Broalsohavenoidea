<?php /** @var array $info @var array $logs */ ?>
<div class="cols">
  <section class="panel">
    <h2>Durum</h2>
    <dl class="kv"><?php foreach ($info as $k => $val): ?><dt><?= e($k) ?></dt><dd><?= e($val) ?></dd><?php endforeach; ?></dl>
  </section>
  <section class="panel">
    <h2>Bakım işlemleri</h2>
    <div class="form">
      <form method="post" action="/admin/system/cache"><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Önbelleği temizle</button> <span class="muted">Sayfa ve veri önbelleğini siler.</span></form>
      <form method="post" action="/admin/system/sitemap"><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Site haritasını yeniden üret</button> <span class="muted"><a href="/sitemap.xml" target="_blank" rel="noopener">sitemap.xml</a></span></form>
      <form method="post" action="/admin/system/backup" data-native><?= csrf_field() ?><button class="btn btn-ghost" type="submit">Veritabanı yedeğini indir</button> <span class="muted">Sıkıştırılmış SQL dosyası (.sql.gz).</span></form>
    </div>
    <p class="muted">Yedek dosyası kullanıcı e-postaları gibi kişisel veriler içerir; güvenli bir yerde saklayın. <code>public_html/uploads</code> klasörünü ve <code>data/</code> klasörünü ayrıca yedekleyin.</p>
  </section>
</div>
<section class="panel">
  <h2>Günlük dosyaları</h2>
  <?php if ($logs): ?>
  <div class="table-wrap"><table><thead><tr><th scope="col">Dosya</th><th scope="col">Boyut</th><th scope="col">Son değişiklik</th></tr></thead><tbody>
    <?php foreach ($logs as $l): ?><tr><th scope="row"><a class="mono" href="/admin/system/log?file=<?= e(rawurlencode($l['name'])) ?>"><?= e($l['name']) ?></a></th><td><?= num($l['size'] / 1024, 1) ?> kB</td><td><?= e(tr_date(date('Y-m-d H:i:s', $l['mtime']), true)) ?></td></tr><?php endforeach; ?>
  </tbody></table></div>
  <?php else: ?><p class="muted">Henüz günlük kaydı yok.</p><?php endif; ?>
</section>
