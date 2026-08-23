<?php

namespace App\Support;

class PageBanner
{
    public static function defaults(): array
    {
        return [
            'about_banner_title' => 'About Us',
            'about_banner_subtitle' => 'Learn more about our story and mission.',
            'about_banner_image' => '',
            'shop_banner_title' => 'Shop',
            'shop_banner_subtitle' => 'Browse our latest products and deals.',
            'shop_banner_image' => '',
            'contact_banner_title' => 'Contact Us',
            'contact_banner_subtitle' => 'We would love to hear from you.',
            'contact_banner_image' => '',
        ];
    }

    public static function get(string $key, $default = null): string
    {
        $defaults = self::defaults();

        return (string) setting($key, $default ?? ($defaults[$key] ?? ''));
    }

    public static function imageUrl(string $key): ?string
    {
        return setting_image_url(self::get($key));
    }

    /**
     * @return array{title: string, subtitle: string, image: ?string}
     */
    public static function for(string $page): array
    {
        $page = strtolower($page);
        $defaults = self::defaults();

        $titleKey = "{$page}_banner_title";
        $subtitleKey = "{$page}_banner_subtitle";
        $imageKey = "{$page}_banner_image";

        $title = self::get($titleKey, $defaults[$titleKey] ?? '');
        $subtitle = self::get($subtitleKey, $defaults[$subtitleKey] ?? '');

        if ($page === 'contact' && $subtitle === ($defaults['contact_banner_subtitle'] ?? '')) {
            $intro = trim((string) setting('contact_intro', ''));
            if ($intro !== '') {
                $subtitle = $intro;
            }
        }

        if ($page === 'about' && $title === ($defaults['about_banner_title'] ?? '')) {
            $aboutTitle = trim((string) setting('about_title', ''));
            if ($aboutTitle !== '') {
                $title = $aboutTitle;
            }
        }

        return [
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => self::imageUrl($imageKey),
        ];
    }
}
