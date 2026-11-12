<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database as DB;

/** Pure-PHP SQL dump (works on shared hosting where mysqldump is unavailable). */
final class Backup
{
    /** Write a gzip-compressed dump and return its path. */
    public static function create(): string
    {
        $dir = storage_path('backups');
        if (!is_dir($dir) && !mkdir($dir, 0700, true) && !is_dir($dir)) {
            throw new \RuntimeException('Yedek klasörü oluşturulamadı.');
        }
        $file = $dir . '/backup-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.sql.gz';
        $gz = gzopen($file, 'wb6');
        if (!$gz) {
            throw new \RuntimeException('Yedek dosyası yazılamadı.');
        }
        $pdo = DB::pdo();
        gzwrite($gz, "-- TechBlog database backup\n-- Created: " . date('c') . "\nSET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");
        foreach ($pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN) as $table) {
            $t = DB::ident((string)$table);
            $create = $pdo->query('SHOW CREATE TABLE ' . $t)->fetch(\PDO::FETCH_NUM);
            gzwrite($gz, "DROP TABLE IF EXISTS $t;\n" . $create[1] . ";\n\n");
            if ($table === 'rate_limits') {
                continue; // transient data
            }
            $st = $pdo->query('SELECT * FROM ' . $t);
            while ($row = $st->fetch(\PDO::FETCH_ASSOC)) {
                $vals = array_map(static fn($v) => $v === null ? 'NULL' : (is_int($v) || is_float($v) ? (string)$v : $pdo->quote((string)$v)), $row);
                gzwrite($gz, "INSERT INTO $t (" . implode(',', array_map([DB::class, 'ident'], array_keys($row))) . ') VALUES (' . implode(',', $vals) . ");\n");
            }
            gzwrite($gz, "\n");
        }
        gzwrite($gz, "SET FOREIGN_KEY_CHECKS=1;\n");
        gzclose($gz);
        return $file;
    }
}
