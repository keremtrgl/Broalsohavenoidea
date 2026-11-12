<?php
declare(strict_types=1);

/**
 * Installation wizard.
 *  1. checks server requirements,
 *  2. collects the database and Firebase settings and writes .env (outside the web root),
 *  3. runs the migrations and seeds (settings, categories and the 15 articles),
 *  4. registers the first administrator's e-mail address,
 *  5. locks itself (storage/installed.lock) and tries to delete this file.
 */

define('TB_PUBLIC', __DIR__);
define('TB_BASE', is_file(__DIR__ . '/tb-path.php') ? rtrim((string)require __DIR__ . '/tb-path.php', '/') : dirname(__DIR__));

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");

$h = static fn(mixed $v): string => htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$lock = TB_BASE . '/storage/installed.lock';

$page = static function (string $title, string $body) use ($h): never {
    echo '<!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<meta name="robots" content="noindex, nofollow"><title>' . $h($title) . ' — TechBlog kurulumu</title>'
        . '<link rel="stylesheet" href="/assets/css/install.css"></head><body><main><p class="brand">TechBlog kurulumu</p><h1>' . $h($title) . '</h1>' . $body . '</main></body></html>';
    exit;
};

// ---- Already installed: the wizard is locked for good. ----
if (is_file($lock)) {
    http_response_code(403);
    $page('Kurulum kilitli', '<p class="box err">Site zaten kurulmuş. Bu sihirbaz güvenlik nedeniyle kilitlendi ve yeniden çalıştırılamaz.</p>'
        . '<p>Lütfen <code>public_html/install.php</code> dosyasını sunucudan silin. Yeniden kurmak isterseniz <code>storage/installed.lock</code> ve <code>.env</code> dosyalarını silmeniz gerekir.</p>'
        . '<p><a class="btn" href="/">Siteye git</a></p>');
}

// ---- Step 1: requirements ----
$writable = static function (string $path): bool {
    return is_dir($path) ? is_writable($path) : (is_dir(dirname($path)) && is_writable(dirname($path)));
};
$checks = [
    ['PHP 8.2 veya üzeri (şu an: ' . PHP_VERSION . ')', version_compare(PHP_VERSION, '8.2.0', '>='), true],
    ['vendor/ klasörü yüklenmiş', is_file(TB_BASE . '/vendor/autoload.php'), true],
];
foreach (['pdo_mysql', 'mbstring', 'json', 'openssl', 'curl', 'gd', 'fileinfo', 'dom', 'ctype', 'iconv'] as $ext) {
    $checks[] = ["PHP eklentisi: $ext", extension_loaded($ext), true];
}
$checks[] = ['GD: WebP desteği', function_exists('imagewebp'), true];
$checks[] = ['GD: AVIF desteği (isteğe bağlı; yoksa yalnızca WebP üretilir)', function_exists('imageavif'), false];
$checks[] = ['PHP eklentisi: exif (isteğe bağlı; fotoğraf yönünü düzeltir)', extension_loaded('exif'), false];
$checks[] = ['PHP eklentisi: zlib (isteğe bağlı; veritabanı yedeği için gerekir)', extension_loaded('zlib'), false];
foreach (['storage/logs', 'storage/cache/pages', 'storage/cache/data', 'storage/cache/purifier', 'storage/sessions', 'storage/backups', 'data'] as $dir) {
    $checks[] = ["Yazılabilir klasör: $dir", $writable(TB_BASE . '/' . $dir), true];
}
$checks[] = ['Yazılabilir klasör: public_html/uploads', $writable(TB_PUBLIC . '/uploads'), true];
$checks[] = ['.env dosyası yazılabilir (' . $h(TB_BASE) . ')', is_file(TB_BASE . '/.env') ? is_writable(TB_BASE . '/.env') : is_writable(TB_BASE), true];
$checks[] = ['Uygulama klasörü web kökünün dışında (önerilen düzen)', !str_starts_with(TB_BASE . '/', TB_PUBLIC . '/'), false];
$ready = !array_filter($checks, static fn($c) => $c[2] && !$c[1]);

$list = '<ul class="checks">';
foreach ($checks as [$label, $ok, $required]) {
    $list .= '<li class="' . ($ok ? 'ok' : ($required ? 'bad' : 'warn')) . '"><span>' . ($ok ? 'TAMAM' : ($required ? 'EKSİK' : 'UYARI')) . '</span> ' . $label . '</li>';
}
$list .= '</ul>';

if (!$ready) {
    $page('Sunucu gereksinimleri', '<p class="box err">Kuruluma devam etmek için “EKSİK” satırlarını giderin (cPanel → “Select PHP Version” ve Dosya Yöneticisi izinleri), sonra sayfayı yenileyin.</p>' . $list);
}

require TB_BASE . '/vendor/autoload.php';

use App\Core\Cache;
use App\Core\Database;
use App\Core\Env;
use App\Core\Migrator;
use App\Services\Seeder;

