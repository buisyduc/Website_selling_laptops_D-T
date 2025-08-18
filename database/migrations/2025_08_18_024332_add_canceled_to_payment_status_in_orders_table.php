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
        // Thêm giá trị 'canceled', 'refund_pending', 'refund_canceled', 'returned_refunded', 'waiting_payment'
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM(
                'unpaid', 
                'pending', 
                'paid', 
                'failed', 
                'refunded', 
                'canceled', 
                'refund_pending', 
                'refund_canceled', 
                'returned_refunded',
                'waiting_payment'
            ) DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại enum cũ (không có 'waiting_payment')
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM(
                'unpaid', 
                'pending', 
                'paid', 
                'failed', 
                'refunded', 
                'canceled', 
                'refund_pending', 
                'refund_canceled', 
                'returned_refunded'
            ) DEFAULT 'unpaid'");
    }
};
