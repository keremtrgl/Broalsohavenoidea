<?php
declare(strict_types=1);

namespace App\Core;

/** Session-backed authentication state. Roles live in the database. */
final class Auth
{
    public const ROLES = ['user', 'editor', 'admin'];
    private static ?array $user = null;
    private static bool $resolved = false;

    /** @return array<string,mixed>|null */
    public static function user(): ?array
    {
        if (self::$resolved) {
            return self::$user;
        }
        self::$resolved = true;
        if (!Session::exists()) {
            return null;
        }
        $id = Session::get('uid');
        if (!is_int($id)) {
            return null;
        }
        if (Session::get('ua') !== self::fingerprint()) {
            self::logout();
            return null;
        }
        // Re-read the user on every request so bans and role changes apply immediately.
        $u = Database::one('SELECT id, firebase_uid, email, name, avatar, role, is_banned, email_verified FROM users WHERE id = ?', [$id]);
        if (!$u || (int)$u['is_banned'] === 1) {
            self::logout();
            return null;
        }
        return self::$user = $u;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        $u = self::user();
        return $u ? (int)$u['id'] : null;
    }

    /** True when the user holds at least the given role and has a verified e-mail. */
    public static function is(string $role): bool
    {
        $u = self::user();
        if (!$u) {
            return false;
        }
        $have = array_search($u['role'], self::ROLES, true);
        $need = array_search($role, self::ROLES, true);
        if ($have === false || $need === false || $have < $need) {
            return false;
        }
        return $need === 0 || (int)$u['email_verified'] === 1;
    }

    /** @param array<string,mixed> $user */
    public static function login(array $user): void
    {
        Session::start();
        Session::regenerate();
        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['ua'] = self::fingerprint();
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        self::$user = null;
        self::$resolved = false;
        self::hintCookie(true);
    }

    public static function logout(): void
    {
        Session::destroy();
        self::$user = null;
        self::$resolved = true;
        self::hintCookie(false);
    }

    /** Non-sensitive flag cookie: tells cached pages whether to ask /api/me for the user. */
    private static function hintCookie(bool $on): void
    {
        if (headers_sent()) {
            return;
        }
        setcookie('tb_auth', $on ? '1' : '', [
            'expires' => $on ? 0 : time() - 3600,
            'path' => '/',
            'secure' => Session::secureCookies(),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }

    private static function fingerprint(): string
    {
        return hash('sha256', (string)($_SERVER['HTTP_USER_AGENT'] ?? ''));
    }
}
