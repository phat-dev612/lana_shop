<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('user_id');
            $table->string('shipping_name')->nullable()->after('address');
            $table->string('shipping_phone')->nullable()->after('shipping_name');
            $table->string('shipping_address')->nullable()->after('shipping_phone');
            $table->decimal('subtotal', 10, 2)->default(0)->after('shipping_address');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('subtotal');
            $table->decimal('total', 10, 2)->default(0)->after('shipping_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'subtotal',
                'shipping_fee',
                'total'
            ]);
        });
    }
};
