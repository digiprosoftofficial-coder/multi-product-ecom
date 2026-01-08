<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('sub_category_id');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('sub_category_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->index('sub_category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_categories');
    }
};
