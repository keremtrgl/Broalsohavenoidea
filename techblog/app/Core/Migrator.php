<?php
declare(strict_types=1);

namespace App\Core;

/** Runs database/migrations/*.php once each, in filename order. */
final class Migrator
{
    /** @return list<string> names of the migrations that were applied */
    public static function migrate(): array
    {
        Database::pdo()->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                name VARCHAR(190) NOT NULL PRIMARY KEY,
                ran_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
        $done = array_column(Database::all('SELECT name FROM migrations'), 'name');
        $applied = [];
        $files = glob(base_path('database/migrations/*.php')) ?: [];
        sort($files);
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (in_array($name, $done, true)) {
                continue;
            }
            $statements = require $file;
            foreach ((array)$statements as $sql) {
                Database::pdo()->exec($sql);
            }
            Database::run('INSERT INTO migrations (name, ran_at) VALUES (?, ?)', [$name, now()]);
            $applied[] = $name;
        }
        return $applied;
    }
}
