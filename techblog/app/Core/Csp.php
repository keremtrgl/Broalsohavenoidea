<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Security headers and a hash-based Content-Security-Policy.
 * Inline blocks are whitelisted by SHA-256 hash, so pages stay cacheable (no nonces).
 */
final class Csp
{
    /** @var array<string,list<string>> */
    private static array $extra = [];

    public static function add(string $directive, string $source): void
    {
        if (!in_array($source, self::$extra[$directive] ?? [], true)) {
            self::$extra[$directive][] = $source;
        }
    }

    /** Register an inline <script> or <style> body and return it unchanged. */
    public static function inline(string $directive, string $content): string
    {
        self::add($directive, "'sha256-" . base64_encode(hash('sha256', $content, true)) . "'");
        return $content;
    }

    /** @return array<string,list<string>> */
    public static function extra(): array
    {
        return self::$extra;
    }

    /** @param array<string,list<string>> $extra */
    public static function restore(array $extra): void
    {
        self::$extra = $extra;
    }

    /** Sources needed by the Firebase Authentication popup flow (login page only). */
    public static function allowFirebase(): void
    {
        $domain = (string)env('FIREBASE_AUTH_DOMAIN', '');
        self::add('script-src', 'https://apis.google.com');
        foreach (['https://identitytoolkit.googleapis.com', 'https://securetoken.googleapis.com', 'https://www.googleapis.com'] as $s) {
            self::add('connect-src', $s);
        }
        if (preg_match('/^[a-z0-9.-]+$/i', $domain)) {
            self::add('frame-src', 'https://' . $domain);
            self::add('connect-src', 'https://' . $domain);
        }
    }

    public static function policy(): string
    {
        $d = [
            'default-src' => ["'self'"],
            'script-src' => ["'self'"],
            'style-src' => ["'self'"],
            'img-src' => ["'self'", 'data:', 'blob:', 'https://lh3.googleusercontent.com'],
            'font-src' => ["'self'"],
            'connect-src' => ["'self'"],
            'frame-src' => ["'none'"],
            'worker-src' => ["'self'"],
            'manifest-src' => ["'self'"],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'none'"],
        ];
        foreach (self::$extra as $k => $list) {
            if (isset($d[$k]) && $d[$k] === ["'none'"]) {
                $d[$k] = [];
            }
            $d[$k] = array_values(array_unique(array_merge($d[$k] ?? [], $list)));
        }
        $parts = [];
        foreach ($d as $k => $v) {
            $parts[] = $k . ' ' . implode(' ', $v);
        }
        if (Session::secureCookies()) {
            $parts[] = 'upgrade-insecure-requests';
        }
        return implode('; ', $parts);
    }

    public static function send(): void
    {
        if (headers_sent()) {
            return;
        }
        header('Content-Security-Policy: ' . self::policy());
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=(), browsing-topics=()');
        header('Cross-Origin-Opener-Policy: same-origin-allow-popups');
        header('X-Permitted-Cross-Domain-Policies: none');
        if (Request::isHttps()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
        header_remove('X-Powered-By');
    }
}
