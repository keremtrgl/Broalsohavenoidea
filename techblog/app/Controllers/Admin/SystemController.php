<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Audit;
use App\Core\Cache;
use App\Core\Database as DB;
use App\Core\PageCache;
use App\Core\Request;
use App\Core\Response;
use App\Services\Backup;
use App\Services\Seo;

final class SystemController extends AdminBase
{
    /** @return list<array{name:string,size:int,mtime:int}> */
    private function logFiles(): array
    {
        $out = [];
        foreach (glob(storage_path('logs/*.log')) ?: [] as $f) {
            $out[] = ['name' => basename($f), 'size' => (int)filesize($f), 'mtime' => (int)filemtime($f)];
        }
        usort($out, static fn($a, $b) => $b['mtime'] <=> $a['mtime']);
        return array_slice($out, 0, 30);
    }

    public function index(array $p): Response
    {
        return $this->view('system', 'Sistem', [
            'info' => [
                'PHP sürümü' => PHP_VERSION,
                'Veritabanı' => (string)DB::val('SELECT VERSION()'),
                'Önbellekteki sayfa' => (string)count(glob(storage_path('cache/pages/*.html')) ?: []),
                'Veri önbelleği girdisi' => (string)count(glob(storage_path('cache/data/*.php')) ?: []),
                'AVIF desteği (GD)' => function_exists('imageavif') ? 'var' : 'yok (yalnızca WebP üretilir)',
                'Hata gösterimi' => env('APP_DEBUG', false) ? 'AÇIK — üretimde kapatın' : 'kapalı (hatalar günlüğe yazılır)',
                'Kurulum sihirbazı' => is_file(public_path('install.php')) ? 'DOSYA DURUYOR — kilitli, yine de silmeniz önerilir' : 'silinmiş',
            ],
            'logs' => $this->logFiles(),
        ]);
    }

    public function clearCache(array $p): Response
    {
        $n = PageCache::flush() + Cache::flush();
        Audit::log('system.cache_clear', 'system', null, ['files' => $n]);
        return $this->done("Önbellek temizlendi ($n dosya).", '/admin/system');
    }

    public function sitemap(array $p): Response
    {
        $xml = Seo::sitemap(true);
        Cache::forget('rss_xml');
        Audit::log('system.sitemap', 'system');
        return $this->done('Site haritası yeniden üretildi (' . substr_count($xml, '<url>') . ' adres).', '/admin/system');
    }

    public function backup(array $p): Response
    {
        $file = Backup::create();
        Audit::log('system.backup', 'system', null, ['size' => filesize($file)]);
        return new Response('', 200, [
            'Content-Type' => 'application/gzip',
            'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
            'Content-Length' => (string)filesize($file),
            'Cache-Control' => 'no-store',
        ], false, static function () use ($file): void {
            readfile($file);
            @unlink($file);
        });
    }

    public function log(array $p): Response
    {
        $name = basename(Request::str('file', 80));
        $names = array_column($this->logFiles(), 'name');
        if (!in_array($name, $names, true)) {
            return $this->done('Günlük dosyası bulunamadı.', '/admin/system', false);
        }
        $path = storage_path('logs/' . $name);
        $size = (int)filesize($path);
        $fh = fopen($path, 'rb');
        fseek($fh, max(0, $size - 60000));
        $tail = (string)stream_get_contents($fh);
        fclose($fh);
        return $this->view('log', 'Günlük: ' . $name, ['name' => $name, 'tail' => $tail, 'truncated' => $size > 60000, 'active' => 'system']);
    }

    public function audit(array $p): Response
    {
        $page = max(1, Request::int('page', 1));
        $total = (int)DB::val('SELECT COUNT(*) FROM audit_log');
        $rows = DB::all('SELECT a.*, u.name AS user_name, u.email AS user_email FROM audit_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT 50 OFFSET ?', [($page - 1) * 50]);
        return $this->view('audit', 'Denetim günlüğü', ['rows' => $rows, 'page' => $page, 'pages' => (int)ceil($total / 50)]);
    }
}
