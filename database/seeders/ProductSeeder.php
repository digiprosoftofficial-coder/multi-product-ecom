<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();

        Product::factory(10)->create()->each(function ($product) use ($categories, $subCategories) {
            // Assign random category
            if ($categories->isNotEmpty()) {
                $product->category_id = $categories->random()->id;
            }

            // Assign random subcategory 50% of the time (only from same category)
            if ($subCategories->isNotEmpty() && rand(0, 1)) {
                $categorySubCategories = $subCategories->where('category_id', $product->category_id);
                if ($categorySubCategories->isNotEmpty()) {
                    $product->sub_category_id = $categorySubCategories->random()->id;
                }
            }

            $product->save();

            // Create product images (1-3 images per product)
            $imageCount = rand(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'filename' => 'placeholder-' . rand(1, 10) . '.jpg',
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        });

        $this->command->info('Products seeded successfully!');
    }
}

