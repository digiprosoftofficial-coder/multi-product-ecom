<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $leaves = Category::leafSelectOptions();

        Product::factory(10)->create()->each(function ($product) use ($leaves) {
            if ($leaves->isNotEmpty()) {
                $product->category_id = $leaves->random()->id;
                $product->save();
            }

            $imageCount = rand(1, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'filename' => 'placeholder-'.rand(1, 10).'.jpg',
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        });

        $this->command->info('Products seeded successfully!');
    }
}