// A small private session only for the installer's CSRF token.
session_name('tbinstall');
session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Strict', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
if (is_writable(TB_BASE . '/storage/sessions')) {
    session_save_path(TB_BASE . '/storage/sessions');
}
session_start();
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' ? 'https' : 'http';
$in = [
    'app_url' => $scheme . '://' . preg_replace('/[^A-Za-z0-9.\-:]/', '', (string)($_SERVER['HTTP_HOST'] ?? 'localhost')),
    'site_name' => 'TechBlog', 'db_host' => 'localhost', 'db_port' => '3306', 'db_name' => '', 'db_user' => '', 'db_pass' => '',
    'fb_api_key' => '', 'fb_auth_domain' => '', 'fb_project_id' => '', 'fb_app_id' => '', 'admin_email' => '',
];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    foreach ($in as $k => $_) {
        $in[$k] = trim(str_replace(["\0", "\r", "\n"], '', (string)($_POST[$k] ?? '')));
    }
    if (!hash_equals($_SESSION['csrf'], (string)($_POST['_csrf'] ?? ''))) {
        $errors[] = 'Oturum doğrulaması başarısız oldu. Sayfayı yenileyip tekrar deneyin.';
    }
    $in['app_url'] = rtrim($in['app_url'], '/');
    if (!preg_match('#^https?://[A-Za-z0-9.\-]+(:\d+)?$#', $in['app_url'])) {
        $errors[] = 'Site adresi https://alanadiniz.com biçiminde olmalı (sonunda / olmadan).';
    }
    if ($in['site_name'] === '' || mb_strlen($in['site_name']) > 80) {
        $errors[] = 'Site adı 1–80 karakter olmalı.';
    }
    if (!preg_match('/^[A-Za-z0-9.\-_]+$/', $in['db_host']) || !ctype_digit($in['db_port'])) {
        $errors[] = 'Veritabanı sunucusu veya portu geçersiz.';
    }
    if (!preg_match('/^[A-Za-z0-9_\-$]+$/', $in['db_name']) || $in['db_user'] === '') {
        $errors[] = 'Veritabanı adı ve kullanıcı adı zorunludur.';
    }
    if (!filter_var($in['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Yönetici e-posta adresi geçersiz.';
    }
    if (!preg_match('/^[A-Za-z0-9_\-]{20,}$/', $in['fb_api_key'])) {
        $errors[] = 'Firebase apiKey geçersiz görünüyor.';
    }
    if (!preg_match('/^[a-z0-9.\-]+\.[a-z]{2,}$/i', $in['fb_auth_domain'])) {
        $errors[] = 'Firebase authDomain “proje-kimligi.firebaseapp.com” biçiminde olmalı.';
    }
    if (!preg_match('/^[a-z0-9\-]{4,40}$/', $in['fb_project_id'])) {
        $errors[] = 'Firebase projectId geçersiz (küçük harf, rakam ve tire).';
    }
    if (!preg_match('/^[0-9]:[0-9]+:web:[a-f0-9]+$/', $in['fb_app_id'])) {
        $errors[] = 'Firebase appId “1:1234567890:web:abcdef…” biçiminde olmalı.';
    }

    if (!$errors) {
        try {
            Database::connect(['host' => $in['db_host'], 'port' => $in['db_port'], 'name' => $in['db_name'], 'user' => $in['db_user'], 'pass' => $in['db_pass']]);
        } catch (Throwable $e) {
            $errors[] = 'Veritabanına bağlanılamadı. Sunucu, veritabanı adı, kullanıcı adı ve parolayı kontrol edin. (' . $h(preg_replace('/\s+/', ' ', $e->getMessage())) . ')';
        }
    }

    if (!$errors) {
        $envWritten = Env::write(TB_BASE . '/.env', [
            'APP_ENV' => 'production', 'APP_DEBUG' => false, 'APP_URL' => $in['app_url'], 'APP_TIMEZONE' => 'Europe/Istanbul',
            'APP_KEY' => bin2hex(random_bytes(32)), 'TRUST_PROXY' => false, 'PAGE_CACHE' => true,
            'DB_HOST' => $in['db_host'], 'DB_PORT' => $in['db_port'], 'DB_NAME' => $in['db_name'], 'DB_USER' => $in['db_user'], 'DB_PASS' => $in['db_pass'],
            'FIREBASE_API_KEY' => $in['fb_api_key'], 'FIREBASE_AUTH_DOMAIN' => $in['fb_auth_domain'], 'FIREBASE_PROJECT_ID' => $in['fb_project_id'], 'FIREBASE_APP_ID' => $in['fb_app_id'],
            'ADMIN_EMAIL' => mb_strtolower($in['admin_email']),
        ]);
        if (!$envWritten) {
            $errors[] = '.env dosyası yazılamadı: ' . $h(TB_BASE . '/.env');
        }
    }

    if (!$errors) {
        try {
            Env::load(TB_BASE . '/.env');
            date_default_timezone_set('Europe/Istanbul');
            Cache::flush();
            $migrations = Migrator::migrate();
            $seed = Seeder::run(['site_name' => $in['site_name'], 'contact_email' => mb_strtolower($in['admin_email'])]);
            file_put_contents($lock, 'Installed at ' . date('c') . "\n", LOCK_EX);
            $_SESSION = [];
            session_destroy();
            $deleted = @unlink(__FILE__);

            $body = '<p class="box ok">Kurulum tamamlandı. ' . count($migrations) . ' migration çalıştırıldı, ' . (int)$seed['posts'] . ' yazı ve ' . (int)$seed['categories'] . ' kategori yüklendi.</p>'
                . '<h2>Son adımlar</h2><ol>'
                . '<li>' . ($deleted ? '<code>install.php</code> dosyası kendiliğinden silindi.' : '<strong><code>public_html/install.php</code> dosyası silinemedi; lütfen elle silin.</strong> (Sihirbaz kilitlendi, yeniden çalıştırılamaz.)') . '</li>'
                . '<li>Firebase konsolunda <em>Authentication → Settings → Authorized domains</em> listesine <code>' . $h((string)parse_url($in['app_url'], PHP_URL_HOST)) . '</code> alan adını ekleyin.</li>'
                . '<li><a href="/login?next=/admin">Giriş sayfasından</a> <code>' . $h($in['admin_email']) . '</code> adresiyle (Google ile ya da e-postası doğrulanmış bir hesapla) giriş yapın; bu hesap kendiliğinden yönetici olur.</li>'
                . '<li>SSL etkinse <code>public_html/.htaccess</code> içindeki <code>TB_HTTPS</code> ve <code>TB_CANON</code> ayarlarını kontrol edin.</li>'
                . '</ol><p><a class="btn" href="/">Siteyi aç</a></p>';
            $page('Kurulum tamamlandı', $body);
        } catch (Throwable $e) {
            @unlink(TB_BASE . '/.env');
            error_log('[techblog install] ' . $e);
            $errors[] = 'Kurulum sırasında hata oluştu: ' . $h($e->getMessage());
        }
    }
}

$field = static function (string $name, string $label, string $type = 'text', string $help = '', bool $required = true) use (&$in, $h): string {
    $value = $type === 'password' ? '' : $in[$name];
    return '<div class="field"><label for="' . $name . '">' . $label . '</label><input type="' . $type . '" id="' . $name . '" name="' . $name . '" value="' . $h($value) . '"'
        . ($required ? ' required' : '') . ' autocomplete="off" spellcheck="false">' . ($help !== '' ? '<small>' . $help . '</small>' : '') . '</div>';
};

$body = '';
if ($errors) {
    $body .= '<div class="box err" role="alert"><strong>Lütfen düzeltin:</strong><ul><li>' . implode('</li><li>', $errors) . '</li></ul></div>';
}
$body .= '<details' . ($errors ? '' : ' open') . '><summary>1. Sunucu gereksinimleri — tümü karşılanıyor</summary>' . $list . '</details>'
    . '<form method="post" action="/install.php" novalidate><input type="hidden" name="_csrf" value="' . $h($_SESSION['csrf']) . '">'
    . '<h2>2. Site</h2>'
    . $field('app_url', 'Site adresi', 'url', 'SSL kuruluysa https:// ile yazın. Sonunda / olmamalı.')
    . $field('site_name', 'Site adı')
    . '<h2>3. Veritabanı (MySQL / MariaDB)</h2><p class="hint">cPanel → “MySQL® Databases” bölümünde oluşturduğunuz veritabanı ve kullanıcı. Kullanıcıya veritabanı üzerinde “ALL PRIVILEGES” verin.</p>'
    . $field('db_host', 'Sunucu', 'text', 'Paylaşımlı barındırmada çoğunlukla “localhost”.')
    . $field('db_port', 'Port')
    . $field('db_name', 'Veritabanı adı', 'text', 'Örnek: kullanici_techblog')
    . $field('db_user', 'Kullanıcı adı')
    . $field('db_pass', 'Parola', 'password', '', false)
    . '<h2>4. Firebase Authentication</h2><p class="hint">Firebase konsolu → Project settings → “Your apps” → Web app yapılandırmasındaki değerler. Hizmet hesabı anahtarı gerekmez: kimlik belirteçleri Google’ın açık anahtarlarıyla sunucuda doğrulanır.</p>'
    . $field('fb_api_key', 'apiKey')
    . $field('fb_auth_domain', 'authDomain', 'text', 'Örnek: proje-kimligi.firebaseapp.com')
    . $field('fb_project_id', 'projectId')
    . $field('fb_app_id', 'appId')
    . '<h2>5. İlk yönetici</h2>'
    . $field('admin_email', 'Yöneticinin e-posta adresi', 'email', 'Bu adresle giriş yapan ve e-postası doğrulanmış hesap yönetici olur.')
    . '<p><button class="btn" type="submit">Kurulumu başlat</button></p>'
    . '<p class="hint">Bu işlem .env dosyasını yazar, tabloları oluşturur, 15 yazıyı yükler ve sihirbazı kilitler.</p></form>';

$page('Kurulum sihirbazı', $body);
