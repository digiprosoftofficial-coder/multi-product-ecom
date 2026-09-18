<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Str;

class StorePreset
{
    public static function slugs(): array
    {
        return array_keys(self::definitions());
    }

    public static function definitions(): array
    {
        return [
            'grocery' => [
                'label' => 'Grocery / daily goods',
                'theme' => 'organic-v1',
                'settings' => [
                    'home_categories_title' => 'Shop by category',
                    'home_best_selling_title' => 'Best selling groceries',
                    'home_featured_title' => 'Fresh picks',
                    'home_popular_title' => 'Most popular',
                    'home_new_title' => 'Just arrived',
                    'home_stat1_value' => '200+',
                    'home_stat1_label' => 'Fresh items',
                    'home_stat2_value' => '1k+',
                    'home_stat2_label' => 'Happy families',
                    'home_stat3_value' => 'Same day',
                    'home_stat3_label' => 'Local delivery',
                ],
                'categories' => [
                    ['name' => 'Fruits', 'subs' => ['Citrus', 'Tropical']],
                    ['name' => 'Vegetables', 'subs' => ['Leafy', 'Root']],
                    ['name' => 'Dairy', 'subs' => ['Milk', 'Yogurt']],
                ],
                'demo_products' => [
                    ['sku' => 'PRE-GROC-ORG', 'name' => 'Fresh Orange 1kg', 'category' => 'Citrus', 'price' => 180, 'stock' => 80],
                    ['sku' => 'PRE-GROC-BAN', 'name' => 'Banana Dozen', 'category' => 'Tropical', 'price' => 80, 'stock' => 100],
                    ['sku' => 'PRE-GROC-SPN', 'name' => 'Spinach Bunch', 'category' => 'Leafy', 'price' => 40, 'stock' => 60],
                ],
            ],
            'gadget' => [
                'label' => 'Phones / covers / electronics',
                'theme' => 'gadget-v1',
                'settings' => [
                    'home_categories_title' => 'Shop gadgets',
                    'home_best_selling_title' => 'Best selling gadgets',
                    'home_featured_title' => 'Featured tech',
                    'home_popular_title' => 'Trending now',
                    'home_new_title' => 'New arrivals',
                    'home_stat1_value' => '500+',
                    'home_stat1_label' => 'Accessories',
                    'home_stat2_value' => '10k+',
                    'home_stat2_label' => 'Happy buyers',
                    'home_stat3_value' => '24h',
                    'home_stat3_label' => 'Dispatch',
                ],
                'categories' => [
                    ['name' => 'Phones', 'subs' => ['Smartphones', 'Feature phones']],
                    ['name' => 'Covers', 'subs' => ['iPhone covers', 'Android covers']],
                    ['name' => 'Chargers', 'subs' => ['Cables', 'Adapters']],
                ],
                'demo_products' => [
                    ['sku' => 'PRE-GAD-IPH', 'name' => 'iPhone 15 Case', 'category' => 'iPhone covers', 'price' => 890, 'stock' => 40],
                    ['sku' => 'PRE-GAD-CAB', 'name' => 'USB-C Fast Cable', 'category' => 'Cables', 'price' => 350, 'stock' => 90],
                    ['sku' => 'PRE-GAD-ADP', 'name' => '20W Adapter', 'category' => 'Adapters', 'price' => 650, 'stock' => 50],
                ],
            ],
            'fashion' => [
                'label' => 'Clothing / shoes',
                'theme' => 'organic-v1',
                'settings' => [
                    'home_categories_title' => 'Collections',
                    'home_best_selling_title' => 'Best sellers',
                    'home_featured_title' => 'Featured looks',
                    'home_popular_title' => 'Most popular',
                    'home_new_title' => 'New season',
                    'home_stat1_value' => '300+',
                    'home_stat1_label' => 'Styles',
                    'home_stat2_value' => '5k+',
                    'home_stat2_label' => 'Happy shoppers',
                    'home_stat3_value' => 'Easy',
                    'home_stat3_label' => 'Returns',
                ],
                'categories' => [
                    ['name' => 'Men', 'subs' => ['T-Shirts', 'Jeans']],
                    ['name' => 'Women', 'subs' => ['Kurtis', 'Sarees']],
                    ['name' => 'Shoes', 'subs' => ['Sneakers', 'Sandals']],
                ],
                'demo_products' => [
                    ['sku' => 'PRE-FASH-TEE', 'name' => 'Cotton Crew Tee', 'category' => 'T-Shirts', 'price' => 890, 'stock' => 70],
                    ['sku' => 'PRE-FASH-KRT', 'name' => 'Floral Cotton Kurti', 'category' => 'Kurtis', 'price' => 1890, 'stock' => 25],
                    ['sku' => 'PRE-FASH-SNK', 'name' => 'Everyday Sneakers', 'category' => 'Sneakers', 'price' => 2490, 'stock' => 20],
                ],
            ],
        ];
    }

    public static function apply(string $slug, bool $withDemoProducts = false): array
    {
        $preset = self::definitions()[$slug] ?? null;
        if (! $preset) {
            throw new \InvalidArgumentException('Unknown preset: '.$slug);
        }

        Setting::set('active_frontend_theme', $preset['theme']);

        foreach ($preset['settings'] as $key => $value) {
            Setting::set($key, (string) $value);
        }

        $categories = [];
        foreach ($preset['categories'] as $group) {
            $parent = self::upsertCategory($group['name'], $group['name'], null);
            $categories[] = $parent->name;
            foreach ($group['subs'] as $subName) {
                $child = self::upsertCategory($subName, $subName.' under '.$parent->name, $parent->id);
                $categories[] = $child->name;
            }
        }

        $products = 0;
        if ($withDemoProducts) {
            foreach ($preset['demo_products'] as $item) {
                $category = Category::where('slug', Str::slug($item['category']))->first();
                if (! $category) {
                    continue;
                }

                Product::firstOrCreate(
                    ['sku' => $item['sku']],
                    [
                        'name' => $item['name'],
                        'slug' => Str::slug($item['name']),
                        'category_id' => $category->id,
                        'description' => $item['name'],
                        'price' => $item['price'],
                        'stock' => $item['stock'],
                        'status' => 1,
                    ]
                );
                $products++;
            }
        }

        return [
            'slug' => $slug,
            'theme' => $preset['theme'],
            'categories' => $categories,
            'products' => $products,
        ];
    }

    protected static function upsertCategory(string $name, ?string $description, ?int $parentId): Category
    {
        return Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            [
                'name' => $name,
                'parent_id' => $parentId,
                'description' => $description,
                'status' => 1,
            ]
        );
    }
}
