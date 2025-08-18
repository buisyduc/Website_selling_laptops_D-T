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
        // Thêm giá trị 'canceled' vào enum payment_status
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded', 'canceled') 
            DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại enum cũ (không có 'canceled')
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status 
            ENUM('unpaid', 'pending', 'paid', 'failed', 'refunded') 
            DEFAULT 'unpaid'");
    }
};
