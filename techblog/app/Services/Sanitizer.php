<?php
declare(strict_types=1);

namespace App\Services;

use enshrined\svgSanitize\Sanitizer as SvgSanitizer;
use HTMLPurifier;
use HTMLPurifier_Config;

/** Whitelist-based HTML and SVG sanitizing for editor content and uploads. */
final class Sanitizer
{
    private static ?HTMLPurifier $inline = null;

    /** Inline rich text: bold, italic, links, inline code and a few semantic tags. */
    public static function inline(string $html): string
    {
        if ($html === '') {
            return '';
        }
        if (self::$inline === null) {
            $cfg = HTMLPurifier_Config::createDefault();
            $cacheDir = storage_path('cache/purifier');
            if (is_dir($cacheDir) && is_writable($cacheDir)) {
                $cfg->set('Cache.SerializerPath', $cacheDir);
            } else {
                $cfg->set('Cache.DefinitionImpl', null);
            }
            $cfg->set('HTML.DefinitionID', 'techblog-inline');
            $cfg->set('HTML.DefinitionRev', 2);
            $cfg->set('HTML.Allowed', 'b,strong,i,em,u,s,a[href|title],code,kbd,mark,br,sub,sup,abbr[title]');
            $cfg->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);
            $cfg->set('HTML.TargetBlank', true);
            $cfg->set('HTML.TargetNoopener', true);
            $cfg->set('HTML.TargetNoreferrer', true);
            $cfg->set('AutoFormat.RemoveEmpty', false);
            if ($def = $cfg->maybeGetRawHTMLDefinition()) {
                $def->addElement('mark', 'Inline', 'Inline', 'Common');
            }
            self::$inline = new HTMLPurifier($cfg);
        }
        return self::$inline->purify($html);
    }

    /** Plain text only: strips every tag. */
    public static function text(string $value, int $max = 0): string
    {
        $value = trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', '', $value) ?? '';
        return $max > 0 ? mb_substr($value, 0, $max) : $value;
    }

    /** Sanitize SVG markup (scripts, event handlers, remote references and foreignObject are removed). */
    public static function svg(string $svg): string
    {
        $svg = trim($svg);
        if ($svg === '' || stripos($svg, '<svg') === false) {
            return '';
        }
        $s = new SvgSanitizer();
        $s->removeRemoteReferences(true);
        $s->removeXMLTag(true);
        $s->minify(true);
        $clean = $s->sanitize($svg);
        if (!is_string($clean) || stripos($clean, '<svg') === false) {
            return '';
        }
        return trim(preg_replace('/<!DOCTYPE[^>]*>/i', '', $clean) ?? '');
    }

    /** Accept only local paths under /uploads or /assets. */
    public static function localPath(string $path): string
    {
        $path = trim($path);
        if ($path === '' || !preg_match('#^/(uploads|assets)/[A-Za-z0-9/_.\-]+$#', $path) || str_contains($path, '..')) {
            return '';
        }
        return $path;
    }

    /** Accept only absolute http(s) URLs. */
    public static function url(string $url): string
    {
        $url = trim($url);
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $url)) {
            return '';
        }
        return mb_substr($url, 0, 500);
    }
}
