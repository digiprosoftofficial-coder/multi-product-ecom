<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop old foreign key
            $table->dropForeign(['sub_category_id']);
            
            // Add new foreign key to subcategories table
            $table->foreign('sub_category_id')->references('id')->on('subcategories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }
};

