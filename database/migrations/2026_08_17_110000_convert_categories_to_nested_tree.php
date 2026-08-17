<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'parent_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
                $table->index('parent_id');
            });
        }

        $subMap = [];
        if (Schema::hasTable('subcategories')) {
            $subs = DB::table('subcategories')->orderBy('id')->get();
            foreach ($subs as $sub) {
                $id = DB::table('categories')->insertGetId([
                    'parent_id' => $sub->category_id,
                    'name' => $sub->name,
                    'slug' => $this->uniqueSlug($sub->slug ?: Str::slug($sub->name)),
                    'description' => $sub->description,
                    'status' => $sub->status,
                    'image' => $sub->image,
                    'created_at' => $sub->created_at,
                    'updated_at' => $sub->updated_at,
                ]);
                $subMap[$sub->id] = $id;
            }
        }

        $childMap = [];
        if (Schema::hasTable('child_categories')) {
            $children = DB::table('child_categories')->orderBy('id')->get();
            foreach ($children as $child) {
                $parentId = $subMap[$child->sub_category_id] ?? null;
                $id = DB::table('categories')->insertGetId([
                    'parent_id' => $parentId,
                    'name' => $child->name,
                    'slug' => $this->uniqueSlug($child->slug ?: Str::slug($child->name)),
                    'description' => $child->description,
                    'status' => $child->status,
                    'image' => $child->image,
                    'created_at' => $child->created_at,
                    'updated_at' => $child->updated_at,
                ]);
                $childMap[$child->id] = $id;
            }
        }

        if (Schema::hasColumn('products', 'child_category_id') || Schema::hasColumn('products', 'sub_category_id')) {
            $select = ['id', 'category_id'];
            if (Schema::hasColumn('products', 'sub_category_id')) {
                $select[] = 'sub_category_id';
            }
            if (Schema::hasColumn('products', 'child_category_id')) {
                $select[] = 'child_category_id';
            }
            $products = DB::table('products')->get($select);
            foreach ($products as $product) {
                $leafId = $product->category_id;
                if (isset($product->child_category_id) && ! empty($product->child_category_id) && isset($childMap[$product->child_category_id])) {
                    $leafId = $childMap[$product->child_category_id];
                } elseif (isset($product->sub_category_id) && ! empty($product->sub_category_id) && isset($subMap[$product->sub_category_id])) {
                    $leafId = $subMap[$product->sub_category_id];
                }
                if ($leafId != $product->category_id) {
                    DB::table('products')->where('id', $product->id)->update(['category_id' => $leafId]);
                }
            }
        }

        if (Schema::hasColumn('products', 'child_category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['child_category_id']);
                $table->dropColumn('child_category_id');
            });
        }

        if (Schema::hasColumn('products', 'sub_category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['sub_category_id']);
                $table->dropColumn('sub_category_id');
            });
        }

        Schema::dropIfExists('child_categories');
        Schema::dropIfExists('subcategories');

        DB::table('settings')->updateOrInsert(
            ['key' => 'category_max_depth'],
            ['value' => '3', 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function down(): void
    {
        // Nested tree cannot be losslessly restored to the old 3-table layout.
    }

    private function uniqueSlug(string $slug): string
    {
        $base = $slug ?: 'category';
        $candidate = $base;
        $i = 1;
        while (DB::table('categories')->where('slug', $candidate)->exists()) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
};
