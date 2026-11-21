<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
            $table->text('description_bn')->nullable()->after('description');
            $table->string('meta_title_bn')->nullable()->after('meta_title');
            $table->text('meta_description_bn')->nullable()->after('meta_description');
            $table->string('option1_name_bn', 40)->nullable()->after('option1_name');
            $table->string('option2_name_bn', 40)->nullable()->after('option2_name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
            $table->text('description_bn')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_bn',
                'description_bn',
                'meta_title_bn',
                'meta_description_bn',
                'option1_name_bn',
                'option2_name_bn',
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'description_bn']);
        });
    }
};
