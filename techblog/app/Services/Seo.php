<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use App\Core\Database as DB;
use App\Models\Post;
use App\Models\Taxonomy;

/** Sitemap, RSS and schema.org structured data. */
final class Seo
{
    public static function jsonLd(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?: '{}';
    }

    /** @return array<string,mixed> */
    public static function organization(): array
    {
        return [
            '@type' => 'Organization',
            'name' => (string)setting('site_name', 'TechBlog'),
            'url' => url('/'),
            'logo' => ['@type' => 'ImageObject', 'url' => url((string)setting('site_logo', '/assets/img/logo.svg'))],
        ];
    }

    /** @return array<string,mixed> */
    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org', '@type' => 'WebSite',
            'name' => (string)setting('site_name', 'TechBlog'), 'url' => url('/'), 'inLanguage' => 'tr-TR',
            'description' => (string)setting('site_description', ''),
            'potentialAction' => ['@type' => 'SearchAction', 'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/search') . '?q={search_term_string}'], 'query-input' => 'required name=search_term_string'],
        ];
    }

    /** @return array<string,mixed> */
    public static function article(array $post, array $rendered, string $canonical, string $image): array
    {
        return [
            '@context' => 'https://schema.org', '@type' => 'Article',
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
            'headline' => mb_substr((string)$post['title'], 0, 110),
            'description' => (string)($post['meta_description'] ?: $post['excerpt']),
            'image' => [$image],
            'datePublished' => date('c', (int)strtotime((string)$post['published_at'])),
            'dateModified' => date('c', (int)strtotime((string)$post['updated_at'])),
            'author' => ['@type' => $post['author_name'] ? 'Person' : 'Organization', 'name' => (string)($post['author_name'] ?: setting('site_name', 'TechBlog')), 'url' => url('/about')],
            'publisher' => self::organization(),
            'articleSection' => (string)($post['category_name'] ?? ''),
            'wordCount' => (int)$rendered['words'],
            'inLanguage' => 'tr-TR',
        ];
    }

    /** @param list<array{0:string,1:string}> $items [name, absolute url] */
    public static function breadcrumbs(array $items): array
    {
        $list = [];
        foreach ($items as $i => [$name, $u]) {
            $list[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => $u];
        }
        return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $list];
    }

    /** @param list<array{q:string,a:string}> $faq */
    public static function faq(array $faq): array
    {
        return [
            '@context' => 'https://schema.org', '@type' => 'FAQPage',
            'mainEntity' => array_map(static fn($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]], $faq),
        ];
    }

    public static function sitemap(bool $rebuild = false): string
    {
        if ($rebuild) {
            Cache::forget('sitemap_xml');
        }
        return Cache::remember('sitemap_xml', 3600, static function (): string {
            $x = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $add = static function (string $path, ?string $lastmod, string $freq, string $prio) use (&$x): void {
                $x .= '  <url><loc>' . htmlspecialchars(url($path), ENT_XML1) . '</loc>'
                    . ($lastmod ? '<lastmod>' . date('Y-m-d', (int)strtotime($lastmod)) . '</lastmod>' : '')
                    . '<changefreq>' . $freq . '</changefreq><priority>' . $prio . '</priority></url>' . "\n";
            };
            $newest = DB::val('SELECT MAX(updated_at) FROM posts p WHERE ' . Post::LIVE);
            $add('/', $newest ? (string)$newest : null, 'daily', '1.0');
            $add('/archive', $newest ? (string)$newest : null, 'daily', '0.6');
            foreach (DB::all('SELECT slug, updated_at FROM posts p WHERE ' . Post::LIVE . ' ORDER BY published_at DESC') as $p) {
                $add('/post/' . $p['slug'], (string)$p['updated_at'], 'monthly', '0.9');
            }
            foreach (Taxonomy::all('category') as $c) {
                if ((int)$c['post_count'] > 0) {
                    $add('/category/' . $c['slug'], null, 'weekly', '0.6');
                }
            }
            foreach (Taxonomy::all('tag') as $t) {
                if ((int)$t['post_count'] > 0) {
                    $add('/tag/' . $t['slug'], null, 'weekly', '0.4');
                }
            }
            foreach (DB::all("SELECT DISTINCT series FROM posts p WHERE series <> '' AND " . Post::LIVE) as $s) {
                $add('/series/' . slugify((string)$s['series']), null, 'weekly', '0.6');
            }
            foreach (['/about', '/contact', '/privacy'] as $p) {
                $add($p, null, 'yearly', '0.3');
            }
            return $x . '</urlset>' . "\n";
        });
    }

    public static function rss(): string
    {
        return Cache::remember('rss_xml', 900, static function (): string {
            $esc = static fn(string $s): string => htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $name = (string)setting('site_name', 'TechBlog');
            $x = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
                . '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom"><channel>'
                . '<title>' . $esc($name) . '</title><link>' . $esc(url('/')) . '</link>'
                . '<description>' . $esc((string)setting('site_description', '')) . '</description><language>tr-TR</language>'
                . '<atom:link href="' . $esc(url('/feed.xml')) . '" rel="self" type="application/rss+xml"/>';
            $posts = Post::latest(30);
            if ($posts) {
                $x .= '<lastBuildDate>' . date(DATE_RSS, (int)strtotime((string)$posts[0]['published_at'])) . '</lastBuildDate>';
            }
            foreach ($posts as $p) {
                $link = url('/post/' . $p['slug']);
                $x .= "\n<item><title>" . $esc((string)$p['title']) . '</title><link>' . $esc($link) . '</link><guid isPermaLink="true">' . $esc($link) . '</guid>'
                    . '<pubDate>' . date(DATE_RSS, (int)strtotime((string)$p['published_at'])) . '</pubDate>'
                    . ($p['category_name'] ? '<category>' . $esc((string)$p['category_name']) . '</category>' : '')
                    . '<description>' . $esc((string)$p['excerpt']) . '</description></item>';
            }
            return $x . "\n</channel></rss>\n";
        });
    }
}
