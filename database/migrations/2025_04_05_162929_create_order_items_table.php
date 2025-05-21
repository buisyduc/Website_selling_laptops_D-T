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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Liên kết với đơn hàng
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Liên kết với sản phẩm
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Số lượng sản phẩm
            $table->integer('quantity');

            // Giá mỗi sản phẩm tại thời điểm mua
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
