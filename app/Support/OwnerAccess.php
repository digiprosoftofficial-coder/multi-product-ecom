<?php

namespace App\Support;

use App\Models\Setting;

class OwnerAccess
{
    public const IDENTITY = 'identity';

    public const CONTACT = 'contact';

    public const SITE_SETTING = 'site_setting';

    public const SEO = 'seo';

    public const PAYMENT = 'payment';

    public static function features(): array
    {
        return [
            self::IDENTITY => [
                'key' => 'owner_can_identity',
                'label' => 'Store identity',
                'help' => 'Site name, logos, favicon, and footer tagline.',
            ],
            self::CONTACT => [
                'key' => 'owner_can_contact',
                'label' => 'Contact information',
                'help' => 'Phone, email, address, hours, and map — shown on the site and invoices.',
            ],
            self::SITE_SETTING => [
                'key' => 'owner_can_site_setting',
                'label' => 'Site Setting',
                'help' => 'Homepage, banners, about, shop/product/contact pages, cart, checkout, and info pages.',
            ],
            self::SEO => [
                'key' => 'owner_can_seo',
                'label' => 'SEO',
                'help' => 'Meta description, share image, Google Analytics, Tag Manager, and Facebook Pixel.',
            ],
            self::PAYMENT => [
                'key' => 'owner_can_payment',
                'label' => 'Payment methods',
                'help' => 'Turn COD / bKash / Nagad / Rocket on or off and edit wallet numbers.',
            ],
        ];
    }

    public static function enabled(string $feature): bool
    {
        $meta = self::features()[$feature] ?? null;

        if (! $meta) {
            return false;
        }

        return (string) Setting::get($meta['key'], '0') === '1';
    }

    public static function set(string $feature, bool $on): void
    {
        $meta = self::features()[$feature] ?? null;

        if (! $meta) {
            return;
        }

        Setting::set($meta['key'], $on ? '1' : '0');
    }
}
