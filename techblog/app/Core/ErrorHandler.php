<?php
declare(strict_types=1);

namespace App\Core;

/** Logs every error to storage/logs and never shows details in production. */
final class ErrorHandler
{
    public static function register(): void
    {
        error_reporting(E_ALL);
        $debug = (bool)env('APP_DEBUG', false);
        ini_set('display_errors', $debug ? '1' : '0');
        ini_set('log_errors', '1');
        $logDir = storage_path('logs');
        if (is_dir($logDir) && is_writable($logDir)) {
            ini_set('error_log', $logDir . '/php-' . date('Y-m-d') . '.log');
        }

        set_error_handler(static function (int $no, string $msg, string $file, int $line): bool {
            if (!(error_reporting() & $no)) {
                return false;
            }
            throw new \ErrorException($msg, 0, $no, $file, $line);
        });

        set_exception_handler([self::class, 'handle']);

        register_shutdown_function(static function (): void {
            $e = error_get_last();
            if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                self::handle(new \ErrorException($e['message'], 0, $e['type'], $e['file'], $e['line']));
            }
        });
    }

    public static function handle(\Throwable $e): void
    {
        Logger::error(get_class($e) . ': ' . $e->getMessage(), [
            'file' => str_replace(TB_BASE, '', $e->getFile()) . ':' . $e->getLine(),
            'url' => ($_SERVER['REQUEST_METHOD'] ?? 'CLI') . ' ' . ($_SERVER['REQUEST_URI'] ?? ''),
            'trace' => array_slice(explode("\n", str_replace(TB_BASE, '', $e->getTraceAsString())), 0, 8),
        ]);
        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, $e . "\n");
            return;
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            http_response_code(500);
            header('Cache-Control: no-store');
        }
        if (Request::wantsJson()) {
            if (!headers_sent()) {
                header('Content-Type: application/json; charset=UTF-8');
            }
            echo json_encode(['ok' => false, 'error' => 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.']);
            return;
        }
        if (env('APP_DEBUG', false)) {
            if (!headers_sent()) {
                header('Content-Type: text/plain; charset=UTF-8');
            }
            echo $e;
            return;
        }
        $page = public_path('500.html');
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=UTF-8');
        }
        echo is_file($page) ? file_get_contents($page) : '<h1>500</h1>';
    }
}
