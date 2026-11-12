<?php /** @var array $rows @var string $status @var array $counts @var int $page @var int $pages */
use App\Core\View;
$tabs = ['pending' => 'Onay bekleyen', 'approved' => 'Onaylı', 'spam' => 'Spam', 'trash' => 'Çöp'];
?>
<div class="toolbar"><div class="chips">
  <?php foreach ($tabs as $k => $label): ?><a href="/admin/comments?status=<?= $k ?>"<?= $status === $k ? ' aria-current="page"' : '' ?>><?= e($label) ?> <span class="muted"><?= (int)($counts[$k] ?? 0) ?></span></a><?php endforeach; ?>
</div></div>
<form method="post" action="/admin/comments/bulk" id="bulk-form">
  <?= csrf_field() ?>
  <input type="hidden" name="from" value="<?= e($status) ?>">
  <div class="toolbar">
    <label class="check"><input type="checkbox" data-check-all> Tümünü seç</label>
    <label class="sr-only" for="bulk-action">Toplu işlem</label>
    <select id="bulk-action" name="action" required>
      <option value="">Toplu işlem seçin…</option>
      <option value="approved">Onayla</option><option value="pending">Beklemeye al</option><option value="spam">Spam olarak işaretle</option><option value="trash">Çöpe taşı</option><option value="delete">Kalıcı olarak sil</option>
    </select>
    <button class="btn btn-s" type="submit">Uygula</button>
  </div>
  <div class="table-wrap"><table>
    <thead><tr><th scope="col"><span class="sr-only">Seç</span></th><th scope="col">Yorum</th><th scope="col">Yazan</th><th scope="col">Yazı</th><th scope="col">Tarih</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>" aria-label="Yorumu seç: <?= e(str_limit((string)$r['body'], 40)) ?>"></td>
        <td><?= nl2br(e($r['body'])) ?></td>
        <td><?= e($r['user_name']) ?><br><span class="muted"><?= e($r['user_email']) ?></span></td>
        <td><a href="/post/<?= e($r['post_slug']) ?>#comments" target="_blank" rel="noopener"><?= e(str_limit((string)$r['post_title'], 50)) ?></a></td>
        <td><?= e(tr_date($r['created_at'], true)) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="5" class="muted">Bu listede yorum yok.</td></tr><?php endif; ?>
    </tbody>
  </table></div>
</form>
<?= View::partial('partials/pager', ['page' => $page, 'pages' => $pages, 'base' => '/admin/comments?status=' . $status . '&']) ?>
