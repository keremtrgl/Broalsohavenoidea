<?php
declare(strict_types=1);

namespace App\Core;

/** Plain PHP templates with layouts. Templates must escape output with e(). */
final class View
{
    /** @param array<string,mixed> $data */
    public static function partial(string $template, array $data = []): string
    {
        $file = base_path('app/Views/' . $template . '.php');
        if (!is_file($file)) {
            throw new \RuntimeException('View not found: ' . $template);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        try {
            require $file;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        return (string)ob_get_clean();
    }

    /** @param array<string,mixed> $data */
    public static function render(string $template, array $data = [], string $layout = 'layouts/public'): string
    {
        $data['content'] = self::partial($template, $data);
        return self::partial($layout, $data);
    }
}
