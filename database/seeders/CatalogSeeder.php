<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            [
                'name' => 'Electronics',
                'description' => 'Phones, laptops and gadgets',
                'subs' => [
                    [
                        'name' => 'Mobile',
                        'children' => [
                            [
                                'name' => 'Smartphones',
                                'products' => [
                                    ['name' => 'iPhone 15', 'sku' => 'ELEC-IPH15', 'price' => 99900, 'compare' => 109900, 'discount' => 94900, 'stock' => 15, 'description' => 'Apple iPhone 15, 128GB, 6.1-inch display.'],
                                    ['name' => 'Samsung Galaxy S24', 'sku' => 'ELEC-SGS24', 'price' => 84900, 'compare' => 89900, 'discount' => 79900, 'stock' => 20, 'description' => 'Samsung Galaxy S24, 256GB, flagship Android phone.'],
                                    ['name' => 'Xiaomi Redmi Note 13', 'sku' => 'ELEC-RN13', 'price' => 22900, 'compare' => 25900, 'discount' => null, 'stock' => 40, 'description' => 'Redmi Note 13, 8GB RAM, 128GB storage.'],
                                ],
                            ],
                            [
                                'name' => 'Feature Phones',
                                'products' => [
                                    ['name' => 'Nokia 105', 'sku' => 'ELEC-NK105', 'price' => 1800, 'compare' => 2000, 'discount' => 1650, 'stock' => 50, 'description' => 'Classic Nokia 105 dual SIM feature phone.'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Laptops',
                        'children' => [
                            [
                                'name' => 'Gaming Laptops',
                                'products' => [
                                    ['name' => 'ASUS TUF F15', 'sku' => 'ELEC-TUF15', 'price' => 125000, 'compare' => 135000, 'discount' => 119000, 'stock' => 8, 'description' => 'ASUS TUF Gaming F15, RTX graphics, 16GB RAM.'],
                                ],
                            ],
                            [
                                'name' => 'Ultrabooks',
                                'products' => [
                                    ['name' => 'MacBook Air M2', 'sku' => 'ELEC-MBA-M2', 'price' => 135000, 'compare' => 145000, 'discount' => null, 'stock' => 6, 'description' => 'Apple MacBook Air M2, 13-inch, 8GB RAM, 256GB SSD.'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing and apparel for men and women',
                'subs' => [
                    [
                        'name' => 'Men',
                        'children' => [
                            [
                                'name' => 'T-Shirts',
                                'products' => [
                                    ['name' => 'Cotton Crew Tee', 'sku' => 'FASH-TEE-M1', 'price' => 890, 'compare' => 1200, 'discount' => 750, 'stock' => 80, 'description' => 'Men\'s 100% cotton crew neck t-shirt.'],
                                    ['name' => 'Graphic Print Tee', 'sku' => 'FASH-TEE-M2', 'price' => 990, 'compare' => 1300, 'discount' => null, 'stock' => 60, 'description' => 'Men\'s graphic print cotton t-shirt.'],
                                ],
                            ],
                            [
                                'name' => 'Jeans',
                                'products' => [
                                    ['name' => 'Slim Fit Blue Jeans', 'sku' => 'FASH-JN-M1', 'price' => 2490, 'compare' => 2990, 'discount' => 2190, 'stock' => 35, 'description' => 'Men\'s slim fit stretch denim jeans.'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Women',
                        'children' => [
                            [
                                'name' => 'Kurtis',
                                'products' => [
                                    ['name' => 'Floral Cotton Kurti', 'sku' => 'FASH-KRT-W1', 'price' => 1890, 'compare' => 2290, 'discount' => 1590, 'stock' => 25, 'description' => 'Women\'s floral print cotton kurti.'],
                                ],
                            ],
                            [
                                'name' => 'Sarees',
                                'products' => [
                                    ['name' => 'Silk Banarasi Saree', 'sku' => 'FASH-SAR-W1', 'price' => 6500, 'compare' => 8500, 'discount' => 5990, 'stock' => 12, 'description' => 'Traditional Banarasi silk saree with woven border.'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Grocery',
                'description' => 'Fresh fruits and vegetables',
                'subs' => [
                    [
                        'name' => 'Fruits',
                        'children' => [
                            [
                                'name' => 'Citrus',
                                'products' => [
                                    ['name' => 'Fresh Orange 1kg', 'sku' => 'GROC-ORG-1K', 'price' => 180, 'compare' => 220, 'discount' => 160, 'stock' => 100, 'description' => 'Fresh juicy oranges, packed 1kg.'],
                                    ['name' => 'Malta 1kg', 'sku' => 'GROC-MLT-1K', 'price' => 150, 'compare' => 180, 'discount' => null, 'stock' => 90, 'description' => 'Sweet malta, packed 1kg.'],
                                ],
                            ],
                            [
                                'name' => 'Tropical',
                                'products' => [
                                    ['name' => 'Banana Dozen', 'sku' => 'GROC-BAN-DZ', 'price' => 80, 'compare' => 100, 'discount' => 70, 'stock' => 120, 'description' => 'Ripe bananas, one dozen.'],
                                    ['name' => 'Mango 1kg', 'sku' => 'GROC-MNG-1K', 'price' => 220, 'compare' => 260, 'discount' => 199, 'stock' => 70, 'description' => 'Seasonal mangoes, packed 1kg.'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Vegetables',
                        'children' => [
                            [
                                'name' => 'Leafy',
                                'products' => [
                                    ['name' => 'Spinach Bunch', 'sku' => 'GROC-SPN-BN', 'price' => 40, 'compare' => 50, 'discount' => null, 'stock' => 60, 'description' => 'Fresh spinach bunch.'],
                                ],
                            ],
                            [
                                'name' => 'Root',
                                'products' => [
                                    ['name' => 'Potato 1kg', 'sku' => 'GROC-POT-1K', 'price' => 45, 'compare' => 55, 'discount' => 40, 'stock' => 200, 'description' => 'Local potatoes, packed 1kg.'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $productCount = 0;

        foreach ($tree as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryData['name'])],
                [
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'],
                    'status' => 1,
                ]
            );

            foreach ($categoryData['subs'] as $subData) {
                $sub = SubCategory::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($subData['name'])],
                    [
                        'name' => $subData['name'],
                        'category_id' => $category->id,
                        'description' => $subData['name'].' under '.$category->name,
                        'status' => 1,
                    ]
                );

                foreach ($subData['children'] as $childData) {
                    $child = ChildCategory::firstOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($childData['name'])],
                        [
                            'name' => $childData['name'],
                            'sub_category_id' => $sub->id,
                            'description' => $childData['name'].' under '.$sub->name,
                            'status' => 1,
                        ]
                    );

                    foreach ($childData['products'] as $productData) {
                        Product::firstOrCreate(
                            ['sku' => $productData['sku']],
                            [
                                'name' => $productData['name'],
                                'slug' => \Illuminate\Support\Str::slug($productData['name']),
                                'category_id' => $category->id,
                                'sub_category_id' => $sub->id,
                                'child_category_id' => $child->id,
                                'description' => $productData['description'],
                                'price' => $productData['price'],
                                'compare_price' => $productData['compare'],
                                'discount_price' => $productData['discount'],
                                'stock' => $productData['stock'],
                                'status' => 1,
                                'meta_title' => $productData['name'],
                                'meta_description' => $productData['description'],
                            ]
                        );
                        $productCount++;
                    }
                }
            }
        }

        $this->command->info("Catalog seeded: 3 categories, 6 subcategories, 12 child categories, {$productCount} products.");
    }
}
