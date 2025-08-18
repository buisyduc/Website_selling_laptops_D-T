<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm giá trị 'canceled', 'refund_pending', 'refund_canceled' vào enum payment_status
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded', 'canceled', 'refund_pending', 'refund_canceled') 
            DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại enum cũ (không có refund_pending, refund_canceled)
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded', 'canceled','refund_pending') 
            DEFAULT 'unpaid'");
    }
};
