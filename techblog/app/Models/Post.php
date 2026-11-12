<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Cache;
use App\Core\Database as DB;
use App\Services\AiData;
use App\Services\BlockRenderer;

final class Post
{
    /** Published and already due (scheduled posts appear once published_at has passed). */
    public const LIVE = "p.status = 'published' AND p.published_at <= NOW()";

    private const LIST_COLS = 'p.id, p.title, p.slug, p.excerpt, p.cover_image, p.cover_alt, p.published_at, p.updated_at, p.reading_minutes,
        p.series, p.series_order, p.is_featured, p.views, c.name AS category_name, c.slug AS category_slug';

    /** @return list<array<string,mixed>> */
    public static function latest(int $limit, int $offset = 0, string $where = '', array $params = []): array
    {
        $sql = 'SELECT ' . self::LIST_COLS . ' FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE ' . self::LIVE
            . ($where !== '' ? ' AND ' . $where : '') . ' ORDER BY p.published_at DESC, p.id DESC LIMIT ? OFFSET ?';
        return DB::all($sql, [...$params, $limit, $offset]);
    }

    public static function countLive(string $where = '', array $params = []): int
    {
        return (int)DB::val('SELECT COUNT(*) FROM posts p WHERE ' . self::LIVE . ($where !== '' ? ' AND ' . $where : ''), $params);
    }

    /** @return list<array<string,mixed>> */
    public static function featured(int $limit): array
    {
        return self::latest($limit, 0, 'p.is_featured = 1');
    }

    /** @return list<array<string,mixed>> */
    public static function mostRead(int $limit): array
    {
        return DB::all('SELECT ' . self::LIST_COLS . ' FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE ' . self::LIVE
            . ' ORDER BY p.views DESC, p.published_at DESC LIMIT ?', [$limit]);
    }

    /** @return list<array<string,mixed>> */
    public static function series(string $series): array
    {
        return DB::all('SELECT ' . self::LIST_COLS . ' FROM posts p LEFT JOIN categories c ON c.id = p.category_id WHERE ' . self::LIVE
            . ' AND p.series = ? ORDER BY p.series_order ASC, p.published_at ASC', [$series]);
    }

