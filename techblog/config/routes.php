<?php
declare(strict_types=1);

use App\Controllers\Admin;
use App\Controllers\ApiController;
use App\Controllers\AuthController;
use App\Controllers\FeedController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Core\Router;

/**
 * Route table. Every non-GET route is CSRF-protected automatically unless it carries "nocsrf".
 * Middleware: "auth" (signed in), "role:editor", "role:admin".
 */
return static function (Router $r): void {
    // Public pages
    $r->add('GET', '/', [HomeController::class, 'index']);
    $r->add('GET', '/archive', [HomeController::class, 'archive']);
    $r->add('GET', '/category/{slug}', [HomeController::class, 'category']);
    $r->add('GET', '/tag/{slug}', [HomeController::class, 'tag']);
    $r->add('GET', '/series/{slug}', [HomeController::class, 'series']);
    $r->add('GET', '/search', [HomeController::class, 'search']);
    $r->add('GET', '/about', [HomeController::class, 'about']);
    $r->add('GET', '/privacy', [HomeController::class, 'privacy']);
    $r->add('GET', '/contact', [HomeController::class, 'contact']);
    $r->add('POST', '/contact', [HomeController::class, 'contactSend']);
    $r->add('GET', '/post/{slug}', [PostController::class, 'show']);

    // Feeds
    $r->add('GET', '/sitemap.xml', [FeedController::class, 'sitemap']);
    $r->add('GET', '/robots.txt', [FeedController::class, 'robots']);
    $r->add('GET', '/feed.xml', [FeedController::class, 'rss']);

    // Authentication
    $r->add('GET', '/login', [AuthController::class, 'page']);
    $r->add('POST', '/auth/session', [AuthController::class, 'session']);
    $r->add('POST', '/logout', [AuthController::class, 'logout']);

    // JSON API
    $r->add('GET', '/api/me', [ApiController::class, 'me']);
    $r->add('GET', '/api/search', [ApiController::class, 'search']);
    $r->add('GET', '/api/ai-data', [ApiController::class, 'aiData']);
    $r->add('POST', '/api/view', [ApiController::class, 'view'], ['nocsrf']);
    $r->add('GET', '/api/comments', [ApiController::class, 'comments']);
    $r->add('POST', '/api/comments', [ApiController::class, 'commentCreate'], ['auth']);
    $r->add('POST', '/api/comments/{id}/delete', [ApiController::class, 'commentDelete'], ['auth']);

    // Admin panel (editors and admins)
    $ed = ['role:editor'];
    $ad = ['role:admin'];
    $r->add('GET', '/admin', [Admin\DashboardController::class, 'index'], $ed);

    $r->add('GET', '/admin/posts', [Admin\PostsController::class, 'index'], $ed);
    $r->add('GET', '/admin/posts/new', [Admin\PostsController::class, 'create'], $ed);
    $r->add('GET', '/admin/posts/{id}/edit', [Admin\PostsController::class, 'edit'], $ed);
    $r->add('POST', '/admin/posts/save', [Admin\PostsController::class, 'save'], $ed);
    $r->add('POST', '/admin/posts/{id}/delete', [Admin\PostsController::class, 'delete'], $ed);
    $r->add('POST', '/admin/posts/preview', [Admin\PostsController::class, 'preview'], $ed);
    $r->add('GET', '/admin/posts/{id}/revisions', [Admin\PostsController::class, 'revisions'], $ed);
    $r->add('POST', '/admin/posts/{id}/revisions/{rev}/restore', [Admin\PostsController::class, 'restore'], $ed);

    $r->add('GET', '/admin/media', [Admin\MediaController::class, 'index'], $ed);
    $r->add('GET', '/admin/media/list', [Admin\MediaController::class, 'list'], $ed);
    $r->add('POST', '/admin/media/upload', [Admin\MediaController::class, 'upload'], $ed);
    $r->add('POST', '/admin/media/{id}/update', [Admin\MediaController::class, 'update'], $ed);
    $r->add('POST', '/admin/media/{id}/delete', [Admin\MediaController::class, 'delete'], $ed);

    foreach (['categories', 'tags'] as $kind) {
        $r->add('GET', "/admin/$kind", [Admin\TaxonomyController::class, 'index'], $ed);
        $r->add('POST', "/admin/$kind/save", [Admin\TaxonomyController::class, 'save'], $ed);
        $r->add('POST', "/admin/$kind/reorder", [Admin\TaxonomyController::class, 'reorder'], $ed);
        $r->add('POST', "/admin/$kind/{id}/delete", [Admin\TaxonomyController::class, 'delete'], $ed);
    }

    $r->add('GET', '/admin/comments', [Admin\CommentsController::class, 'index'], $ed);
    $r->add('POST', '/admin/comments/bulk', [Admin\CommentsController::class, 'bulk'], $ed);

    $r->add('GET', '/admin/messages', [Admin\CommentsController::class, 'messages'], $ad);
    $r->add('POST', '/admin/messages/{id}/delete', [Admin\CommentsController::class, 'messageDelete'], $ad);

    $r->add('GET', '/admin/ai', [Admin\AiController::class, 'index'], $ed);
    $r->add('POST', '/admin/ai/model', [Admin\AiController::class, 'saveModel'], $ed);
    $r->add('POST', '/admin/ai/model/delete', [Admin\AiController::class, 'deleteModel'], $ed);
    $r->add('POST', '/admin/ai/benchmark', [Admin\AiController::class, 'saveBenchmark'], $ed);
    $r->add('POST', '/admin/ai/benchmark/delete', [Admin\AiController::class, 'deleteBenchmark'], $ed);
    $r->add('POST', '/admin/ai/score', [Admin\AiController::class, 'saveScore'], $ed);
    $r->add('POST', '/admin/ai/score/delete', [Admin\AiController::class, 'deleteScore'], $ed);

    $r->add('GET', '/admin/users', [Admin\UsersController::class, 'index'], $ad);
    $r->add('POST', '/admin/users/{id}/update', [Admin\UsersController::class, 'update'], $ad);

    $r->add('GET', '/admin/settings', [Admin\SettingsController::class, 'index'], $ad);
    $r->add('POST', '/admin/settings', [Admin\SettingsController::class, 'save'], $ad);

    $r->add('GET', '/admin/system', [Admin\SystemController::class, 'index'], $ad);
    $r->add('POST', '/admin/system/cache', [Admin\SystemController::class, 'clearCache'], $ad);
    $r->add('POST', '/admin/system/sitemap', [Admin\SystemController::class, 'sitemap'], $ad);
    $r->add('POST', '/admin/system/backup', [Admin\SystemController::class, 'backup'], $ad);
    $r->add('GET', '/admin/system/log', [Admin\SystemController::class, 'log'], $ad);
    $r->add('GET', '/admin/audit', [Admin\SystemController::class, 'audit'], $ad);
};
