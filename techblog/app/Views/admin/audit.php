<?php /** @var array $rows @var int $page @var int $pages */
use App\Core\View;
?>
<p class="muted">Yönetim panelinde yapılan her değişiklik burada kayıt altına alınır: kim, neyi, ne zaman.</p>
<div class="table-wrap"><table>
  <thead><tr><th scope="col">Zaman</th><th scope="col">Kullanıcı</th><th scope="col">İşlem</th><th scope="col">Kayıt</th><th scope="col">Ayrıntı</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr><td><?= e(tr_date($r['created_at'], true)) ?></td><td><?= e($r['user_name'] ?? 'silinmiş kullanıcı') ?><br><span class="muted"><?= e($r['user_email'] ?? '') ?></span></td>
      <td><span class="mono"><?= e($r['action']) ?></span></td><td><?= e(trim($r['entity'] . ' ' . ($r['entity_id'] ?? ''))) ?></td><td class="mono muted"><?= e(str_limit((string)($r['details'] ?? ''), 140)) ?></td></tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="5" class="muted">Henüz kayıt yok.</td></tr><?php endif; ?>
  </tbody>
</table></div>
<?= View::partial('partials/pager', ['page' => $page, 'pages' => $pages, 'base' => '/admin/audit?']) ?>
