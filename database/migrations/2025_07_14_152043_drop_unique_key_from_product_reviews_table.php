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
        // Tạo bảng tạm không có ràng buộc unique
        Schema::table('product_reviews', function (Blueprint $table) {
            // 1. Xóa foreign key constraint trước (nếu có)
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['order_id']);
            
            // 2. Sau đó mới xóa unique index
            $table->dropUnique('product_reviews_user_id_product_id_order_id_unique');
            
            // 3. Tạo lại foreign key (nếu cần)
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            // Đảo ngược lại khi rollback
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['order_id']);
            
            $table->unique(['user_id', 'product_id', 'order_id']);
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }
};
