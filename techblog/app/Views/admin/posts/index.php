<?php /** @var array $rows @var string $status @var string $q @var int $page @var int $pages @var int $total */
use App\Core\View;
$tabs = ['' => 'Tümü', 'published' => 'Yayında', 'scheduled' => 'Zamanlanmış', 'draft' => 'Taslak'];
?>
<div class="toolbar">
  <div class="chips">
    <?php foreach ($tabs as $k => $label): ?><a href="/admin/posts<?= $k !== '' ? '?status=' . $k : '' ?>"<?= $status === $k ? ' aria-current="page"' : '' ?>><?= e($label) ?></a><?php endforeach; ?>
  </div>
  <span class="grow"></span>
  <form method="get" action="/admin/posts" role="search" class="toolbar">
    <?php if ($status !== ''): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
    <label class="sr-only" for="q">Başlıkta ara</label><input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="Başlıkta ara…" data-search-focus>
    <button class="btn btn-ghost btn-s" type="submit">Ara</button>
  </form>
  <a class="btn" href="/admin/posts/new">Yeni yazı</a>
</div>
<p class="muted"><?= num($total) ?> yazı</p>
<div class="table-wrap"><table>
  <thead><tr><th scope="col">Başlık</th><th scope="col">Kategori</th><th scope="col">Durum</th><th scope="col">Yayın tarihi</th><th scope="col">Görüntülenme</th><th scope="col"><span class="sr-only">İşlemler</span></th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r):
      $scheduled = $r['status'] === 'published' && strtotime((string)$r['published_at']) > time(); ?>
    <tr>
      <th scope="row"><a href="/admin/posts/<?= (int)$r['id'] ?>/edit"><?= e($r['title']) ?></a><?= (int)$r['is_featured'] ? ' <span class="pill pill-ok">öne çıkan</span>' : '' ?></th>
      <td><?= e($r['category_name'] ?? '—') ?></td>
      <td><?php if ($r['status'] === 'draft'): ?><span class="pill">Taslak</span><?php elseif ($scheduled): ?><span class="pill pill-warn">Zamanlanmış</span><?php else: ?><span class="pill pill-ok">Yayında</span><?php endif; ?></td>
      <td><?= $r['published_at'] ? e(tr_date($r['published_at'], true)) : '—' ?></td>
      <td><?= num((int)$r['views']) ?></td>
      <td><div class="row-actions">
        <?php if ($r['status'] === 'published' && !$scheduled): ?><a class="btn btn-ghost btn-s" href="/post/<?= e($r['slug']) ?>" target="_blank" rel="noopener">Gör</a><?php endif; ?>
        <a class="btn btn-ghost btn-s" href="/admin/posts/<?= (int)$r['id'] ?>/edit">Düzenle</a>
        <form method="post" action="/admin/posts/<?= (int)$r['id'] ?>/delete" data-confirm="“<?= e($r['title']) ?>” kalıcı olarak silinsin mi? Sürüm geçmişi ve yorumları da silinir."><?= csrf_field() ?><button class="btn btn-danger btn-s" type="submit">Sil</button></form>
      </div></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="6" class="muted">Kayıt bulunamadı.</td></tr><?php endif; ?>
  </tbody>
</table></div>
<?= View::partial('partials/pager', ['page' => $page, 'pages' => $pages, 'base' => '/admin/posts?' . http_build_query(array_filter(['status' => $status, 'q' => $q])) . '&']) ?>
