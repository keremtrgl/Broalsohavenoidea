<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Audit;
use App\Core\Auth;
use App\Core\Database as DB;
use App\Core\Request;
use App\Core\Response;

final class UsersController extends AdminBase
{
    public function index(array $p): Response
    {
        $q = Request::str('q', 80);
        $page = max(1, Request::int('page', 1));
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where = '(name LIKE ? OR email LIKE ?)';
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $params = [$like, $like];
        }
        $total = (int)DB::val("SELECT COUNT(*) FROM users WHERE $where", $params);
        $rows = DB::all("SELECT * FROM users WHERE $where ORDER BY FIELD(role, 'admin', 'editor', 'user'), id DESC LIMIT 30 OFFSET ?", [...$params, ($page - 1) * 30]);
        return $this->view('users', 'Kullanıcılar', ['rows' => $rows, 'q' => $q, 'page' => $page, 'pages' => (int)ceil($total / 30), 'total' => $total]);
    }

    public function update(array $p): Response
    {
        $user = DB::one('SELECT * FROM users WHERE id = ?', [(int)$p['id']]);
        if (!$user) {
            return App::notFound();
        }
        $role = Request::str('role', 10);
        $ban = Request::bool('is_banned') ? 1 : 0;
        if (!in_array($role, Auth::ROLES, true)) {
            return $this->done('Geçersiz rol.', '/admin/users', false);
        }
        if ((int)$user['id'] === Auth::id() && ($role !== 'admin' || $ban === 1)) {
            return $this->done('Kendi yönetici yetkinizi kaldıramaz veya kendinizi engelleyemezsiniz.', '/admin/users', false);
        }
        if ($user['role'] === 'admin' && ($role !== 'admin' || $ban === 1)
            && (int)DB::val("SELECT COUNT(*) FROM users WHERE role = 'admin' AND is_banned = 0") <= 1) {
            return $this->done('Sitede en az bir etkin yönetici kalmalıdır.', '/admin/users', false);
        }
        DB::update('users', ['role' => $role, 'is_banned' => $ban], (int)$user['id']);
        Audit::log('user.update', 'user', (int)$user['id'], ['email' => $user['email'], 'role' => $role, 'banned' => (bool)$ban]);
        return $this->done('Kullanıcı güncellendi.', '/admin/users');
    }
}
