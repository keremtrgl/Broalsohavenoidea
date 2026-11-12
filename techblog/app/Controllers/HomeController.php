<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Csrf;
use App\Core\Database as DB;
use App\Core\Logger;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Models\Post;
use App\Models\Taxonomy;
use App\Services\Sanitizer;
use App\Services\Seo;

final class HomeController
{
    private const PER_PAGE = 9;

    public function index(array $p): Response
    {
        $featured = Post::featured(3);
        $seriesName = (string)setting('home_series', 'Every X Explained');
        return Response::html(View::render('public/home', [
            'featured' => $featured,
            'latest' => Post::latest(6),
            'categories' => array_values(array_filter(Taxonomy::all('category'), static fn($c) => (int)$c['post_count'] > 0)),
            'seriesName' => $seriesName,
            'series' => Post::series($seriesName),
            'lcpImage' => $featured[0]['cover_image'] ?? '',
            'seo' => [
                'title' => (string)setting('site_name', 'TechBlog') . ' — ' . (string)setting('site_tagline', 'Teknolojiyi sade bir dille anlatan rehberler'),
                'description' => (string)setting('site_description', ''),
                'canonical' => url('/'),
                'jsonld' => [Seo::website()],
                'raw_title' => true,
            ],
        ]))->cache();
    }

    /** @return array{0:int,1:int} page number and offset */
    private function paging(): array
    {
        $page = max(1, min(500, Request::int('page', 1)));
        return [$page, ($page - 1) * self::PER_PAGE];
    }

    private function listing(string $title, string $lead, array $posts, int $total, int $page, string $basePath, array $crumbs, string $kicker = ''): Response
    {
        if ($page > 1 && !$posts) {
            return App::notFound();
        }
        $pages = (int)ceil($total / self::PER_PAGE);
        $canonical = url($basePath) . ($page > 1 ? '?page=' . $page : '');
        $bc = [['Ana sayfa', url('/')]];
        foreach ($crumbs as $c) {
            $bc[] = $c;
        }
        return Response::html(View::render('public/list', [
            'title' => $title, 'lead' => $lead, 'kicker' => $kicker, 'posts' => $posts, 'total' => $total,
            'page' => $page, 'pages' => $pages, 'basePath' => $basePath, 'crumbs' => $bc,
            'seo' => [
                'title' => $title . ($page > 1 ? ' — Sayfa ' . $page : ''),
                'description' => $lead !== '' ? $lead : $title . ' konusundaki tüm yazılar.',
                'canonical' => $canonical,
                'jsonld' => [Seo::breadcrumbs($bc)],
            ],
        ]))->cache();
    }

    public function archive(array $p): Response
    {
        [$page, $offset] = $this->paging();
        return $this->listing('Tüm yazılar', 'Yayımlanan bütün rehberler, en yeniden en eskiye.', Post::latest(self::PER_PAGE, $offset), Post::countLive(), $page, '/archive', [['Tüm yazılar', url('/archive')]]);
    }

    public function category(array $p): Response
    {
        $cat = Taxonomy::bySlug('category', $p['slug']);
        if (!$cat) {
            return App::notFound();
        }
        [$page, $offset] = $this->paging();
        return $this->listing((string)$cat['name'], (string)$cat['description'],
            Post::latest(self::PER_PAGE, $offset, 'p.category_id = ?', [(int)$cat['id']]),
            Post::countLive('p.category_id = ?', [(int)$cat['id']]), $page, '/category/' . $cat['slug'],
            [[(string)$cat['name'], url('/category/' . $cat['slug'])]], 'Kategori');
    }

    public function tag(array $p): Response
    {
        $tag = Taxonomy::bySlug('tag', $p['slug']);
        if (!$tag) {
            return App::notFound();
        }
        [$page, $offset] = $this->paging();
        return $this->listing('#' . $tag['name'], '“' . $tag['name'] . '” etiketiyle işaretlenen yazılar.',
            Post::byTag((int)$tag['id'], self::PER_PAGE, $offset), Post::countByTag((int)$tag['id']), $page, '/tag/' . $tag['slug'],
            [['#' . $tag['name'], url('/tag/' . $tag['slug'])]], 'Etiket');
    }

