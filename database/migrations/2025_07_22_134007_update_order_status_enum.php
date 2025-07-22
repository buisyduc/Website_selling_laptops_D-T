<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'unprocessed',         -- Chưa xử lý
            'pending',             -- Đơn mới tạo
            'processing_seller',   -- Người bán đang chuẩn bị hàng
            'processing',          -- Đơn đang được giao
            'completed',           -- Đã hoàn thành
            'canceled',            -- Bị hủy
            'failed'               -- Thất bại
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'processing',
            'completed',
            'canceled',
            'failed'
        ) DEFAULT 'pending'");
    }
};
