<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 'inside_dhaka' | 'outside_dhaka' | null (for old orders before feature)
            $table->string('delivery_zone')->nullable()->after('shipping_address');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('delivery_zone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_zone', 'shipping_cost']);
        });
    }
};
