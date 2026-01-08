<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if parent_id column exists in categories table
        if (!Schema::hasColumn('categories', 'parent_id')) {
            // Column doesn't exist, so no migration needed
            return;
        }

        // Migrate existing subcategories from categories table to subcategories table
        $subcategories = DB::table('categories')
            ->whereNotNull('parent_id')
            ->get();

        foreach ($subcategories as $subcategory) {
            DB::table('subcategories')->insert([
                'name' => $subcategory->name,
                'slug' => $subcategory->slug,
                'category_id' => $subcategory->parent_id,
                'description' => $subcategory->description,
                'status' => $subcategory->status,
                'image' => $subcategory->image,
                'created_at' => $subcategory->created_at,
                'updated_at' => $subcategory->updated_at,
            ]);

            // Update products that reference this subcategory
            DB::table('products')
                ->where('sub_category_id', $subcategory->id)
                ->update(['sub_category_id' => DB::raw('(SELECT id FROM subcategories WHERE slug = "' . $subcategory->slug . '" LIMIT 1)')]);
        }

        // Delete old subcategories from categories table
        DB::table('categories')
            ->whereNotNull('parent_id')
            ->delete();
    }

    public function down(): void
    {
        // This migration is not easily reversible
        // You would need to recreate the parent_id column and migrate data back
    }
};

