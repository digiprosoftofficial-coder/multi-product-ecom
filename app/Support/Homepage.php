<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Homepage
{
    public static function defaults(): array
    {
        return [
            'home_hero_title' => 'Organic Foods at your Doorsteps',
            'home_hero_highlight' => 'Organic',
            'home_hero_subtitle' => 'Fresh products delivered to your home.',
            'home_hero_btn1_text' => 'Start Shopping',
            'home_hero_btn1_url' => '/products',
            'home_hero_btn2_text' => 'Join Now',
            'home_hero_btn2_url' => '/register',
            'home_hero_image' => '',
            'home_hero_slides' => '',
            'home_hero_autoplay' => '1',
            'home_hero_interval' => '5',
            'home_hero_height_desktop' => '480',
            'home_hero_height_mobile' => '320',
            'home_hero_show_dots' => '1',
            'home_hero_show_arrows' => '1',
            'home_hero_show_overlay' => '1',
            'home_hero_overlay_color' => '#ffffff',
            'home_hero_overlay_opacity' => '45',
            'home_stat1_value' => '14k+',
            'home_stat1_label' => 'Product Varieties',
            'home_stat2_value' => '50k+',
            'home_stat2_label' => 'Happy Customers',
            'home_stat3_value' => '10+',
            'home_stat3_label' => 'Store Locations',
            'home_show_categories' => '1',
            'home_show_best_selling' => '1',
            'home_show_banners' => '1',
            'home_show_featured' => '1',
            'home_show_popular' => '1',
            'home_show_new' => '1',
            'home_show_newsletter' => '1',
            'home_show_features' => '1',
            'home_categories_title' => 'Category',
            'home_best_selling_title' => 'Best selling products',
            'home_featured_title' => 'Featured products',
            'home_popular_title' => 'Most popular products',
            'home_new_title' => 'Just arrived',
            'home_best_selling_limit' => '10',
            'home_featured_limit' => '10',
            'home_popular_limit' => '10',
            'home_new_limit' => '10',
            'home_banner1_title' => 'Items on SALE',
            'home_banner1_text' => 'Discounts up to 30%',
            'home_banner1_url' => '/products',
            'home_banner2_title' => 'Combo offers',
            'home_banner2_text' => 'Discounts up to 50%',
            'home_banner2_url' => '/products',
            'home_banner3_title' => 'Discount Coupons',
            'home_banner3_text' => 'Discounts up to 40%',
            'home_banner3_url' => '/products',
            'home_newsletter_title' => 'Get 25% Discount on your first purchase',
            'home_newsletter_text' => 'Just Sign Up & Register it now to become member.',
            'home_feature_1_title' => 'Free delivery',
            'home_feature_1_text' => 'Fast, free shipping on every order — right to your door.',
            'home_feature_1_icon' => 'fa-truck-fast',
            'home_feature_2_title' => '100% secure payment',
            'home_feature_2_text' => 'Your payment details are encrypted and fully protected.',
            'home_feature_2_icon' => 'fa-shield-halved',
            'home_feature_3_title' => 'Quality guarantee',
            'home_feature_3_text' => 'We stand behind every product with a quality promise.',
            'home_feature_3_icon' => 'fa-award',
            'home_feature_4_title' => 'Guaranteed savings',
            'home_feature_4_text' => 'Fair prices and regular deals so you always save more.',
            'home_feature_4_icon' => 'fa-tags',
            'home_feature_5_title' => 'Daily offers',
            'home_feature_5_text' => 'Fresh discounts every day — check back for new deals.',
            'home_feature_5_icon' => 'fa-gift',
            'about_title' => '',
        ];
    }

    public static function get(string $key, $default = null): string
    {
        $defaults = self::defaults();

        return (string) setting($key, $default ?? ($defaults[$key] ?? ''));
    }

    public static function enabled(string $key): bool
    {
        return self::get($key) === '1';
    }

    public static function heroImageUrl(): string
    {
        $slides = self::slides();

        if (! empty($slides[0]['image'])) {
            return self::slideImageUrl($slides[0]['image']);
        }

        return setting_image_url(self::get('home_hero_image'))
            ?: asset('organic-v1/images/banner-1.jpg');
    }

    public static function slideImageUrl(?string $filename): string
    {
        return setting_image_url($filename)
            ?: asset('organic-v1/images/banner-1.jpg');
    }

    /** @return array<int, array<string, mixed>> */
    public static function slidesForAdmin(): array
    {
        $slides = self::decodeSlides();

        return count($slides) > 0 ? $slides : [self::legacySlide()];
    }

    /** @return array<int, array<string, mixed>> */
    public static function slides(): array
    {
        $slides = array_values(array_filter(
            self::decodeSlides(),
            fn (array $slide) => ($slide['enabled'] ?? true) && ! empty($slide['image'])
        ));

        return count($slides) > 0 ? $slides : [self::legacySlide()];
    }

    public static function heroAutoplay(): bool
    {
        return self::get('home_hero_autoplay', '1') === '1';
    }

    public static function heroIntervalMs(): int
    {
        $seconds = max(2, min(15, (int) self::get('home_hero_interval', '5')));

        return $seconds * 1000;
    }

    public static function heroHeightDesktop(): int
    {
        return max(200, min(900, (int) self::get('home_hero_height_desktop', '480')));
    }

    public static function heroHeightMobile(): int
    {
        return max(180, min(800, (int) self::get('home_hero_height_mobile', '320')));
    }

    /** @return array<string, mixed> */
    protected static function legacySlide(): array
    {
        return self::normalizeSlide([
            'enabled' => true,
            'image' => self::get('home_hero_image'),
            'show_content' => true,
            'title' => self::get('home_hero_title'),
            'highlight' => self::get('home_hero_highlight'),
            'subtitle' => self::get('home_hero_subtitle'),
            'title_color' => '#212529',
            'subtitle_color' => '#212529',
            'highlight_color' => '#6BB252',
            'btn1_text' => self::get('home_hero_btn1_text'),
            'btn1_url' => self::get('home_hero_btn1_url'),
            'btn2_text' => self::get('home_hero_btn2_text'),
            'btn2_url' => self::get('home_hero_btn2_url'),
        ]);
    }

    /** @return array<int, array<string, mixed>> */
    protected static function decodeSlides(): array
    {
        $raw = setting('home_hero_slides');

        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            return [];
        }

        return array_values(array_map(fn ($slide) => self::normalizeSlide(is_array($slide) ? $slide : []), $decoded));
    }

    /** @param  array<string, mixed>  $slide */
    public static function normalizeSlide(array $slide): array
    {
        return [
            'enabled' => filter_var($slide['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'image' => (string) ($slide['image'] ?? ''),
            'image_mobile' => (string) ($slide['image_mobile'] ?? ''),
            'show_content' => filter_var($slide['show_content'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'title' => (string) ($slide['title'] ?? ''),
            'highlight' => (string) ($slide['highlight'] ?? ''),
            'subtitle' => (string) ($slide['subtitle'] ?? ''),
            'title_color' => self::normalizeColor($slide['title_color'] ?? '#ffffff', '#ffffff'),
            'subtitle_color' => self::normalizeColor($slide['subtitle_color'] ?? '#ffffff', '#ffffff'),
            'highlight_color' => self::normalizeColor($slide['highlight_color'] ?? '#22c55e', '#22c55e'),
            'btn1_text' => (string) ($slide['btn1_text'] ?? ''),
            'btn1_url' => (string) ($slide['btn1_url'] ?? ''),
            'btn2_text' => (string) ($slide['btn2_text'] ?? ''),
            'btn2_url' => (string) ($slide['btn2_url'] ?? ''),
        ];
    }

    /**
     * Desktop or mobile hero image URL. Falls back to desktop when mobile is empty.
     */
    public static function slideResponsiveImageUrl(array $slide, bool $mobile = false): string
    {
        if ($mobile && ! empty($slide['image_mobile'])) {
            return self::slideImageUrl($slide['image_mobile']);
        }

        return self::slideImageUrl($slide['image'] ?? null);
    }

    public static function normalizeColor(?string $color, string $fallback = '#ffffff'): string
    {
        $color = trim((string) $color);

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color)) {
            return strtolower($color);
        }

        return $fallback;
    }

    public static function colorToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim(self::normalizeColor($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $alpha = max(0, min(1, $alpha));

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }

    public static function heroOverlayBackground(): string
    {
        $opacity = max(0, min(100, (int) self::get('home_hero_overlay_opacity', '45'))) / 100;
        $color = self::normalizeColor(self::get('home_hero_overlay_color', '#ffffff'), '#ffffff');

        return self::colorToRgba($color, $opacity);
    }

    public static function renderHeroTitle(array $slide): string
    {
        $title = e($slide['title'] ?? '');
        $highlight = (string) ($slide['highlight'] ?? '');

        if ($highlight !== '' && str_contains($slide['title'] ?? '', $highlight)) {
            $highlightHtml = '<span class="fw-bold" style="color:'.e($slide['highlight_color']).'">'.e($highlight).'</span>';

            return str_replace(e($highlight), $highlightHtml, $title);
        }

        return $title;
    }

    public static function heroButtonUrl(?string $url, string $fallback): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return $fallback;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '/')) {
            return $url;
        }

        return '/'.ltrim($url, '/');
    }

    public static function products(string $flag, int $limit): Collection
    {
        $base = Product::query()
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->with(['category', 'images']);

        $flagged = (clone $base)->where($flag, true)->latest()->take($limit)->get();

        if ($flagged->isNotEmpty()) {
            return $flagged;
        }

        return $base->latest()->take($limit)->get();
    }
}