    /** @return list<array<string,mixed>> */
    public static function byTag(int $tagId, int $limit, int $offset): array
    {
        return DB::all('SELECT ' . self::LIST_COLS . ' FROM posts p JOIN post_tags pt ON pt.post_id = p.id LEFT JOIN categories c ON c.id = p.category_id
            WHERE ' . self::LIVE . ' AND pt.tag_id = ? ORDER BY p.published_at DESC LIMIT ? OFFSET ?', [$tagId, $limit, $offset]);
    }

    public static function countByTag(int $tagId): int
    {
        return (int)DB::val('SELECT COUNT(*) FROM posts p JOIN post_tags pt ON pt.post_id = p.id WHERE ' . self::LIVE . ' AND pt.tag_id = ?', [$tagId]);
    }

    /** @return array<string,mixed>|null */
    public static function findLiveBySlug(string $slug): ?array
    {
        return DB::one('SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.name AS author_name
            FROM posts p LEFT JOIN categories c ON c.id = p.category_id LEFT JOIN users u ON u.id = p.author_id
            WHERE p.slug = ? AND ' . self::LIVE, [$slug]);
    }

    /** @return array<string,mixed>|null */
    public static function find(int $id): ?array
    {
        return DB::one('SELECT * FROM posts WHERE id = ?', [$id]);
    }

    /** @return list<array<string,mixed>> */
    public static function tags(int $postId): array
    {
        return DB::all('SELECT t.id, t.name, t.slug FROM tags t JOIN post_tags pt ON pt.tag_id = t.id WHERE pt.post_id = ? ORDER BY t.sort_order, t.name', [$postId]);
    }

    /** Related posts: same series first, then shared tags, then same category. @return list<array<string,mixed>> */
    public static function related(array $post, int $limit = 3): array
    {
        return DB::all('SELECT ' . self::LIST_COLS . ',
                ((p.series <> \'\' AND p.series = ?) * 2 + (p.category_id <=> ?) * 3
                 + (SELECT COUNT(*) FROM post_tags a JOIN post_tags b ON a.tag_id = b.tag_id WHERE a.post_id = p.id AND b.post_id = ?) * 2) AS score
            FROM posts p LEFT JOIN categories c ON c.id = p.category_id
            WHERE ' . self::LIVE . ' AND p.id <> ? ORDER BY score DESC, p.published_at DESC LIMIT ?',
            [$post['series'], $post['category_id'], $post['id'], $post['id'], $limit]);
    }

    /** @return array{prev:?array,next:?array} */
    public static function neighbours(array $post): array
    {
        $cols = 'p.title, p.slug';
        return [
            'prev' => DB::one("SELECT $cols FROM posts p WHERE " . self::LIVE . ' AND (p.published_at < ? OR (p.published_at = ? AND p.id < ?)) ORDER BY p.published_at DESC, p.id DESC LIMIT 1',
                [$post['published_at'], $post['published_at'], $post['id']]),
            'next' => DB::one("SELECT $cols FROM posts p WHERE " . self::LIVE . ' AND (p.published_at > ? OR (p.published_at = ? AND p.id > ?)) ORDER BY p.published_at ASC, p.id ASC LIMIT 1',
                [$post['published_at'], $post['published_at'], $post['id']]),
        ];
    }

    /** FULLTEXT search with a LIKE fallback for very short terms. @return list<array<string,mixed>> */
    public static function search(string $q, int $limit = 20): array
    {
        $q = trim(preg_replace('/[^\p{L}\p{N}\s\-+#.]/u', ' ', $q) ?? '');
        if (mb_strlen($q) < 2) {
            return [];
        }
        $words = array_slice(array_filter(preg_split('/\s+/u', $q) ?: [], static fn($w) => mb_strlen($w) >= 2), 0, 6);
        $rows = [];
        $long = array_filter($words, static fn($w) => mb_strlen($w) >= 3);
        if ($long) {
            $bool = implode(' ', array_map(static fn($w) => '+' . preg_replace('/[+\-<>()~*"@#.]/', '', $w) . '*', $long));
            if (trim(str_replace(['+', '*'], '', $bool)) !== '') {
                $rows = DB::all('SELECT ' . self::LIST_COLS . ', MATCH(p.title, p.excerpt, p.content_text) AGAINST (? IN BOOLEAN MODE) AS score
                    FROM posts p LEFT JOIN categories c ON c.id = p.category_id
                    WHERE ' . self::LIVE . ' AND MATCH(p.title, p.excerpt, p.content_text) AGAINST (? IN BOOLEAN MODE)
                    ORDER BY (p.title LIKE ?) DESC, score DESC LIMIT ?', [$bool, $bool, '%' . $q . '%', $limit]);
            }
        }
        if (!$rows) {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $rows = DB::all('SELECT ' . self::LIST_COLS . ' FROM posts p LEFT JOIN categories c ON c.id = p.category_id
                WHERE ' . self::LIVE . ' AND (p.title LIKE ? OR p.excerpt LIKE ? OR p.content_text LIKE ?)
                ORDER BY (p.title LIKE ?) DESC, p.published_at DESC LIMIT ?', [$like, $like, $like, $like, $limit]);
        }
        return $rows;
    }

    /** @return list<array<string,mixed>> decoded Editor.js blocks */
    public static function blocks(array $post): array
    {
        $d = json_decode((string)($post['content_json'] ?? ''), true);
        return is_array($d['blocks'] ?? null) ? $d['blocks'] : [];
    }

    /** Rendered HTML, table of contents and FAQ for a post (cached until the post or the AI data changes). */
    public static function rendered(array $post): array
    {
        $key = 'post_render_' . $post['id'] . '_' . md5($post['updated_at'] . '|' . AiData::updated() . '|' . @filemtime(base_path('data/ai-benchmarks.json')));
        return Cache::remember($key, 86400, static fn() => (new BlockRenderer())->render(self::blocks($post)));
    }

    public static function uniqueSlug(string $wanted, int $ignoreId = 0): string
    {
        $base = slugify($wanted) ?: 'yazi';
        $slug = $base;
        $i = 2;
        while (DB::val('SELECT 1 FROM posts WHERE slug = ? AND id <> ?', [$slug, $ignoreId])) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    /** @param list<int> $tagIds */
    public static function syncTags(int $postId, array $tagIds): void
    {
        DB::run('DELETE FROM post_tags WHERE post_id = ?', [$postId]);
        foreach (array_unique($tagIds) as $tid) {
            DB::run('INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)', [$postId, (int)$tid]);
        }
    }

    /** Remember when the next scheduled post becomes due so the page cache can expire on time. */
    public static function refreshSchedule(): void
    {
        $next = DB::val("SELECT MIN(published_at) FROM posts WHERE status = 'published' AND published_at > NOW()");
        Cache::set('next_scheduled_at', $next ? (int)strtotime((string)$next) : 0);
    }

    public static function addView(int $postId): void
    {
        DB::run('UPDATE posts SET views = views + 1 WHERE id = ?', [$postId]);
        DB::run('INSERT INTO post_views (post_id, view_date, views) VALUES (?, CURDATE(), 1) ON DUPLICATE KEY UPDATE views = views + 1', [$postId]);
    }
}
