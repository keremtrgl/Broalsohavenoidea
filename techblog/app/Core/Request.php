<?php
declare(strict_types=1);

namespace App\Core;

final class Request
{
    private static ?array $json = null;

    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = '/' . trim(rawurldecode($path), '/');
        return $path;
    }

    public static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') === '443')
            || (env('TRUST_PROXY', false) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }

    public static function ip(): string
    {
        if (env('TRUST_PROXY', false) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $first = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
            if (filter_var($first, FILTER_VALIDATE_IP)) {
                return $first;
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /** Salted hash so raw IP addresses are never stored (KVKK/GDPR). */
    public static function ipHash(): string
    {
        return hash_hmac('sha256', self::ip(), (string)env('APP_KEY', 'techblog'));
    }

    public static function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
            || str_starts_with(self::path(), '/api/')
            || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';
    }

    /** @return array<string,mixed> */
    public static function json(): array
    {
        if (self::$json === null) {
            self::$json = [];
            if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
                $raw = file_get_contents('php://input', false, null, 0, 6 * 1024 * 1024);
                $data = json_decode($raw ?: '', true);
                self::$json = is_array($data) ? $data : [];
            }
        }
        return self::$json;
    }

    public static function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? self::json()[$key] ?? $_GET[$key] ?? $default;
    }

    public static function str(string $key, int $max = 255): string
    {
        $v = self::input($key, '');
        if (!is_scalar($v)) {
            return '';
        }
        $v = trim(str_replace("\0", '', (string)$v));
        return mb_substr($v, 0, $max);
    }

    public static function int(string $key, int $default = 0): int
    {
        $v = self::input($key);
        return is_numeric($v) ? (int)$v : $default;
    }

    public static function bool(string $key): bool
    {
        return filter_var(self::input($key, false), FILTER_VALIDATE_BOOLEAN);
    }

    /** Same-origin check for state-changing requests that cannot carry a CSRF token. */
    public static function sameOrigin(): bool
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        if ($origin === '') {
            $ref = $_SERVER['HTTP_REFERER'] ?? '';
            $p = parse_url($ref);
            $origin = isset($p['scheme'], $p['host']) ? $p['scheme'] . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '') : '';
        }
        return $origin !== '' && rtrim($origin, '/') === rtrim(self::origin(), '/');
    }

    public static function origin(): string
    {
        $p = parse_url((string)env('APP_URL', ''));
        return isset($p['scheme'], $p['host']) ? $p['scheme'] . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : '') : '';
    }
}
