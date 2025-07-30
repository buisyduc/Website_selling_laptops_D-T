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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 sao
            $table->text('comment')->nullable();
            $table->json('images')->nullable(); // Lưu nhiều hình ảnh
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_edited')->default(false); // Đánh dấu đã chỉnh sửa
            $table->timestamps();
            
            // Đảm bảo mỗi user chỉ đánh giá 1 sản phẩm trong 1 đơn hàng 1 lần
            $table->unique(['user_id', 'product_id', 'order_id']);        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
