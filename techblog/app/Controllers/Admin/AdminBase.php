<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Cache;
use App\Core\Csp;
use App\Core\PageCache;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

/** Shared helpers for admin controllers. */
abstract class AdminBase
{
    /** @param array<string,mixed> $data */
    protected function view(string $template, string $title, array $data = []): Response
    {
        // Editor.js and its plugins inject <style> elements at runtime, so the panel allows inline styles.
        // Scripts stay restricted to same-origin files.
        Csp::add('style-src', "'unsafe-inline'");
        return Response::html(View::render('admin/' . $template, $data + ['pageTitle' => $title, 'active' => explode('/', $template)[0]], 'layouts/admin'));
    }

    /** Finish a form action: JSON for fetch() callers, flash + redirect otherwise. */
    protected function done(string $message, string $redirect, bool $ok = true, array $extra = []): Response
    {
        if (Request::wantsJson()) {
            return Response::json(['ok' => $ok, $ok ? 'message' : 'error' => $message] + $extra, $ok ? 200 : 422);
        }
        Session::flash($ok ? 'success' : 'error', $message);
        return Response::redirect($redirect);
    }

    /** Content changed: drop cached pages and feeds. */
    protected function contentChanged(): void
    {
        PageCache::flush();
        Cache::forget('sitemap_xml');
        Cache::forget('rss_xml');
    }
}
