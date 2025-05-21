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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Mã giảm giá (unique để đảm bảo mã này không trùng lặp)
            $table->string('code')->unique();

            // Phần trăm giảm giá
            $table->decimal('discount_percent', 5, 2);

            // Giảm giá tối đa
            $table->decimal('max_discount', 10, 2);

            // Số tiền đơn hàng tối thiểu để áp dụng mã giảm giá
            $table->decimal('min_order_amount', 10, 2);
            //Giới hạn tổng số lần mã giảm giá có thể được sử dụng.
            $table->integer('usage_limit')->nullable();
            //Đếm số lần mã giảm giá đã được sử dụng.
            $table->integer('used_count')->default(0);
            //Giới hạn mỗi người dùng chỉ được sử dụng mã này tối đa bao nhiêu lần.
            $table->integer('per_user_limit')->nullable();


            // Thời gian hết hạn của mã giảm giá
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
