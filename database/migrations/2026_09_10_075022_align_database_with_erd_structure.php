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
        // 1. categories
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'name') && !Schema::hasColumn('categories', 'category_name')) {
                $table->renameColumn('name', 'category_name');
            }
        });

        // 2. products
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'name') && !Schema::hasColumn('products', 'product_name')) {
                $table->renameColumn('name', 'product_name');
            }
            if (Schema::hasColumn('products', 'photo') && !Schema::hasColumn('products', 'product_photo')) {
                $table->renameColumn('photo', 'product_photo');
            }
            if (Schema::hasColumn('products', 'price') && !Schema::hasColumn('products', 'product_price')) {
                $table->renameColumn('price', 'product_price');
            }
            if (!Schema::hasColumn('products', 'product_description')) {
                $table->text('product_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });

        // 3. orders
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_number') && !Schema::hasColumn('orders', 'order_code')) {
                $table->renameColumn('order_number', 'order_code');
            }
            if (!Schema::hasColumn('orders', 'order_date')) {
                $table->date('order_date')->nullable();
            }
            if (Schema::hasColumn('orders', 'total_price') && !Schema::hasColumn('orders', 'order_amount')) {
                $table->renameColumn('total_price', 'order_amount');
            }
            if (Schema::hasColumn('orders', 'change') && !Schema::hasColumn('orders', 'order_change')) {
                $table->renameColumn('change', 'order_change');
            }
            if (Schema::hasColumn('orders', 'payment_status') && !Schema::hasColumn('orders', 'order_status')) {
                $table->renameColumn('payment_status', 'order_status');
            }
            $dropCols = [];
            if (Schema::hasColumn('orders', 'snap_token')) {
                $dropCols[] = 'snap_token';
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $dropCols[] = 'payment_method';
            }
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });

        // 4. order_details
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'unit_price') && !Schema::hasColumn('order_details', 'order_price')) {
                $table->renameColumn('unit_price', 'order_price');
            }
            if (Schema::hasColumn('order_details', 'subtotal') && !Schema::hasColumn('order_details', 'order_amount')) {
                $table->renameColumn('subtotal', 'order_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe rollback logic
    }
};
