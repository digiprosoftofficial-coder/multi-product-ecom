<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create exactly 10 categories using factory
        Category::factory()->count(10)->create();

        $this->command->info('10 categories seeded successfully!');
    }
}