    public function series(array $p): Response
    {
        $name = null;
        foreach (DB::all("SELECT DISTINCT series FROM posts WHERE series <> ''") as $row) {
            if (slugify((string)$row['series']) === $p['slug']) {
                $name = (string)$row['series'];
                break;
            }
        }
        $posts = $name !== null ? Post::series($name) : [];
        if (!$posts) {
            return App::notFound();
        }
        return $this->listing($name . ' serisi', 'Bir konudaki bütün kavramları tek yazıda, baştan sona açıklayan rehber serisi.', $posts, count($posts), 1,
            '/series/' . $p['slug'], [[$name, url('/series/' . $p['slug'])]], 'Seri');
    }

    public function search(array $p): Response
    {
        $q = Request::str('q', 80);
        $results = $q !== '' ? Post::search($q, 30) : [];
        return Response::html(View::render('public/search', [
            'q' => $q, 'results' => $results,
            'seo' => ['title' => $q !== '' ? '“' . $q . '” için arama sonuçları' : 'Arama', 'robots' => 'noindex, follow', 'canonical' => url('/search')],
        ]));
    }

    public function about(array $p): Response
    {
        return Response::html(View::render('public/about', [
            'seo' => ['title' => 'Hakkında', 'description' => (string)setting('site_description', ''), 'canonical' => url('/about')],
        ]))->cache();
    }

    public function privacy(array $p): Response
    {
        return Response::html(View::render('public/privacy', [
            'seo' => ['title' => 'Gizlilik ve çerez politikası', 'description' => 'Hangi verileri neden işlediğimizi, çerez tercihlerinizi ve KVKK/GDPR kapsamındaki haklarınızı açıklar.', 'canonical' => url('/privacy')],
        ]))->cache();
    }

    public function contact(array $p): Response
    {
        Csrf::token();
        return Response::html(View::render('public/contact', [
            'errors' => [], 'old' => [], 'sent' => (bool)Session::get('contact_sent', false),
            'seo' => ['title' => 'İletişim', 'description' => 'Öneri, düzeltme ve iş birliği talepleriniz için bize yazın.', 'canonical' => url('/contact')],
        ] + $this->forgetSent()));
    }

    private function forgetSent(): array
    {
        Session::forget('contact_sent');
        return [];
    }

    public function contactSend(array $p): Response
    {
        $old = [
            'name' => Sanitizer::text(Request::str('name', 120)),
            'email' => mb_strtolower(Request::str('email', 190)),
            'subject' => Sanitizer::text(Request::str('subject', 200)),
            'message' => Sanitizer::text(Request::str('message', 5000)),
        ];
        $errors = [];
        if (Request::str('website') !== '') { // honeypot: real visitors never fill this hidden field
            return Response::redirect('/contact');
        }
        if (mb_strlen($old['name']) < 2) {
            $errors['name'] = 'Adınızı yazın (en az 2 karakter).';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli bir e-posta adresi yazın.';
        }
        if (mb_strlen($old['subject']) < 3) {
            $errors['subject'] = 'Konu en az 3 karakter olmalı.';
        }
        if (mb_strlen($old['message']) < 10) {
            $errors['message'] = 'Mesajınız en az 10 karakter olmalı.';
        }
        if (!$errors && !RateLimiter::hit('contact', 3, 3600)) {
            $errors['form'] = 'Kısa sürede çok fazla mesaj gönderdiniz. Lütfen bir saat sonra tekrar deneyin.';
        }
        if ($errors) {
            return Response::html(View::render('public/contact', [
                'errors' => $errors, 'old' => $old, 'sent' => false,
                'seo' => ['title' => 'İletişim', 'robots' => 'noindex', 'canonical' => url('/contact')],
            ]), 422);
        }
        DB::insert('contact_messages', $old + ['ip_hash' => Request::ipHash(), 'created_at' => now()]);
        $to = (string)setting('contact_email', '');
        if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL) && function_exists('mail')) {
            $host = parse_url(url('/'), PHP_URL_HOST) ?: 'localhost';
            $headers = "From: no-reply@$host\r\nReply-To: " . str_replace(["\r", "\n"], '', $old['email']) . "\r\nContent-Type: text/plain; charset=UTF-8";
            if (!@mail($to, '=?UTF-8?B?' . base64_encode('[İletişim] ' . $old['subject']) . '?=', $old['name'] . ' <' . $old['email'] . ">\n\n" . $old['message'], $headers)) {
                Logger::warning('Contact notification e-mail could not be sent.');
            }
        }
        Session::set('contact_sent', true);
        return Response::redirect('/contact');
    }
}
