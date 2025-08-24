<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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
                'waiting_payment',
                'returned'
            ) DEFAULT 'unpaid'");
    }

    public function down(): void
    {
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
};
