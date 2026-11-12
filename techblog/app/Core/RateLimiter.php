<?php
declare(strict_types=1);

namespace App\Core;

/** Fixed-window rate limiter stored in MySQL, keyed per IP and/or per user. */
final class RateLimiter
{
    /** Register one attempt. Returns false when the limit is exceeded. */
    public static function hit(string $name, int $max, int $windowSeconds, ?int $userId = null): bool
    {
        $ok = self::touch($name . '|ip|' . Request::ipHash(), $max, $windowSeconds);
        if ($userId !== null) {
            $ok = self::touch($name . '|u|' . $userId, $max, $windowSeconds) && $ok;
        }
        return $ok;
    }

    private static function touch(string $key, int $max, int $window): bool
    {
        $key = substr(sha1($key), 0, 40);
        $now = time();
        Database::run(
            'INSERT INTO rate_limits (k, hits, expires_at) VALUES (?, 1, ?)
             ON DUPLICATE KEY UPDATE hits = IF(expires_at <= ?, 1, hits + 1), expires_at = IF(expires_at <= ?, ?, expires_at)',
            [$key, $now + $window, $now, $now, $now + $window]
        );
        if (random_int(1, 200) === 1) {
            Database::run('DELETE FROM rate_limits WHERE expires_at <= ?', [$now]);
        }
        return (int)Database::val('SELECT hits FROM rate_limits WHERE k = ?', [$key]) <= $max;
    }
}
