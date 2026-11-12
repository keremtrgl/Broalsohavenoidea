<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Audit;
use App\Core\Request;
use App\Core\Response;
use App\Core\Settings;
use App\Services\Sanitizer;

final class SettingsController extends AdminBase
{
    public const SOCIAL = ['x' => 'X (Twitter)', 'github' => 'GitHub', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram'];

    public function index(array $p): Response
    {
        return $this->view('settings', 'Site ayarları', ['s' => Settings::all(), 'social' => self::SOCIAL]);
    }

    public function save(array $p): Response
    {
        $back = '/admin/settings';
        $name = Sanitizer::text(Request::str('site_name', 80));
        if ($name === '') {
            return $this->done('Site adı boş olamaz.', $back, false);
        }
        $colors = [];
        foreach (['accent_light', 'accent_dark'] as $k) {
            $c = Request::str($k, 7);
            if (!preg_match('/^#[0-9a-fA-F]{6}$/', $c)) {
                return $this->done('Renkler #RRGGBB biçiminde olmalı.', $back, false);
            }
            $colors[$k] = strtolower($c);
        }
        $ga = strtoupper(Request::str('analytics_ga4', 20));
        if ($ga !== '' && !preg_match('/^G-[A-Z0-9]{4,15}$/', $ga)) {
            return $this->done('GA4 ölçüm kimliği G-XXXXXXXXXX biçiminde olmalı.', $back, false);
        }
        $script = Request::str('analytics_script', 300);
        if ($script !== '' && (Sanitizer::url($script) === '' || !str_starts_with($script, 'https://'))) {
            return $this->done('Analitik betiği adresi https:// ile başlayan geçerli bir URL olmalı.', $back, false);
        }
        $contact = Request::str('contact_email', 190);
        if ($contact !== '' && !filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            return $this->done('İletişim e-postası geçersiz.', $back, false);
        }
        $social = [];
        foreach (array_keys(self::SOCIAL) as $k) {
            $u = Request::str('social_' . $k, 300);
            if ($u !== '') {
                if (Sanitizer::url($u) === '') {
                    return $this->done(self::SOCIAL[$k] . ' bağlantısı geçerli bir URL olmalı.', $back, false);
                }
                $social[$k] = $u;
            }
        }
        $menu = [];
        $labels = (array)Request::input('menu_label', []);
        $urls = (array)Request::input('menu_url', []);
        foreach ($labels as $i => $label) {
            $label = Sanitizer::text((string)$label, 40);
            $u = trim((string)($urls[$i] ?? ''));
            if ($label === '' || $u === '') {
                continue;
            }
            if (!preg_match('#^/[A-Za-z0-9/_\-?=&.]*$#', $u) && Sanitizer::url($u) === '') {
                return $this->done('Menü bağlantıları “/yol” biçiminde veya tam bir URL olmalı: ' . $label, $back, false);
            }
            $menu[] = ['label' => $label, 'url' => $u];
        }
        $logo = Request::str('site_logo', 300);
        Settings::setMany([
            'site_name' => $name,
            'site_tagline' => Sanitizer::text(Request::str('site_tagline', 120)),
            'site_description' => Sanitizer::text(Request::str('site_description', 300)),
            'site_logo' => $logo !== '' ? Sanitizer::localPath($logo) : '',
            'accent_light' => $colors['accent_light'],
            'accent_dark' => $colors['accent_dark'],
            'footer_text' => Sanitizer::text(Request::str('footer_text', 300)),
            'contact_email' => $contact,
            'social' => $social,
            'menu' => array_slice($menu, 0, 10),
            'analytics_ga4' => $ga,
            'analytics_script' => $script,
            'comments_moderation' => Request::bool('comments_moderation'),
            'maintenance_mode' => Request::bool('maintenance_mode'),
            'home_series' => Sanitizer::text(Request::str('home_series', 100)),
        ]);
        $this->contentChanged();
        Audit::log('settings.update', 'settings', null, ['maintenance' => Request::bool('maintenance_mode')]);
        return $this->done('Ayarlar kaydedildi.', $back);
    }
}
