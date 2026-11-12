<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Full-page HTML cache for public GET pages.
 * Cached pages contain no per-user data: the signed-in state is filled in by JavaScript.
 */
final class PageCache
{
    private const TTL = 3600;

    private static function dir(): string
    {
        return storage_path('cache/pages');
    }

    private static function key(): string
    {
        $q = [];
        if (isset($_GET['page']) && ctype_digit((string)$_GET['page'])) {
            $q['page'] = (int)$_GET['page'];
        }
        return sha1(Request::path() . '?' . http_build_query($q));
    }

    public static function eligible(): bool
    {
        if (Request::method() !== 'GET' || !env('PAGE_CACHE', true)) {
            return false;
        }
        $extra = array_diff(array_keys($_GET), ['page']);
        if ($extra) {
            return false;
        }
        $p = Request::path();
        return $p === '/' || $p === '/archive' || $p === '/privacy' || $p === '/about'
            || (bool)preg_match('#^/(post|category|tag|series)/[a-z0-9-]+$#', $p);
    }

    /** @return array{body:string,csp:array<string,list<string>>,etag:string}|null */
    public static function get(): ?array
    {
        $f = self::dir() . '/' . self::key() . '.html';
        if (!is_file($f)) {
            return null;
        }
        $mtime = (int)filemtime($f);
        // A scheduled post becoming due invalidates everything written before that moment.
        $due = (int)Cache::get('next_scheduled_at', 0);
        if ($mtime + self::TTL < time() || ($due > 0 && $due <= time() && $mtime < $due)) {
            @unlink($f);
            return null;
        }
        $raw = (string)file_get_contents($f);
        $nl = strpos($raw, "\n");
        if ($nl === false) {
            return null;
        }
        $meta = json_decode(substr($raw, 0, $nl), true);
        if (!is_array($meta)) {
            return null;
        }
        return ['body' => substr($raw, $nl + 1), 'csp' => $meta['csp'] ?? [], 'etag' => '"' . sha1($raw) . '"'];
    }

    /** @param array<string,list<string>> $csp */
    public static function put(string $body, array $csp): void
    {
        $dir = self::dir();
        if (!is_dir($dir) || !is_writable($dir)) {
            return;
        }
        $f = $dir . '/' . self::key() . '.html';
        $tmp = $f . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, json_encode(['csp' => $csp]) . "\n" . $body, LOCK_EX) !== false) {
            @rename($tmp, $f);
        }
    }

    public static function flush(): int
    {
        $n = 0;
        foreach (glob(self::dir() . '/*.html') ?: [] as $f) {
            if (@unlink($f)) {
                $n++;
            }
        }
        return $n;
    }
}
