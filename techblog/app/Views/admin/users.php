<?php /** @var array $rows @var string $q @var int $page @var int $pages @var int $total */
use App\Core\Auth;
use App\Core\View;
$roles = ['user' => 'Kullanıcı', 'editor' => 'Editör', 'admin' => 'Yönetici'];
?>
<div class="toolbar">
  <p class="muted grow">Firebase ile giriş yapmış <?= num($total) ?> kullanıcı. Editör ve yönetici yetkileri yalnızca e-postası doğrulanmış hesaplarda etkin olur.</p>
  <form method="get" action="/admin/users" role="search" class="toolbar"><label class="sr-only" for="q">Ad veya e-posta ara</label><input type="search" id="q" name="q" value="<?= e($q) ?>" placeholder="Ad veya e-posta…" data-search-focus><button class="btn btn-ghost btn-s" type="submit">Ara</button></form>
</div>
<div class="table-wrap"><table>
  <thead><tr><th scope="col">Kullanıcı</th><th scope="col">E-posta</th><th scope="col">Son giriş</th><th scope="col">Rol</th><th scope="col">Durum</th><th scope="col"><span class="sr-only">Kaydet</span></th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): $fid = 'uf' . (int)$r['id']; ?>
    <tr>
      <th scope="row"><?= e($r['name']) ?><?= (int)$r['id'] === Auth::id() ? ' <span class="pill">siz</span>' : '' ?></th>
      <td><?= e($r['email']) ?> <?= (int)$r['email_verified'] ? '<span class="pill pill-ok">doğrulanmış</span>' : '<span class="pill pill-warn">doğrulanmamış</span>' ?></td>
      <td><?= $r['last_login_at'] ? e(tr_date($r['last_login_at'], true)) : '—' ?></td>
      <td><label class="sr-only" for="role-<?= (int)$r['id'] ?>">Rol</label><select id="role-<?= (int)$r['id'] ?>" name="role" form="<?= $fid ?>"><?php foreach ($roles as $k => $label): ?><option value="<?= $k ?>"<?= $r['role'] === $k ? ' selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></td>
      <td><label class="check"><input type="checkbox" name="is_banned" value="1" form="<?= $fid ?>"<?= (int)$r['is_banned'] ? ' checked' : '' ?>> Engelli</label></td>
      <td><form method="post" action="/admin/users/<?= (int)$r['id'] ?>/update" id="<?= $fid ?>"><?= csrf_field() ?><button class="btn btn-ghost btn-s" type="submit">Kaydet</button></form></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="6" class="muted">Kullanıcı bulunamadı.</td></tr><?php endif; ?>
  </tbody>
</table></div>
<?= View::partial('partials/pager', ['page' => $page, 'pages' => $pages, 'base' => '/admin/users?' . ($q !== '' ? 'q=' . rawurlencode($q) . '&' : '')]) ?>
