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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('change')->nullable();
            $table->tinyInteger('payment_method')->default(0)->comment("0 = cash, 1 =midtrans"); 
            $table->tinyInteger('payment_status')->default(0)->comment("0 = pending, 1 = paid, 2 = failed, 3 = canceled");
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
