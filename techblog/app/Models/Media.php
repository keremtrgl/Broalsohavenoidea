<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database as DB;

final class Media
{
    /** @var array<int,array<string,mixed>|null> */
    private static array $memo = [];

    /** @return array<string,mixed>|null */
    public static function find(int $id): ?array
    {
        return self::$memo[$id] ??= DB::one('SELECT * FROM media WHERE id = ?', [$id]);
    }

    /** @return list<array<string,mixed>> */
    public static function page(int $limit, int $offset): array
    {
        return DB::all('SELECT * FROM media ORDER BY id DESC LIMIT ? OFFSET ?', [$limit, $offset]);
    }

    /** @return array<string,list<array{w:int,path:string}>> */
    public static function variants(array $m): array
    {
        $v = json_decode((string)($m['variants'] ?? ''), true);
        return is_array($v) ? $v : [];
    }

    /** <source> elements (AVIF first, then WebP) with srcset and sizes. */
    public static function sourcesHtml(array $m, string $sizes): string
    {
        $out = '';
        $variants = self::variants($m);
        foreach (['avif' => 'image/avif', 'webp' => 'image/webp'] as $fmt => $mime) {
            if (empty($variants[$fmt])) {
                continue;
            }
            $set = implode(', ', array_map(static fn($x) => e($x['path']) . ' ' . (int)$x['w'] . 'w', $variants[$fmt]));
            $out .= '<source type="' . $mime . '" srcset="' . $set . '" sizes="' . e($sizes) . '">';
        }
        return $out;
    }

    /** Delete the database row and every file that belongs to it. */
    public static function delete(array $m): void
    {
        $files = [$m['path']];
        foreach (self::variants($m) as $list) {
            foreach ($list as $v) {
                $files[] = $v['path'];
            }
        }
        foreach ($files as $rel) {
            $abs = public_path(ltrim((string)$rel, '/'));
            if (str_starts_with((string)$rel, '/uploads/') && !str_contains((string)$rel, '..') && is_file($abs)) {
                @unlink($abs);
            }
        }
        DB::run('DELETE FROM media WHERE id = ?', [(int)$m['id']]);
        unset(self::$memo[(int)$m['id']]);
    }
}
