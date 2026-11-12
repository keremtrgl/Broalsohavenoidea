<?php
declare(strict_types=1);

namespace App\Core;

/** File cache for computed data (settings, sitemap, rendered blocks, Google keys). */
final class Cache
{
    private static function file(string $key): string
    {
        return storage_path('cache/data/' . sha1($key) . '.php');
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $f = self::file($key);
        if (!is_file($f)) {
            return $default;
        }
        $data = @include $f;
        if (!is_array($data) || !array_key_exists('v', $data) || ($data['e'] !== 0 && $data['e'] < time())) {
            return $default;
        }
        return $data['v'];
    }

    public static function set(string $key, mixed $value, int $ttl = 0): void
    {
        $f = self::file($key);
        $payload = '<?php return ' . var_export(['e' => $ttl > 0 ? time() + $ttl : 0, 'v' => $value], true) . ';';
        $tmp = $f . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, $payload, LOCK_EX) !== false) {
            @rename($tmp, $f);
            if (function_exists('opcache_invalidate')) {
                @opcache_invalidate($f, true);
            }
        }
    }

    public static function remember(string $key, int $ttl, callable $fn): mixed
    {
        $miss = new \stdClass();
        $v = self::get($key, $miss);
        if ($v === $miss) {
            $v = $fn();
            self::set($key, $v, $ttl);
        }
        return $v;
    }

    public static function forget(string $key): void
    {
        $f = self::file($key);
        if (is_file($f)) {
            @unlink($f);
            if (function_exists('opcache_invalidate')) {
                @opcache_invalidate($f, true);
            }
        }
    }

    /** Remove every data-cache entry. Returns the number of deleted files. */
    public static function flush(): int
    {
        $n = 0;
        foreach (glob(storage_path('cache/data/*.php')) ?: [] as $f) {
            if (@unlink($f)) {
                $n++;
            }
        }
        return $n;
    }
}
