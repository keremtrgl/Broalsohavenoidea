<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Validates uploads and builds responsive WebP/AVIF variants with GD.
 * Whitelist: jpg, png, webp, avif, svg. Files get random names; SVG is sanitized.
 */
final class ImageProcessor
{
    public const MAX_BYTES = 8 * 1024 * 1024;
    public const MAX_PIXELS = 40_000_000;
    private const WIDTHS = [480, 800, 1200, 1600];
    private const TYPES = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/webp' => ['webp'],
        'image/avif' => ['avif'],
        'image/svg+xml' => ['svg'],
    ];

    /**
     * @param array{name:string,tmp_name:string,error:int,size:int} $file one entry of $_FILES
     * @return array{path:string,mime:string,width:int,height:int,size:int,variants:array<string,list<array{w:int,path:string}>>,original_name:string}
     * @throws \RuntimeException with a user-facing (Turkish) message
     */
    public function store(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('Dosya yüklenemedi (hata kodu ' . (int)($file['error'] ?? -1) . ').');
        }
        if ($file['size'] <= 0 || $file['size'] > self::MAX_BYTES) {
            throw new \RuntimeException('Dosya boyutu en fazla ' . (self::MAX_BYTES / 1048576) . ' MB olabilir.');
        }
        $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
        $mime = (string)(new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if ($mime === 'text/plain' || $mime === 'text/xml' || $mime === 'application/xml' || $mime === 'text/html') {
            // libmagic reports some SVG files as text; accept only when the extension says svg and the content parses as SVG.
            $mime = $ext === 'svg' ? 'image/svg+xml' : $mime;
        }
        if (!isset(self::TYPES[$mime]) || !in_array($ext, self::TYPES[$mime], true)) {
            throw new \RuntimeException('İzin verilen türler: JPG, PNG, WebP, AVIF ve SVG. Dosya uzantısı içerikle eşleşmelidir.');
        }

        $sub = date('Y/m');
        $dir = public_path('uploads/' . $sub);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new \RuntimeException('Yükleme klasörü oluşturulamadı.');
        }
        $name = bin2hex(random_bytes(16));
        $original = Sanitizer::text(basename((string)$file['name']), 200);

        if ($mime === 'image/svg+xml') {
            $clean = Sanitizer::svg((string)file_get_contents($file['tmp_name']));
            if ($clean === '') {
                throw new \RuntimeException('SVG dosyası geçersiz veya güvenli değil.');
            }
            $rel = "/uploads/$sub/$name.svg";
            file_put_contents(public_path(ltrim($rel, '/')), $clean, LOCK_EX);
            [$w, $h] = $this->svgSize($clean);
            return ['path' => $rel, 'mime' => $mime, 'width' => $w, 'height' => $h, 'size' => strlen($clean), 'variants' => [], 'original_name' => $original];
        }

        $info = @getimagesize($file['tmp_name']);
        if (!$info || $info[0] < 1 || $info[1] < 1) {
            throw new \RuntimeException('Görsel okunamadı; dosya bozuk olabilir.');
        }
        if ($info[0] * $info[1] > self::MAX_PIXELS) {
            throw new \RuntimeException('Görsel çok büyük (en fazla 40 megapiksel).');
        }
        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file['tmp_name']),
            'image/png' => @imagecreatefrompng($file['tmp_name']),
            'image/webp' => @imagecreatefromwebp($file['tmp_name']),
            'image/avif' => function_exists('imagecreatefromavif') ? @imagecreatefromavif($file['tmp_name']) : false,
        };
        if (!$src) {
            throw new \RuntimeException('Bu görsel biçimi sunucuda işlenemiyor.');
        }
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $src = $this->fixOrientation($src, $file['tmp_name']);
        }
        imagepalettetotruecolor($src);
        imagealphablending($src, true);
        imagesavealpha($src, true);
        $w = imagesx($src);
        $h = imagesy($src);

        // Re-encoding drops EXIF/GPS metadata and any payload hidden in the original file.
        $variants = [];
        $widths = array_values(array_filter(self::WIDTHS, static fn($x) => $x < $w));
        $widths[] = min($w, 2000);
        foreach (array_unique($widths) as $tw) {
            $th = (int)round($h * $tw / $w);
            $dst = imagecreatetruecolor($tw, $th);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            $relW = "/uploads/$sub/$name-$tw.webp";
            if (imagewebp($dst, public_path(ltrim($relW, '/')), 82)) {
                $variants['webp'][] = ['w' => $tw, 'path' => $relW];
            }
            if (function_exists('imageavif')) {
                $relA = "/uploads/$sub/$name-$tw.avif";
                if (@imageavif($dst, public_path(ltrim($relA, '/')), 60) && filesize(public_path(ltrim($relA, '/'))) > 0) {
                    $variants['avif'][] = ['w' => $tw, 'path' => $relA];
                } else {
                    @unlink(public_path(ltrim($relA, '/')));
                }
            }
            imagedestroy($dst);
        }
        imagedestroy($src);
        if (empty($variants['webp'])) {
            throw new \RuntimeException('Görsel WebP biçimine dönüştürülemedi.');
        }
        $main = end($variants['webp']);
        $mw = (int)$main['w'];
        return [
            'path' => $main['path'], 'mime' => 'image/webp', 'width' => $mw, 'height' => (int)round($h * $mw / $w),
            'size' => (int)filesize(public_path(ltrim($main['path'], '/'))), 'variants' => $variants, 'original_name' => $original,
        ];
    }

    private function fixOrientation(\GdImage $img, string $file): \GdImage
    {
        $exif = @exif_read_data($file);
        $angle = match ((int)($exif['Orientation'] ?? 1)) {
            3 => 180, 6 => -90, 8 => 90, default => 0,
        };
        if ($angle !== 0) {
            $rot = imagerotate($img, $angle, 0);
            if ($rot) {
                imagedestroy($img);
                return $rot;
            }
        }
        return $img;
    }

    /** @return array{0:int,1:int} */
    private function svgSize(string $svg): array
    {
        if (preg_match('/viewBox="\s*[\d.\-]+[\s,]+[\d.\-]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $svg, $m)) {
            return [(int)round((float)$m[1]), (int)round((float)$m[2])];
        }
        if (preg_match('/<svg[^>]*\swidth="([\d.]+)/i', $svg, $mw) && preg_match('/<svg[^>]*\sheight="([\d.]+)/i', $svg, $mh)) {
            return [(int)round((float)$mw[1]), (int)round((float)$mh[1])];
        }
        return [0, 0];
    }
}
