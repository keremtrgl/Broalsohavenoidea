<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\Csp;
use App\Core\Csrf;
use App\Core\Database as DB;
use App\Core\Logger;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\FirebaseVerifier;

final class AuthController
{
    public function page(array $p): Response
    {
        $next = self::safeNext(Request::str('next', 200));
        if (Auth::check()) {
            return Response::redirect($next);
        }
        Csrf::token();
        Csp::allowFirebase();
        return Response::html(View::render('public/login', [
            'next' => $next,
            'firebase' => [
                'apiKey' => (string)env('FIREBASE_API_KEY', ''),
                'authDomain' => (string)env('FIREBASE_AUTH_DOMAIN', ''),
                'projectId' => (string)env('FIREBASE_PROJECT_ID', ''),
                'appId' => (string)env('FIREBASE_APP_ID', ''),
            ],
            'seo' => ['title' => 'Giriş yap', 'robots' => 'noindex, nofollow', 'canonical' => url('/login')],
        ]));
    }

    /** Exchange a Firebase ID token for a server session. The token is verified here, never trusted from the browser. */
    public function session(array $p): Response
    {
        if (!RateLimiter::hit('login', 10, 600)) {
            return Response::json(['ok' => false, 'error' => 'Çok fazla giriş denemesi yapıldı. 10 dakika sonra tekrar deneyin.'], 429);
        }
        $token = Request::input('idToken', '');
        if (!is_string($token) || $token === '') {
            return Response::json(['ok' => false, 'error' => 'Kimlik belirteci eksik.'], 422);
        }
        try {
            $claims = (new FirebaseVerifier((string)env('FIREBASE_PROJECT_ID', '')))->verify($token);
        } catch (\Throwable $e) {
            Logger::warning('Firebase token rejected: ' . $e->getMessage());
            return Response::json(['ok' => false, 'error' => 'Kimlik doğrulanamadı. Lütfen tekrar giriş yapın.'], 401);
        }

        $user = DB::one('SELECT * FROM users WHERE firebase_uid = ?', [$claims['uid']]);
        $name = $claims['name'] !== '' ? $claims['name'] : ucfirst(explode('@', $claims['email'])[0]);
        $avatar = str_starts_with($claims['picture'], 'https://lh3.googleusercontent.com/') ? mb_substr($claims['picture'], 0, 500) : '';
        if ($user) {
            if ((int)$user['is_banned'] === 1) {
                return Response::json(['ok' => false, 'error' => 'Bu hesap engellenmiştir.'], 403);
            }
            DB::update('users', [
                'email' => $claims['email'], 'name' => $user['name'] ?: $name, 'avatar' => $avatar,
                'email_verified' => $claims['email_verified'] ? 1 : 0, 'last_login_at' => now(),
            ], (int)$user['id']);
            $id = (int)$user['id'];
        } else {
            $id = DB::insert('users', [
                'firebase_uid' => $claims['uid'], 'email' => $claims['email'], 'name' => $name, 'avatar' => $avatar, 'role' => 'user',
                'is_banned' => 0, 'email_verified' => $claims['email_verified'] ? 1 : 0, 'created_at' => now(), 'last_login_at' => now(),
            ]);
        }
        // The installer stores the first admin's address. It only counts when Firebase reports the e-mail as verified,
        // so nobody can claim the admin role by registering that address with a password first.
        $adminEmail = mb_strtolower((string)env('ADMIN_EMAIL', ''));
        if ($adminEmail !== '' && $claims['email'] === $adminEmail && $claims['email_verified']) {
            DB::run("UPDATE users SET role = 'admin' WHERE id = ? AND role <> 'admin'", [$id]);
        }

        $fresh = DB::one('SELECT * FROM users WHERE id = ?', [$id]);
        Auth::login($fresh);
        if ($fresh['role'] !== 'user') {
            Audit::log('login', 'user', $id);
        }
        $next = self::safeNext((string)Request::input('next', '/'));
        if (str_starts_with($next, '/admin') && !Auth::is('editor')) {
            $next = '/';
        }
        return Response::json([
            'ok' => true, 'redirect' => $next,
            'needs_verification' => !$claims['email_verified'],
        ]);
    }

    public function logout(array $p): Response
    {
        Auth::logout();
        return Request::wantsJson() ? Response::json(['ok' => true]) : Response::redirect('/');
    }

    private static function safeNext(string $next): string
    {
        return ($next !== '' && preg_match('#^/[A-Za-z0-9/_\-]*$#', $next) && !str_starts_with($next, '//')) ? $next : '/';
    }
}
