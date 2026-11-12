<?php
declare(strict_types=1);

namespace App\Core;

/** Key/value site settings, stored in MySQL and mirrored to the file cache. */
final class Settings
{
    private static ?array $all = null;

    /** @return array<string,mixed> */
    public static function all(): array
    {
        if (self::$all === null) {
            self::$all = Cache::remember('settings', 0, function (): array {
                $out = [];
                foreach (Database::all('SELECT `key`, `value` FROM settings') as $r) {
                    $decoded = json_decode((string)$r['value'], true);
                    $out[$r['key']] = json_last_error() === JSON_ERROR_NONE ? $decoded : $r['value'];
                }
                return $out;
            });
        }
        return self::$all;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();
        return array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '' ? $all[$key] : $default;
    }

    /** @param array<string,mixed> $values */
    public static function setMany(array $values): void
    {
        foreach ($values as $k => $v) {
            Database::run(
                'INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
                [$k, json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
            );
        }
        Cache::forget('settings');
        self::$all = null;
    }
}
