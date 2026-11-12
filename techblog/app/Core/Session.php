<?php
declare(strict_types=1);

namespace App\Core;

/** Hardened, lazily started PHP session. */
final class Session
{
    public const NAME = 'tbsess';
    private const LIFETIME = 60 * 60 * 24 * 14;
    private const ROTATE_AFTER = 60 * 30;

    public static function exists(): bool
    {
        return isset($_COOKIE[self::NAME]);
    }

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $dir = storage_path('sessions');
        if (is_dir($dir) && is_writable($dir)) {
            session_save_path($dir);
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.gc_maxlifetime', (string)self::LIFETIME);
        session_name(self::NAME);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => self::secureCookies(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        $now = time();
        if (isset($_SESSION['_last']) && $now - (int)$_SESSION['_last'] > self::LIFETIME) {
            self::destroy();
            session_start();
        }
        if (!isset($_SESSION['_rot'])) {
            $_SESSION['_rot'] = $now;
        } elseif ($now - (int)$_SESSION['_rot'] > self::ROTATE_AFTER) {
            self::regenerate();
        }
        $_SESSION['_last'] = $now;
    }

    public static function secureCookies(): bool
    {
        return str_starts_with((string)env('APP_URL', ''), 'https://') || Request::isHttps();
    }

    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['_rot'] = time();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /** One-time flash message shown as a toast on the next page. */
    public static function flash(string $type, string $message): void
    {
        self::start();
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }

    /** @return list<array{type:string,message:string}> */
    public static function pullFlash(): array
    {
        if (!self::exists()) {
            return [];
        }
        self::start();
        $f = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $f;
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (!self::exists()) {
                return;
            }
            self::start();
        }
        $_SESSION = [];
        $p = session_get_cookie_params();
        setcookie(self::NAME, '', ['expires' => time() - 3600, 'path' => $p['path'], 'secure' => $p['secure'], 'httponly' => true, 'samesite' => 'Lax']);
        session_destroy();
    }
}
