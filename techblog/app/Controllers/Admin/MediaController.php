<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Audit;
use App\Core\Auth;
use App\Core\Database as DB;
use App\Core\Request;
use App\Core\Response;
use App\Models\Media;
use App\Services\ImageProcessor;
use App\Services\Sanitizer;

final class MediaController extends AdminBase
{
    public function index(array $p): Response
    {
        $page = max(1, Request::int('page', 1));
        $total = (int)DB::val('SELECT COUNT(*) FROM media');
        return $this->view('media/index', 'Medya kütüphanesi', ['items' => Media::page(24, ($page - 1) * 24), 'page' => $page, 'pages' => (int)ceil($total / 24), 'total' => $total]);
    }

    /** JSON list for the editor's image picker. */
    public function list(array $p): Response
    {
        $out = [];
        foreach (Media::page(60, 0) as $m) {
            $v = Media::variants($m);
            $out[] = ['id' => (int)$m['id'], 'url' => $m['path'], 'thumb' => $v['webp'][0]['path'] ?? $m['path'], 'alt' => $m['alt'], 'width' => (int)$m['width'], 'height' => (int)$m['height']];
        }
        return Response::json(['ok' => true, 'items' => $out]);
    }

    public function upload(array $p): Response
    {
        // When the request body exceeds post_max_size PHP silently drops $_POST and $_FILES.
        if (!$_POST && !$_FILES && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
            return Response::json(['ok' => false, 'error' => 'Dosya, sunucunun izin verdiği yükleme boyutunu aşıyor (PHP post_max_size: ' . ini_get('post_max_size') . ').'], 413);
        }
        $alt = Sanitizer::text(Request::str('alt', 300));
        if (mb_strlen($alt) < 3) {
            return Response::json(['ok' => false, 'error' => 'Alternatif metin zorunludur (en az 3 karakter).'], 422);
        }
        $file = $_FILES['file'] ?? null;
        if (!is_array($file) || is_array($file['name'] ?? null)) {
            return Response::json(['ok' => false, 'error' => 'Dosya alınamadı.'], 422);
        }
        try {
            $info = (new ImageProcessor())->store($file);
        } catch (\RuntimeException $e) {
            return Response::json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
        $id = DB::insert('media', [
            'user_id' => Auth::id(), 'original_name' => $info['original_name'], 'path' => $info['path'], 'mime' => $info['mime'],
            'width' => $info['width'], 'height' => $info['height'], 'size' => $info['size'], 'alt' => $alt,
            'variants' => json_encode($info['variants'], JSON_UNESCAPED_SLASHES), 'created_at' => now(),
        ]);
        Audit::log('media.upload', 'media', $id, ['name' => $info['original_name']]);
        return Response::json(['ok' => true, 'message' => 'Görsel yüklendi.', 'item' => [
            'id' => $id, 'url' => $info['path'], 'thumb' => $info['variants']['webp'][0]['path'] ?? $info['path'],
            'alt' => $alt, 'width' => $info['width'], 'height' => $info['height'],
        ]]);
    }

    public function update(array $p): Response
    {
        $m = Media::find((int)$p['id']);
        if (!$m) {
            return App::notFound();
        }
        $alt = Sanitizer::text(Request::str('alt', 300));
        if (mb_strlen($alt) < 3) {
            return $this->done('Alternatif metin zorunludur (en az 3 karakter).', '/admin/media', false);
        }
        DB::update('media', ['alt' => $alt], (int)$m['id']);
        $this->contentChanged();
        Audit::log('media.update', 'media', (int)$m['id']);
        return $this->done('Alternatif metin güncellendi.', '/admin/media');
    }

    public function delete(array $p): Response
    {
        $m = Media::find((int)$p['id']);
        if (!$m) {
            return App::notFound();
        }
        Media::delete($m);
        $this->contentChanged();
        Audit::log('media.delete', 'media', (int)$m['id'], ['name' => $m['original_name']]);
        return $this->done('Görsel silindi.', '/admin/media');
    }
}
