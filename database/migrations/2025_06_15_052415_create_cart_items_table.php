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

            // Liên kết với bảng carts
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // Liên kết với bảng product_variants
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');

            // Ràng buộc không trùng variant trong cùng 1 cart
            $table->unique(['cart_id', 'variant_id']);

            // Số lượng sản phẩm
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
