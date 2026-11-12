<?php
declare(strict_types=1);

use App\Core\Env;
use App\Core\Csrf;
use App\Core\Settings;
use App\Core\Assets;

/** Escape a value for HTML output. */
function e(mixed $v): string
{
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function env(string $key, mixed $default = null): mixed
{
    return Env::get($key, $default);
}

function base_path(string $p = ''): string
{
    return TB_BASE . ($p !== '' ? '/' . ltrim($p, '/') : '');
}

function public_path(string $p = ''): string
{
    return TB_PUBLIC . ($p !== '' ? '/' . ltrim($p, '/') : '');
}

function storage_path(string $p = ''): string
{
    return base_path('storage' . ($p !== '' ? '/' . ltrim($p, '/') : ''));
}

/** Absolute URL for a site path. */
function url(string $path = '/'): string
{
    return rtrim((string)env('APP_URL', ''), '/') . '/' . ltrim($path, '/');
}

/** Versioned asset URL from the build manifest. */
function asset(string $name): string
{
    return Assets::url($name);
}

function setting(string $key, mixed $default = null): mixed
{
    return Settings::get($key, $default);
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Csrf::token()) . '">';
}

/** Turkish-aware slug generator. */
function slugify(string $text): string
{
    $map = ['ç' => 'c', 'Ç' => 'c', 'ğ' => 'g', 'Ğ' => 'g', 'ı' => 'i', 'I' => 'i', 'İ' => 'i', 'ö' => 'o', 'Ö' => 'o',
        'ş' => 's', 'Ş' => 's', 'ü' => 'u', 'Ü' => 'u', 'τ' => 'tau', '+' => ' plus ', '#' => ' sharp ', '&' => ' ve '];
    $text = strtr($text, $map);
    $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    $text = strtolower($ascii !== false ? $ascii : $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim(substr($text, 0, 120), '-');
}

/** Format a datetime string as a Turkish long date, e.g. "12 Kasım 2026". */
function tr_date(?string $datetime, bool $withTime = false): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    if ($ts === false) {
        return '';
    }
    $months = [1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    $out = (int)date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    return $withTime ? $out . ' ' . date('H:i', $ts) : $out;
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/** Plain-text excerpt limited to $len characters. */
function str_limit(string $text, int $len = 160): string
{
    $text = trim(preg_replace('/\s+/u', ' ', strip_tags($text)) ?? '');
    return mb_strlen($text) > $len ? rtrim(mb_substr($text, 0, $len - 1)) . '…' : $text;
}

/** Thousands-separated integer in Turkish notation. */
function num(int|float $n, int $dec = 0): string
{
    return number_format((float)$n, $dec, ',', '.');
}

/** Inline SVG icon (decorative: hidden from assistive technology). */
function icon(string $name): string
{
    static $paths = [
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon' => '<path d="M20 14.5A8 8 0 0 1 9.5 4a8 8 0 1 0 10.5 10.5z"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/>',
        'rss' => '<path d="M5 11a8 8 0 0 1 8 8M5 5a14 14 0 0 1 14 14"/><circle cx="6" cy="18" r="1.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'chip' => '<rect x="7" y="7" width="10" height="10" rx="2"/><path d="M10 3v4M14 3v4M10 17v4M14 17v4M3 10h4M3 14h4M17 10h4M17 14h4"/>',
        'code' => '<path d="m8 8-4 4 4 4M16 8l4 4-4 4M13.5 5l-3 14"/>',
        'network' => '<circle cx="12" cy="5" r="2.5"/><circle cx="5" cy="19" r="2.5"/><circle cx="19" cy="19" r="2.5"/><path d="M12 7.5V13M12 13l-5.5 4M12 13l5.5 4"/>',
        'ai' => '<path d="M12 3l1.8 4.6L18.5 9l-4.7 1.4L12 15l-1.8-4.6L5.5 9l4.7-1.4z"/><path d="M18 15l.8 2.2L21 18l-2.2.8L18 21l-.8-2.2L15 18l2.2-.8z"/>',
        'device' => '<rect x="3" y="5" width="18" height="12" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3.5 3 14.5 0 18M12 3c-3 3.5-3 14.5 0 18"/>',
        'layers' => '<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/>',
        'book' => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 21V5M9 7h6"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
    ];
    return '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . ($paths[$name] ?? $paths['chip']) . '</svg>';
}
