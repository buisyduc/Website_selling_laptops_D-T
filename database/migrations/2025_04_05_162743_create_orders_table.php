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
            // Liên kết với người dùng (khách hàng)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //Lienn ket voi bang giam gia de biet down hang do dang dung ma giam gia nao
          $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('cascade');


            // Mã đơn hàng
            $table->string('order_code')->unique(); // Mã đơn hàng, đảm bảo là duy nhất

            // Tổng số tiền trong đơn hàng
            $table->decimal('total_amount', 10, 2);

            // Trạng thái đơn hàng (ví dụ: pending, processing, completed, canceled)
            $table->enum('status', ['pending', 'processing', 'completed', 'canceled', 'failed'])->default('pending');

            // Phương thức thanh toán (ví dụ: credit card, cash on delivery, etc.)
            $table->string('payment_method');
            //Trạng thái thanh toán của đơn hàng
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'refunded'])
                ->default('unpaid');
            // Địa chỉ giao hàng
            $table->string('shipping_address');

            // Ghi chú thêm về đơn hàng (nếu có)
            $table->text('note')->nullable();
            // Thêm cột deleted_at để hỗ trợ xóa mềm
            $table->softDeletes();
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
