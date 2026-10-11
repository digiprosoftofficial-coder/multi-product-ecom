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

    public const STORE_CATALOG = 'store_catalog';

    public const DELIVERY = 'delivery';

    public const COLORS = 'colors';

    public const SOCIAL = 'social';

    public const MESSAGES = 'messages';

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
            self::STORE_CATALOG => [
                'key' => 'owner_can_store_catalog',
                'label' => 'Store & catalog',
                'help' => 'Currency, tax and VAT rates, compare price (MRP), and category depth.',
            ],
            self::DELIVERY => [
                'key' => 'owner_can_delivery',
                'label' => 'Delivery charges',
                'help' => 'Inside Dhaka and outside Dhaka shipping charges.',
            ],
            self::COLORS => [
                'key' => 'owner_can_colors',
                'label' => 'Header & footer colors',
                'help' => 'Storefront header, footer, and copyright bar colors.',
            ],
            self::SOCIAL => [
                'key' => 'owner_can_social',
                'label' => 'Social media',
                'help' => 'Facebook, Instagram, YouTube, TikTok links and WhatsApp number.',
            ],
            self::MESSAGES => [
                'key' => 'owner_can_messages',
                'label' => 'Contact messages',
                'help' => 'Read, mark, and delete messages sent from the contact form.',
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
