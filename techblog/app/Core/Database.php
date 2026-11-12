<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

/** Thin PDO wrapper. Every query goes through a prepared statement. */
final class Database
{
    private static ?PDO $pdo = null;

    public static function connect(?array $cfg = null): PDO
    {
        if (self::$pdo !== null && $cfg === null) {
            return self::$pdo;
        }
        $cfg ??= [
            'host' => (string)env('DB_HOST', 'localhost'),
            'port' => (string)env('DB_PORT', '3306'),
            'name' => (string)env('DB_NAME', ''),
            'user' => (string)env('DB_USER', ''),
            'pass' => (string)env('DB_PASS', ''),
        ];
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $cfg['host'], $cfg['port'], $cfg['name']);
        $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);
        $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, time_zone = '" . date('P') . "'");
        return self::$pdo = $pdo;
    }

    public static function pdo(): PDO
    {
        return self::connect();
    }

    /** @param array<int|string,mixed> $params */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $st = self::pdo()->prepare($sql);
        foreach ($params as $k => $v) {
            $type = match (true) {
                is_int($v) => PDO::PARAM_INT,
                is_bool($v) => PDO::PARAM_BOOL,
                $v === null => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
            $st->bindValue(is_int($k) ? $k + 1 : $k, $v, $type);
        }
        $st->execute();
        return $st;
    }

    /** @return list<array<string,mixed>> */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function val(string $sql, array $params = []): mixed
    {
        $v = self::run($sql, $params)->fetchColumn();
        return $v === false ? null : $v;
    }

    /** @param array<string,mixed> $data */
    public static function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $sql = 'INSERT INTO ' . self::ident($table) . ' (' . implode(',', array_map([self::class, 'ident'], $cols)) . ') VALUES ('
            . implode(',', array_fill(0, count($cols), '?')) . ')';
        self::run($sql, array_values($data));
        return (int)self::pdo()->lastInsertId();
    }

    /** @param array<string,mixed> $data */
    public static function update(string $table, array $data, int $id): void
    {
        $set = implode(',', array_map(fn($c) => self::ident($c) . '=?', array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        self::run('UPDATE ' . self::ident($table) . ' SET ' . $set . ' WHERE id=?', $params);
    }

    /** Quote an identifier that comes from code (never from user input). */
    public static function ident(string $name): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new \InvalidArgumentException('Invalid identifier');
        }
        return '`' . $name . '`';
    }

    /** Build "?,?,?" for an IN() clause. */
    public static function placeholders(int $n): string
    {
        return implode(',', array_fill(0, max(1, $n), '?'));
    }
}
