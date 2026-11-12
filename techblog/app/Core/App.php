<?php
declare(strict_types=1);

namespace App\Core;

/** HTTP kernel: page cache, maintenance mode, routing, middleware and response. */
final class App
{
    public static function isInstalled(): bool
    {
        return is_file(base_path('.env')) && is_file(storage_path('installed.lock'));
    }

    public static function run(): void
    {
        if (!self::isInstalled()) {
            $installer = is_file(public_path('install.php'));
            http_response_code($installer ? 302 : 503);
            if ($installer) {
                header('Location: /install.php');
            } else {
                header('Content-Type: text/plain; charset=UTF-8');
                echo 'Kurulum tamamlanmamış: .env dosyası veya storage/installed.lock bulunamadı.';
            }
            return;
        }

        $method = Request::method();
        $path = Request::path();
        $maintenance = (bool)Settings::get('maintenance_mode', false);

        // 1) Serve a cached page when possible (no database query on the hot path).
        if (!$maintenance && PageCache::eligible() && ($hit = PageCache::get())) {
            Csp::restore($hit['csp']);
            Csp::send();
            header('Content-Type: text/html; charset=UTF-8');
            header('Cache-Control: public, max-age=300, stale-while-revalidate=600');
            header('ETag: ' . $hit['etag']);
            header('X-Cache: HIT');
            if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $hit['etag']) {
                http_response_code(304);
                return;
            }
            echo $hit['body'];
            return;
        }

        // 2) Maintenance mode: only editors/admins and the login flow get through.
        if ($maintenance && !Auth::is('editor') && !preg_match('#^/(login|logout|auth/session|api/me)$#', $path)) {
            Csp::send();
            http_response_code(503);
            header('Retry-After: 3600');
            header('Cache-Control: no-store');
            header('Content-Type: text/html; charset=UTF-8');
            echo View::render('errors/maintenance', ['seo' => ['title' => 'Bakım çalışması', 'robots' => 'noindex']]);
            return;
        }

        // 3) Route.
        $router = new Router();
        (require base_path('config/routes.php'))($router);
        $match = $router->match($method, $path);
        $response = $match ? self::dispatch($match, $method) : self::notFound();

        // 4) Send, and store cacheable HTML.
        Csp::send();
        if ($response->cacheable && $response->status === 200 && !$maintenance && PageCache::eligible()) {
            PageCache::put($response->body, Csp::extra());
            $response->header('Cache-Control', 'public, max-age=300, stale-while-revalidate=600');
            $response->header('X-Cache', 'MISS');
        } elseif (!isset($response->headers['Cache-Control'])) {
            $response->header('Cache-Control', 'no-store, private');
        }
        if ($method === 'HEAD') {
            $response->body = '';
        }
        $response->send();
    }

    private static function dispatch(array $match, string $method): Response
    {
        $mw = $match['middleware'];
        if ($method !== 'GET' && $method !== 'HEAD' && !in_array('nocsrf', $mw, true) && !Csrf::check()) {
            return self::fail(419, 'Oturum doğrulaması başarısız oldu. Sayfayı yenileyip tekrar deneyin.');
        }
        foreach ($mw as $rule) {
            if ($rule === 'auth' && !Auth::check()) {
                return self::denied();
            }
            if (str_starts_with($rule, 'role:') && !Auth::is(substr($rule, 5))) {
                return self::denied();
            }
        }
        [$class, $action] = $match['handler'];
        return (new $class())->$action($match['params']);
    }

    private static function denied(): Response
    {
        if (Request::wantsJson()) {
            return Response::json(['ok' => false, 'error' => Auth::check() ? 'Bu işlem için yetkiniz yok.' : 'Giriş yapmanız gerekiyor.'], Auth::check() ? 403 : 401);
        }
        if (!Auth::check()) {
            return Response::redirect('/login?next=' . rawurlencode(Request::path()));
        }
        return Response::html(View::render('errors/403', ['seo' => ['title' => 'Erişim reddedildi', 'robots' => 'noindex']]), 403);
    }

    public static function fail(int $status, string $message): Response
    {
        if (Request::wantsJson()) {
            return Response::json(['ok' => false, 'error' => $message], $status);
        }
        return Response::html(View::render('errors/message', ['message' => $message, 'seo' => ['title' => 'Hata', 'robots' => 'noindex']]), $status);
    }

    public static function notFound(): Response
    {
        if (Request::wantsJson()) {
            return Response::json(['ok' => false, 'error' => 'Bulunamadı.'], 404);
        }
        return Response::html(View::render('errors/404', ['seo' => ['title' => 'Sayfa bulunamadı', 'robots' => 'noindex']]), 404);
    }
}
