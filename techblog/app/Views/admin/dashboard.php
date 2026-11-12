<?php /** @var array $stats @var array $chart @var array $top @var array $activity */ ?>
<div class="stats">
  <div class="stat"><b><?= num($stats['published']) ?></b><span>Yayımlanan yazı</span></div>
  <div class="stat"><b><?= num($stats['drafts']) ?></b><span>Taslak</span></div>
  <div class="stat"><b><?= num($stats['scheduled']) ?></b><span>Zamanlanmış</span></div>
  <div class="stat"><b><?= num($stats['comments']) ?></b><span>Onaylı yorum</span></div>
  <div class="stat"><b><?= num($stats['pending']) ?></b><span>Onay bekleyen yorum</span></div>
  <div class="stat"><b><?= num($stats['users']) ?></b><span>Kullanıcı</span></div>
  <div class="stat"><b><?= num($stats['views30']) ?></b><span>Görüntülenme (30 gün)</span></div>
</div>
<figure class="chart" data-chart="<?= e(json_encode($chart, JSON_UNESCAPED_UNICODE)) ?>">
  <p class="chart-title">Son 30 günde görüntülenme</p>
  <div class="chart-box"><canvas role="img" aria-label="Son 30 günün günlük görüntülenme sayıları çizgi grafiği. Toplam: <?= num($stats['views30']) ?>"></canvas></div>
</figure>
<div class="cols">
  <section class="panel">
    <h2>En çok okunan yazılar</h2>
    <?php if ($top): ?>
    <div class="table-wrap"><table><thead><tr><th scope="col">Yazı</th><th scope="col">Görüntülenme</th></tr></thead><tbody>
      <?php foreach ($top as $t): ?><tr><td><a href="/admin/posts/<?= (int)$t['id'] ?>/edit"><?= e($t['title']) ?></a></td><td><?= num((int)$t['views']) ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <?php else: ?><p class="muted">Henüz yazı yok.</p><?php endif; ?>
  </section>
  <section class="panel">
    <h2>Son etkinlikler</h2>
    <?php if ($activity): ?>
    <div class="table-wrap"><table><thead><tr><th scope="col">Zaman</th><th scope="col">Kim</th><th scope="col">İşlem</th></tr></thead><tbody>
      <?php foreach ($activity as $a): ?><tr><td><?= e(tr_date($a['created_at'], true)) ?></td><td><?= e($a['user_name'] ?? '—') ?></td><td><span class="mono"><?= e($a['action']) ?></span> <?= e($a['entity_id'] ?? '') ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
    <?php else: ?><p class="muted">Henüz kayıt yok.</p><?php endif; ?>
  </section>
</div>
