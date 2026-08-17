<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->boolean('is_popular')->default(false)->after('is_featured');
            $table->boolean('is_new_arrival')->default(false)->after('is_popular');
            $table->boolean('is_best_selling')->default(false)->after('is_new_arrival');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_popular', 'is_new_arrival', 'is_best_selling']);
        });
    }
};
