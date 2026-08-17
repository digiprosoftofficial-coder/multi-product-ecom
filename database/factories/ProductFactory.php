<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 1000);
        $comparePrice = $price * 1.2;

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'category_id' => Category::factory(),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $price,
            'compare_price' => $comparePrice,
            'discount_price' => $this->faker->boolean(30) ? $price * 0.8 : null,
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean(80) ? 1 : 0,
            'thumbnail' => null,
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
        ];
    }
}

