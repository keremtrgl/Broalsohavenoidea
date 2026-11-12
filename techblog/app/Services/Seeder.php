<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use App\Core\Database as DB;
use App\Core\PageCache;
use App\Core\Settings;
use App\Models\Post;
use App\Models\Taxonomy;

/** Loads default settings, categories and the seed articles. Safe to run more than once. */
final class Seeder
{
    /** @return array{settings:int,categories:int,posts:int,skipped:int} */
    public static function run(array $overrides = []): array
    {
        $out = ['settings' => 0, 'categories' => 0, 'posts' => 0, 'skipped' => 0];

        $defaults = require base_path('database/seeds/settings.php');
        $existing = array_column(DB::all('SELECT `key` FROM settings'), 'key');
        $new = array_diff_key($overrides + $defaults, array_flip($existing));
        if ($new) {
            Settings::setMany($new);
            $out['settings'] = count($new);
        }

        $catIds = [];
        foreach (require base_path('database/seeds/categories.php') as $i => $c) {
            $row = Taxonomy::bySlug('category', $c['slug']);
            if (!$row) {
                $catIds[$c['slug']] = DB::insert('categories', $c + ['sort_order' => $i + 1]);
                $out['categories']++;
            } else {
                $catIds[$c['slug']] = (int)$row['id'];
            }
        }

        $parser = new ArticleParser();
        $files = glob(base_path('database/seeds/articles/*.md')) ?: [];
        sort($files);
        foreach ($files as $file) {
            $doc = $parser->parse((string)file_get_contents($file));
            $meta = $doc['meta'];
            $slug = $meta['slug'] ?? slugify($meta['title'] ?? basename($file, '.md'));
            if (DB::val('SELECT 1 FROM posts WHERE slug = ?', [$slug])) {
                $out['skipped']++;
                continue;
            }
            $r = (new BlockRenderer())->render($doc['blocks']);
            $date = date('Y-m-d H:i:s', strtotime($meta['date'] ?? 'now') ?: time());
            $id = DB::insert('posts', [
                'author_id' => null,
                'category_id' => $catIds[$meta['category'] ?? ''] ?? null,
                'title' => $meta['title'] ?? $slug,
                'slug' => $slug,
                'excerpt' => $meta['excerpt'] ?? str_limit($r['text'], 220),
                'content_json' => json_encode(['time' => time() * 1000, 'blocks' => $doc['blocks'], 'version' => '2'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'content_text' => $r['text'],
                'cover_image' => $meta['cover'] ?? '',
                'cover_alt' => $meta['cover_alt'] ?? '',
                'status' => 'published',
                'published_at' => $date,
                'created_at' => $date,
                'updated_at' => date('Y-m-d H:i:s', strtotime($meta['updated'] ?? $meta['date'] ?? 'now') ?: time()),
                'reading_minutes' => max(1, (int)ceil($r['words'] / 200)),
                'word_count' => $r['words'],
                'meta_title' => $meta['meta_title'] ?? '',
                'meta_description' => $meta['description'] ?? '',
                'is_featured' => (int)($meta['featured'] ?? 0),
                'series' => $meta['series'] ?? '',
                'series_order' => (int)($meta['series_order'] ?? 0),
                'show_updated' => (int)($meta['show_updated'] ?? 0),
            ]);
            $tagIds = [];
            foreach (array_filter(array_map('trim', explode(',', $meta['tags'] ?? ''))) as $tag) {
                $tagIds[] = Taxonomy::tagId($tag);
            }
            Post::syncTags($id, $tagIds);
            $out['posts']++;
        }
        Post::refreshSchedule();
        PageCache::flush();
        Cache::forget('sitemap_xml');
        Cache::forget('rss_xml');
        return $out;
    }
}
