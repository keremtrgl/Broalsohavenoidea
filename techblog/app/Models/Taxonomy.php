<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database as DB;

/** Categories and tags share the same shape, so one class serves both tables. */
final class Taxonomy
{
    private static function table(string $kind): string
    {
        return $kind === 'tag' ? 'tags' : 'categories';
    }

    /** @return list<array<string,mixed>> with a live post count */
    public static function all(string $kind): array
    {
        if ($kind === 'tag') {
            return DB::all("SELECT t.*, (SELECT COUNT(*) FROM post_tags pt JOIN posts p ON p.id = pt.post_id WHERE pt.tag_id = t.id AND " . Post::LIVE . ") AS post_count
                FROM tags t ORDER BY t.sort_order, t.name");
        }
        return DB::all("SELECT c.*, (SELECT COUNT(*) FROM posts p WHERE p.category_id = c.id AND " . Post::LIVE . ") AS post_count
            FROM categories c ORDER BY c.sort_order, c.name");
    }

    /** @return array<string,mixed>|null */
    public static function bySlug(string $kind, string $slug): ?array
    {
        return DB::one('SELECT * FROM ' . self::table($kind) . ' WHERE slug = ?', [$slug]);
    }

    /** @return array<string,mixed>|null */
    public static function find(string $kind, int $id): ?array
    {
        return DB::one('SELECT * FROM ' . self::table($kind) . ' WHERE id = ?', [$id]);
    }

    public static function uniqueSlug(string $kind, string $wanted, int $ignoreId = 0): string
    {
        $base = slugify($wanted) ?: 'etiket';
        $slug = $base;
        $i = 2;
        while (DB::val('SELECT 1 FROM ' . self::table($kind) . ' WHERE slug = ? AND id <> ?', [$slug, $ignoreId])) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    /** @param array<string,mixed> $data */
    public static function save(string $kind, array $data, int $id = 0): int
    {
        if ($id > 0) {
            DB::update(self::table($kind), $data, $id);
            return $id;
        }
        $data['sort_order'] = (int)DB::val('SELECT COALESCE(MAX(sort_order), 0) + 1 FROM ' . self::table($kind));
        return DB::insert(self::table($kind), $data);
    }

    public static function delete(string $kind, int $id): void
    {
        DB::run('DELETE FROM ' . self::table($kind) . ' WHERE id = ?', [$id]);
    }

    /** @param list<int> $ids ordered ids from the drag-and-drop list */
    public static function reorder(string $kind, array $ids): void
    {
        foreach (array_values($ids) as $i => $id) {
            DB::run('UPDATE ' . self::table($kind) . ' SET sort_order = ? WHERE id = ?', [$i + 1, (int)$id]);
        }
    }

    /** Find a tag by name or create it. */
    public static function tagId(string $name): int
    {
        $name = mb_substr(trim($name), 0, 100);
        $id = DB::val('SELECT id FROM tags WHERE name = ? OR slug = ?', [$name, slugify($name)]);
        return $id ? (int)$id : self::save('tag', ['name' => $name, 'slug' => self::uniqueSlug('tag', $name)]);
    }
}
