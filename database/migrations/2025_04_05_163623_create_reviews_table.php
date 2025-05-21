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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Liên kết với người dùng (khách hàng)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Liên kết với sản phẩm
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Đánh giá (số điểm từ 1 đến 5)
            $table->integer('rating')->default(5); // Rating từ 1 đến 5

            // Bình luận của người dùng
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
