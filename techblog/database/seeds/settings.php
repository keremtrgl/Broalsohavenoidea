<?php
/** Default site settings (only inserted when a key does not exist yet). */
return [
    'site_name' => 'TechBlog',
    'site_tagline' => 'Teknolojiyi tek yazıda anlatan rehberler',
    'site_description' => 'Programlama dillerinden ağ donanımına, işlemcilerden yapay zekâ modellerine: her kavramı görsellerle, tablolarla ve kaynak göstererek açıklayan Türkçe teknoloji rehberleri.',
    'site_logo' => '/assets/img/logo.svg',
    'accent_light' => '#0a7558',
    'accent_dark' => '#3ddc97',
    'footer_text' => 'Teknolojinin temel kavramlarını kaynak göstererek, sade bir Türkçeyle anlatan bağımsız rehber sitesi.',
    'contact_email' => '',
    'social' => [],
    'menu' => [
        ['label' => 'Seri', 'url' => '/series/every-x-explained'],
        ['label' => 'Yapay zekâ', 'url' => '/category/yapay-zeka'],
        ['label' => 'Tüm yazılar', 'url' => '/archive'],
        ['label' => 'Hakkında', 'url' => '/about'],
        ['label' => 'İletişim', 'url' => '/contact'],
    ],
    'analytics_ga4' => '',
    'analytics_script' => '',
    'comments_moderation' => true,
    'maintenance_mode' => false,
    'home_series' => 'Every X Explained',
];
