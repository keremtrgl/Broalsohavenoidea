<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Services\Seo;

final class FeedController
{
    public function sitemap(array $p): Response
    {
        return Response::xml(Seo::sitemap());
    }

    public function rss(array $p): Response
    {
        return Response::xml(Seo::rss(), 'application/rss+xml');
    }

    public function robots(array $p): Response
    {
        $body = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /api/\nDisallow: /login\nDisallow: /search\n\nSitemap: " . url('/sitemap.xml') . "\n";
        return new Response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8', 'Cache-Control' => 'public, max-age=3600']);
    }
}
