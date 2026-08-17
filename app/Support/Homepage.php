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
        return setting_image_url(self::get('home_hero_image'))
            ?: asset('organic-v1/images/banner-1.jpg');
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
