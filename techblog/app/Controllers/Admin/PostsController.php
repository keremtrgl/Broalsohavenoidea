<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Audit;
use App\Core\Auth;
use App\Core\Database as DB;
use App\Core\Request;
use App\Core\Response;
use App\Models\Post;
use App\Models\Taxonomy;
use App\Services\BlockRenderer;
use App\Services\Sanitizer;

final class PostsController extends AdminBase
{
    private const BLOCK_TYPES = ['header', 'paragraph', 'list', 'code', 'table', 'quote', 'callout', 'image', 'gallery', 'svg', 'chart', 'faq', 'ai'];
    private const MAX_REVISIONS = 30;

    public function index(array $p): Response
    {
        $status = Request::str('status', 20);
        $q = Request::str('q', 80);
        $where = '1=1';
        $params = [];
        if ($status === 'draft') {
            $where .= " AND p.status = 'draft'";
        } elseif ($status === 'published') {
            $where .= " AND p.status = 'published' AND p.published_at <= NOW()";
        } elseif ($status === 'scheduled') {
            $where .= " AND p.status = 'published' AND p.published_at > NOW()";
        }
        if ($q !== '') {
            $where .= ' AND p.title LIKE ?';
            $params[] = '%' . addcslashes($q, '%_\\') . '%';
        }
        $page = max(1, Request::int('page', 1));
        $total = (int)DB::val("SELECT COUNT(*) FROM posts p WHERE $where", $params);
        $rows = DB::all("SELECT p.id, p.title, p.slug, p.status, p.published_at, p.updated_at, p.views, p.is_featured, c.name AS category_name
            FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE $where ORDER BY p.updated_at DESC LIMIT 20 OFFSET ?", [...$params, ($page - 1) * 20]);
        return $this->view('posts/index', 'Yazılar', ['rows' => $rows, 'status' => $status, 'q' => $q, 'page' => $page, 'pages' => (int)ceil($total / 20), 'total' => $total]);
    }

    public function create(array $p): Response
    {
        return $this->form(null);
    }

    public function edit(array $p): Response
    {
        $post = Post::find((int)$p['id']);
        return $post ? $this->form($post) : App::notFound();
    }

    private function form(?array $post): Response
    {
        return $this->view('posts/form', $post ? 'Yazıyı düzenle' : 'Yeni yazı', [
            'post' => $post,
            'categories' => Taxonomy::all('category'),
            'allTags' => array_column(Taxonomy::all('tag'), 'name'),
            'tags' => $post ? array_column(Post::tags((int)$post['id']), 'name') : [],
            'seriesList' => array_column(DB::all("SELECT DISTINCT series FROM posts WHERE series <> '' ORDER BY series"), 'series'),
            'revCount' => $post ? (int)DB::val('SELECT COUNT(*) FROM post_revisions WHERE post_id = ?', [(int)$post['id']]) : 0,
        ]);
    }

    /** Keep only known block types and a sane structure; all values are sanitized again at render time. */
    private function normalizeBlocks(string $json): ?array
    {
        if (strlen($json) > 3 * 1024 * 1024) {
            return null;
        }
        $d = json_decode($json, true);
        if (!is_array($d) || !is_array($d['blocks'] ?? null)) {
            return null;
        }
        $blocks = [];
        foreach ($d['blocks'] as $b) {
            if (is_array($b) && in_array($b['type'] ?? '', self::BLOCK_TYPES, true) && is_array($b['data'] ?? null)) {
                if ($b['type'] === 'svg') {
                    $b['data']['code'] = Sanitizer::svg((string)($b['data']['code'] ?? ''));
                }
                $blocks[] = ['id' => preg_replace('/[^A-Za-z0-9_-]/', '', (string)($b['id'] ?? '')) ?: bin2hex(random_bytes(5)), 'type' => $b['type'], 'data' => $b['data']];
            }
        }
        return ['time' => (int)(microtime(true) * 1000), 'blocks' => $blocks, 'version' => '2'];
    }

    public function preview(array $p): Response
    {
        $doc = $this->normalizeBlocks((string)Request::input('content_json', ''));
        if ($doc === null) {
            return Response::json(['ok' => false, 'error' => 'İçerik okunamadı.'], 422);
        }
        $r = (new BlockRenderer())->render($doc['blocks']);
        return Response::json(['ok' => true, 'html' => $r['html'], 'words' => $r['words'], 'minutes' => max(1, (int)ceil($r['words'] / 200))]);
    }

    public function save(array $p): Response
    {
        $id = Request::int('id');
        $existing = $id > 0 ? Post::find($id) : null;
        if ($id > 0 && !$existing) {
            return App::notFound();
        }
        $back = $existing ? "/admin/posts/$id/edit" : '/admin/posts/new';

        $title = Sanitizer::text(Request::str('title', 200));
        if (mb_strlen($title) < 3) {
            return $this->done('Başlık en az 3 karakter olmalı.', $back, false);
        }
        $doc = $this->normalizeBlocks((string)Request::input('content_json', ''));
        if ($doc === null) {
            return $this->done('İçerik okunamadı veya çok büyük (en fazla 3 MB).', $back, false);
        }
        $status = Request::str('status') === 'published' ? 'published' : 'draft';
        $publishedAt = $existing['published_at'] ?? null;
        $when = Request::str('published_at', 30);
        if ($when !== '' && ($ts = strtotime($when)) !== false) {
            $publishedAt = date('Y-m-d H:i:s', $ts);
        }
        if ($status === 'published' && !$publishedAt) {
            $publishedAt = now();
        }
        $canonical = Request::str('canonical_url', 300);
        if ($canonical !== '' && Sanitizer::url($canonical) === '') {
            return $this->done('Canonical URL geçerli bir http(s) adresi olmalı.', $back, false);
        }
        $cover = Sanitizer::localPath(Request::str('cover_image', 300));
        $coverAlt = Sanitizer::text(Request::str('cover_alt', 300));
        if ($cover !== '' && $coverAlt === '') {
            return $this->done('Kapak görseli için alternatif metin zorunludur.', $back, false);
        }
        $catId = Request::int('category_id');
        $r = (new BlockRenderer())->render($doc['blocks']);
        $excerpt = Sanitizer::text(Request::str('excerpt', 400)) ?: str_limit($r['text'], 220);

        $data = [
            'category_id' => $catId > 0 && Taxonomy::find('category', $catId) ? $catId : null,
            'title' => $title,
            'slug' => Post::uniqueSlug(Request::str('slug', 160) ?: $title, $id),
            'excerpt' => $excerpt,
            'content_json' => json_encode($doc, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'content_text' => $r['text'],
            'cover_image' => $cover,
            'cover_alt' => $coverAlt,
            'status' => $status,
            'published_at' => $publishedAt,
            'updated_at' => now(),
            'reading_minutes' => max(1, (int)ceil($r['words'] / 200)),
            'word_count' => $r['words'],
            'meta_title' => Sanitizer::text(Request::str('meta_title', 200)),
            'meta_description' => Sanitizer::text(Request::str('meta_description', 320)),
            'og_image' => Sanitizer::localPath(Request::str('og_image', 300)),
            'canonical_url' => $canonical,
            'is_featured' => Request::bool('is_featured') ? 1 : 0,
            'series' => Sanitizer::text(Request::str('series', 100)),
            'series_order' => Request::int('series_order'),
            'show_updated' => Request::bool('show_updated') ? 1 : 0,
        ];

        if ($existing) {
            if ($existing['content_json'] !== $data['content_json'] || $existing['title'] !== $title) {
                DB::insert('post_revisions', ['post_id' => $id, 'user_id' => Auth::id(), 'title' => $existing['title'], 'content_json' => $existing['content_json'], 'created_at' => now()]);
                $old = DB::all('SELECT id FROM post_revisions WHERE post_id = ? ORDER BY id DESC LIMIT 1000 OFFSET ?', [$id, self::MAX_REVISIONS]);
                foreach ($old as $o) {
                    DB::run('DELETE FROM post_revisions WHERE id = ?', [(int)$o['id']]);
                }
            }
            DB::update('posts', $data, $id);
        } else {
            $id = DB::insert('posts', $data + ['author_id' => Auth::id(), 'created_at' => now()]);
        }

        $tagIds = [];
        foreach (array_slice(array_filter(array_map('trim', explode(',', Request::str('tags', 600)))), 0, 15) as $name) {
            $tagIds[] = Taxonomy::tagId(Sanitizer::text($name, 100));
        }
        Post::syncTags($id, $tagIds);
        Post::refreshSchedule();
        $this->contentChanged();
        Audit::log($existing ? 'post.update' : 'post.create', 'post', $id, ['title' => $title, 'status' => $status]);

        $scheduled = $status === 'published' && strtotime((string)$publishedAt) > time();
        $msg = $status === 'draft' ? 'Taslak kaydedildi.' : ($scheduled ? 'Yazı ' . tr_date($publishedAt, true) . ' için zamanlandı.' : 'Yazı yayımlandı.');
        return $this->done($msg, "/admin/posts/$id/edit", true, ['id' => $id, 'slug' => $data['slug'], 'redirect' => $existing ? null : "/admin/posts/$id/edit"]);
    }

    public function delete(array $p): Response
    {
        $post = Post::find((int)$p['id']);
        if (!$post) {
            return App::notFound();
        }
        DB::run('DELETE FROM posts WHERE id = ?', [(int)$post['id']]);
        Post::refreshSchedule();
        $this->contentChanged();
        Audit::log('post.delete', 'post', (int)$post['id'], ['title' => $post['title']]);
        return $this->done('Yazı silindi.', '/admin/posts');
    }

    public function revisions(array $p): Response
    {
        $post = Post::find((int)$p['id']);
        if (!$post) {
            return App::notFound();
        }
        $revs = DB::all('SELECT r.id, r.title, r.created_at, CHAR_LENGTH(r.content_json) AS size, u.name AS user_name
            FROM post_revisions r LEFT JOIN users u ON u.id = r.user_id WHERE r.post_id = ? ORDER BY r.id DESC', [(int)$post['id']]);
        return $this->view('posts/revisions', 'Sürüm geçmişi', ['post' => $post, 'revs' => $revs]);
    }

    public function restore(array $p): Response
    {
        $post = Post::find((int)$p['id']);
        $rev = DB::one('SELECT * FROM post_revisions WHERE id = ? AND post_id = ?', [(int)$p['rev'], (int)$p['id']]);
        if (!$post || !$rev) {
            return App::notFound();
        }
        $doc = json_decode((string)$rev['content_json'], true);
        $r = (new BlockRenderer())->render(is_array($doc['blocks'] ?? null) ? $doc['blocks'] : []);
        DB::insert('post_revisions', ['post_id' => (int)$post['id'], 'user_id' => Auth::id(), 'title' => $post['title'], 'content_json' => $post['content_json'], 'created_at' => now()]);
        DB::update('posts', [
            'title' => $rev['title'], 'content_json' => $rev['content_json'], 'content_text' => $r['text'], 'word_count' => $r['words'],
            'reading_minutes' => max(1, (int)ceil($r['words'] / 200)), 'updated_at' => now(),
        ], (int)$post['id']);
        $this->contentChanged();
        Audit::log('post.restore', 'post', (int)$post['id'], ['revision' => (int)$rev['id']]);
        return $this->done('Seçilen sürüm geri yüklendi.', '/admin/posts/' . (int)$post['id'] . '/edit');
    }
}
