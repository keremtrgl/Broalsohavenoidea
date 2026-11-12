<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        $t = Session::get('_csrf');
        if (!is_string($t) || strlen($t) !== 64) {
            $t = bin2hex(random_bytes(32));
            Session::set('_csrf', $t);
        }
        return $t;
    }

    public static function check(): bool
    {
        if (!Session::exists()) {
            return false;
        }
        $sent = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? (Request::json()['_csrf'] ?? '');
        $real = Session::get('_csrf');
        return is_string($sent) && is_string($real) && $real !== '' && hash_equals($real, $sent);
    }
}
