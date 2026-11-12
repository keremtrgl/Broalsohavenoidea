<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database as DB;
use App\Core\Response;

final class DashboardController extends AdminBase
{
    public function index(array $p): Response
    {
        $byDay = [];
        foreach (DB::all('SELECT view_date, SUM(views) AS v FROM post_views WHERE view_date >= DATE_SUB(CURDATE(), INTERVAL 29 DAY) GROUP BY view_date') as $r) {
            $byDay[(string)$r['view_date']] = (int)$r['v'];
        }
        $labels = $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i day"));
            $labels[] = date('d.m', strtotime($d));
            $values[] = $byDay[$d] ?? 0;
        }
        return $this->view('dashboard', 'Gösterge paneli', [
            'stats' => [
                'published' => (int)DB::val("SELECT COUNT(*) FROM posts WHERE status = 'published' AND published_at <= NOW()"),
                'scheduled' => (int)DB::val("SELECT COUNT(*) FROM posts WHERE status = 'published' AND published_at > NOW()"),
                'drafts' => (int)DB::val("SELECT COUNT(*) FROM posts WHERE status = 'draft'"),
                'comments' => (int)DB::val("SELECT COUNT(*) FROM comments WHERE status = 'approved'"),
                'pending' => (int)DB::val("SELECT COUNT(*) FROM comments WHERE status = 'pending'"),
                'users' => (int)DB::val('SELECT COUNT(*) FROM users'),
                'views30' => array_sum($values),
            ],
            'chart' => ['type' => 'line', 'labels' => $labels, 'datasets' => [['label' => 'Günlük görüntülenme', 'data' => $values]], 'unit' => '', 'xTitle' => '', 'yTitle' => '', 'horizontal' => false, 'stacked' => false, 'log' => false],
            'top' => DB::all('SELECT id, title, slug, views FROM posts ORDER BY views DESC, id ASC LIMIT 8'),
            'activity' => DB::all('SELECT a.*, u.name AS user_name FROM audit_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT 12'),
        ]);
    }
}
