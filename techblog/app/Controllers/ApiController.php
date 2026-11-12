<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database as DB;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Models\Post;
use App\Services\AiData;
use App\Services\Sanitizer;

final class ApiController
{
    /** Signed-in state for cached pages. Guests get no session and no cookie. */
    public function me(array $p): Response
    {
        $u = Auth::user();
        if (!$u) {
            return Response::json(['user' => null]);
        }
        return Response::json([
            'user' => ['id' => (int)$u['id'], 'name' => $u['name'], 'avatar' => $u['avatar'], 'role' => $u['role'], 'staff' => Auth::is('editor')],
            'csrf' => Csrf::token(),
        ]);
    }

    public function search(array $p): Response
    {
        if (!RateLimiter::hit('search', 60, 60)) {
            return Response::json(['ok' => false, 'error' => 'Çok hızlı arama yapıyorsunuz.'], 429);
        }
        $out = [];
        foreach (Post::search(Request::str('q', 80), 8) as $r) {
            $out[] = ['title' => $r['title'], 'url' => '/post/' . $r['slug'], 'excerpt' => str_limit((string)$r['excerpt'], 110), 'category' => $r['category_name'], 'minutes' => (int)$r['reading_minutes']];
        }
        return Response::json(['ok' => true, 'results' => $out]);
    }

    public function aiData(array $p): Response
    {
        return Response::json(AiData::publicData())->header('Cache-Control', 'public, max-age=300');
    }

    /** View counter beacon. No CSRF token (cached pages have none); protected by origin check and rate limit. */
    public function view(array $p): Response
    {
        $id = Request::int('post_id');
        if ($id > 0 && Request::sameOrigin() && RateLimiter::hit('view|' . $id, 1, 1800)
            && DB::val('SELECT 1 FROM posts p WHERE p.id = ? AND ' . Post::LIVE, [$id])) {
            Post::addView($id);
        }
        return new Response('', 204, ['Cache-Control' => 'no-store']);
    }

    public function comments(array $p): Response
    {
        $postId = Request::int('post_id');
        $rows = DB::all("SELECT c.id, c.parent_id, c.body, c.created_at, c.user_id, u.name, u.avatar, u.role
            FROM comments c JOIN users u ON u.id = c.user_id
            WHERE c.post_id = ? AND c.status = 'approved' ORDER BY c.created_at ASC LIMIT 500", [$postId]);
        $me = Auth::id();
        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id' => (int)$r['id'], 'parent_id' => $r['parent_id'] ? (int)$r['parent_id'] : null, 'body' => $r['body'],
                'date' => tr_date((string)$r['created_at'], true), 'name' => $r['name'], 'avatar' => $r['avatar'],
                'staff' => $r['role'] !== 'user', 'mine' => $me !== null && (int)$r['user_id'] === $me,
            ];
        }
        return Response::json(['ok' => true, 'comments' => $out]);
    }

    public function commentCreate(array $p): Response
    {
        $uid = (int)Auth::id();
        $postId = Request::int('post_id');
        $parentId = Request::int('parent_id');
        $body = Sanitizer::text((string)Request::input('body', ''), 2000);
        if (mb_strlen($body) < 3) {
            return Response::json(['ok' => false, 'error' => 'Yorum en az 3 karakter olmalı.'], 422);
        }
        if (!DB::val('SELECT 1 FROM posts p WHERE p.id = ? AND ' . Post::LIVE, [$postId])) {
            return Response::json(['ok' => false, 'error' => 'Yazı bulunamadı.'], 404);
        }
        if ($parentId > 0 && !DB::val("SELECT 1 FROM comments WHERE id = ? AND post_id = ? AND parent_id IS NULL AND status = 'approved'", [$parentId, $postId])) {
            return Response::json(['ok' => false, 'error' => 'Yanıtlanan yorum bulunamadı.'], 422);
        }
        if (!RateLimiter::hit('comment', 5, 600, $uid)) {
            return Response::json(['ok' => false, 'error' => 'Çok sık yorum gönderiyorsunuz. Birkaç dakika sonra tekrar deneyin.'], 429);
        }
        $links = preg_match_all('#https?://#i', $body);
        $status = Auth::is('editor') ? 'approved' : ($links > 2 ? 'spam' : (setting('comments_moderation', true) ? 'pending' : 'approved'));
        $id = DB::insert('comments', [
            'post_id' => $postId, 'user_id' => $uid, 'parent_id' => $parentId > 0 ? $parentId : null,
            'body' => $body, 'status' => $status, 'ip_hash' => Request::ipHash(), 'created_at' => now(),
        ]);
        return Response::json([
            'ok' => true, 'id' => $id, 'status' => $status,
            'message' => $status === 'approved' ? 'Yorumunuz yayımlandı.' : 'Yorumunuz alındı; onaylandıktan sonra görünecek.',
        ]);
    }

    /** Users may delete their own comments. */
    public function commentDelete(array $p): Response
    {
        $n = DB::run("UPDATE comments SET status = 'trash' WHERE id = ? AND user_id = ?", [(int)$p['id'], (int)Auth::id()])->rowCount();
        return Response::json(['ok' => $n > 0], $n > 0 ? 200 : 404);
    }
}
