<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('child_category_id')->nullable()->after('sub_category_id');
            $table->foreign('child_category_id')->references('id')->on('child_categories')->onDelete('set null');
            $table->index('child_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['child_category_id']);
            $table->dropIndex(['child_category_id']);
            $table->dropColumn('child_category_id');
        });
    }
};
