<?php /** @var array $post @var array $revs */ ?>
<div class="toolbar"><a class="btn btn-ghost btn-s" href="/admin/posts/<?= (int)$post['id'] ?>/edit">← Yazıya dön</a><span class="muted">“<?= e($post['title']) ?>” için en fazla 30 sürüm saklanır.</span></div>
<div class="table-wrap"><table>
  <thead><tr><th scope="col">Tarih</th><th scope="col">Başlık</th><th scope="col">Düzenleyen</th><th scope="col">Boyut</th><th scope="col"><span class="sr-only">İşlem</span></th></tr></thead>
  <tbody>
  <?php foreach ($revs as $r): ?>
    <tr><td><?= e(tr_date($r['created_at'], true)) ?></td><td><?= e($r['title']) ?></td><td><?= e($r['user_name'] ?? '—') ?></td><td><?= num((int)$r['size'] / 1024, 1) ?> kB</td>
      <td><div class="row-actions"><form method="post" action="/admin/posts/<?= (int)$post['id'] ?>/revisions/<?= (int)$r['id'] ?>/restore" data-confirm="Bu sürüm geri yüklensin mi? Şu anki içerik yeni bir sürüm olarak saklanır."><?= csrf_field() ?><button class="btn btn-ghost btn-s" type="submit">Geri yükle</button></form></div></td></tr>
  <?php endforeach; ?>
  <?php if (!$revs): ?><tr><td colspan="5" class="muted">Henüz kayıtlı sürüm yok. İçerik her değiştiğinde önceki hâli burada saklanır.</td></tr><?php endif; ?>
  </tbody>
</table></div>
