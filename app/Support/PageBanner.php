<?php

namespace App\Support;

class PageBanner
{
    public static function defaults(): array
    {
        return [
            'about_banner_enabled' => '1',
            'about_banner_title' => 'About Us',
            'about_banner_subtitle' => 'Learn more about our story and mission.',
            'about_banner_image' => '',
            'shop_banner_enabled' => '1',
            'shop_banner_title' => 'Shop',
            'shop_banner_subtitle' => 'Browse our latest products and deals.',
            'shop_banner_image' => '',
            'contact_banner_enabled' => '1',
            'contact_banner_title' => 'Contact Us',
            'contact_banner_subtitle' => 'We would love to hear from you.',
            'contact_banner_image' => '',
            'privacy_banner_enabled' => '1',
            'privacy_banner_title' => 'Privacy Policy',
            'privacy_banner_subtitle' => 'How we collect, use, and protect your information.',
            'privacy_banner_image' => '',
            'terms_banner_enabled' => '1',
            'terms_banner_title' => 'Terms & Conditions',
            'terms_banner_subtitle' => 'Please read these terms carefully before using our store.',
            'terms_banner_image' => '',
            'delivery_banner_enabled' => '1',
            'delivery_banner_title' => 'Delivery Information',
            'delivery_banner_subtitle' => 'Shipping times, areas, and delivery options.',
            'delivery_banner_image' => '',
            'returns_banner_enabled' => '1',
            'returns_banner_title' => 'Product Returns',
            'returns_banner_subtitle' => 'How to return or exchange items easily.',
            'returns_banner_image' => '',
            'cart_banner_enabled' => '1',
            'cart_banner_title' => 'Shopping Cart',
            'cart_banner_subtitle' => 'Review your items before checkout.',
            'cart_banner_image' => '',
            'checkout_banner_enabled' => '1',
            'checkout_banner_title' => 'Checkout',
            'checkout_banner_subtitle' => 'Complete your order securely.',
            'checkout_banner_image' => '',
            'product_banner_enabled' => '1',
            'product_banner_subtitle' => 'Quality products, carefully selected for you.',
            'product_banner_image' => '',
        ];
    }

    public static function get(string $key, $default = null): string
    {
        $defaults = self::defaults();

        return (string) setting($key, $default ?? ($defaults[$key] ?? ''));
    }

    public static function enabled(string $page): bool
    {
        $page = strtolower($page);

        return self::get("{$page}_banner_enabled", '1') === '1';
    }

    public static function imageUrl(string $key): ?string
    {
        return setting_image_url(self::get($key));
    }

    /**
     * @return array{enabled: bool, title: string, subtitle: string, image: ?string}
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
            'enabled' => self::enabled($page),
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => self::imageUrl($imageKey),
        ];
    }

    /**
     * @return array{enabled: bool, title: string, subtitle: string, image: ?string}
     */
    public static function forProduct(\App\Models\Product $product): array
    {
        $banner = self::for('product');

        $subtitle = $product->category?->pathName() ?: $banner['subtitle'];

        $image = $banner['image'];
        $firstImage = $product->relationLoaded('images')
            ? $product->images->first()
            : $product->images()->orderBy('sort_order')->first();

        if ($firstImage) {
            $image = upload_url('uploads/products/'.$firstImage->filename) ?? asset('images/product-placeholder.svg');
        } elseif ($product->thumbnail_url) {
            $image = $product->thumbnail_url;
        }

        return [
            'enabled' => $banner['enabled'],
            'title' => $product->name,
            'subtitle' => $subtitle,
            'image' => $image,
        ];
    }
}
