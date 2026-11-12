<?php
declare(strict_types=1);

namespace App\Core;

/** Resolves logical asset names to content-hashed files via the build manifest. */
final class Assets
{
    private static ?array $manifest = null;

    /** @return array<string,string> */
    public static function manifest(): array
    {
        if (self::$manifest === null) {
            $f = public_path('assets/manifest.json');
            $m = is_file($f) ? json_decode((string)file_get_contents($f), true) : null;
            self::$manifest = is_array($m) ? $m : [];
        }
        return self::$manifest;
    }

    public static function url(string $name): string
    {
        return '/assets/' . (self::manifest()[$name] ?? $name);
    }

    /** Contents of a built asset, used to inline the critical CSS. */
    public static function inline(string $name): string
    {
        $f = public_path('assets/' . (self::manifest()[$name] ?? $name));
        return is_file($f) ? trim((string)file_get_contents($f)) : '';
    }
}
