<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Audit;
use App\Core\Database as DB;
use App\Core\Request;
use App\Core\Response;

final class CommentsController extends AdminBase
{
    private const STATUSES = ['pending', 'approved', 'spam', 'trash'];

    public function index(array $p): Response
    {
        $status = in_array(Request::str('status'), self::STATUSES, true) ? Request::str('status') : 'pending';
        $page = max(1, Request::int('page', 1));
        $total = (int)DB::val('SELECT COUNT(*) FROM comments WHERE status = ?', [$status]);
        $rows = DB::all('SELECT c.*, u.name AS user_name, u.email AS user_email, p.title AS post_title, p.slug AS post_slug
            FROM comments c JOIN users u ON u.id = c.user_id JOIN posts p ON p.id = c.post_id
            WHERE c.status = ? ORDER BY c.id DESC LIMIT 30 OFFSET ?', [$status, ($page - 1) * 30]);
        $counts = [];
        foreach (DB::all('SELECT status, COUNT(*) AS n FROM comments GROUP BY status') as $r) {
            $counts[$r['status']] = (int)$r['n'];
        }
        return $this->view('comments', 'Yorumlar', ['rows' => $rows, 'status' => $status, 'counts' => $counts, 'page' => $page, 'pages' => (int)ceil($total / 30)]);
    }

    public function bulk(array $p): Response
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', (array)Request::input('ids', [])))));
        $action = Request::str('action', 20);
        $back = '/admin/comments?status=' . (in_array(Request::str('from'), self::STATUSES, true) ? Request::str('from') : 'pending');
        if (!$ids) {
            return $this->done('Önce en az bir yorum seçin.', $back, false);
        }
        $in = DB::placeholders(count($ids));
        if ($action === 'delete') {
            DB::run("DELETE FROM comments WHERE id IN ($in)", $ids);
        } elseif (in_array($action, self::STATUSES, true)) {
            DB::run("UPDATE comments SET status = ? WHERE id IN ($in)", [$action, ...$ids]);
        } else {
            return $this->done('Geçersiz işlem.', $back, false);
        }
        Audit::log('comment.' . $action, 'comment', implode(',', array_slice($ids, 0, 20)), ['count' => count($ids)]);
        return $this->done(count($ids) . ' yorum güncellendi.', $back);
    }

    public function messages(array $p): Response
    {
        $rows = DB::all('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 100');
        DB::run('UPDATE contact_messages SET is_read = 1 WHERE is_read = 0');
        return $this->view('messages', 'İletişim mesajları', ['rows' => $rows]);
    }

    public function messageDelete(array $p): Response
    {
        DB::run('DELETE FROM contact_messages WHERE id = ?', [(int)$p['id']]);
        Audit::log('message.delete', 'contact_message', (int)$p['id']);
        return $this->done('Mesaj silindi.', '/admin/messages');
    }
}
