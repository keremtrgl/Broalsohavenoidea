<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Response;
use App\Core\View;
use App\Models\Post;
use App\Services\AiData;
use App\Services\Seo;

final class PostController
{
    public function show(array $p): Response
    {
        $post = Post::findLiveBySlug($p['slug']);
        if (!$post) {
            return App::notFound();
        }
        $r = Post::rendered($post);
        $canonical = $post['canonical_url'] ?: url('/post/' . $post['slug']);
        $og = $post['og_image'] ?: preg_replace('/\.svg$/', '.png', (string)$post['cover_image']);
        $ogAbs = $og ? url((string)$og) : url('/assets/img/og-default.png');
        $crumbs = [['Ana sayfa', url('/')]];
        if ($post['category_slug']) {
            $crumbs[] = [(string)$post['category_name'], url('/category/' . $post['category_slug'])];
        }
        $crumbs[] = [(string)$post['title'], url('/post/' . $post['slug'])];

        $updated = (string)$post['updated_at'];
        if ($r['has_ai'] && AiData::updated() !== '' && strtotime(AiData::updated()) > strtotime($updated)) {
            $updated = AiData::updated();
        }
        $jsonld = [Seo::article(['updated_at' => $updated] + $post, $r, $canonical, $ogAbs), Seo::breadcrumbs($crumbs)];
        if ($r['faq']) {
            $jsonld[] = Seo::faq($r['faq']);
        }

        return Response::html(View::render('public/post', [
            'post' => $post, 'r' => $r, 'tags' => Post::tags((int)$post['id']), 'related' => Post::related($post, 3),
            'nav' => Post::neighbours($post), 'crumbs' => $crumbs, 'updated' => $updated,
            'seriesPosts' => $post['series'] !== '' ? Post::series((string)$post['series']) : [],
            'lcpImage' => (string)$post['cover_image'],
            'seo' => [
                'title' => $post['meta_title'] ?: $post['title'],
                'description' => $post['meta_description'] ?: $post['excerpt'],
                'canonical' => $canonical, 'og_image' => $ogAbs, 'og_type' => 'article', 'jsonld' => $jsonld,
                'published' => (string)$post['published_at'], 'modified' => $updated,
            ],
        ]))->cache();
    }
}
