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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            //Tránh trùng sản phẩm trong cùng một giỏ hàng.
            $table->unique(['cart_id', 'product_id']);
            // Liên kết với giỏ hàng
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // Liên kết với sản phẩm
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Số lượng sản phẩm trong giỏ
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
