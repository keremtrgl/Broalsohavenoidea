<?php
declare(strict_types=1);

namespace App\Core;

final class Logger
{
    public static function write(string $level, string $message, array $context = []): void
    {
        $dir = storage_path('logs');
        if (!is_dir($dir) || !is_writable($dir)) {
            error_log('[techblog] ' . $level . ': ' . $message);
            return;
        }
        $line = sprintf(
            "[%s] %s: %s%s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            str_replace(["\r", "\n"], ' ', $message),
            $context ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR) : ''
        );
        @file_put_contents($dir . '/app-' . date('Y-m-d') . '.log', $line, FILE_APPEND | LOCK_EX);
    }

    public static function error(string $m, array $c = []): void
    {
        self::write('error', $m, $c);
    }

    public static function warning(string $m, array $c = []): void
    {
        self::write('warning', $m, $c);
    }

    public static function info(string $m, array $c = []): void
    {
        self::write('info', $m, $c);
    }
}
