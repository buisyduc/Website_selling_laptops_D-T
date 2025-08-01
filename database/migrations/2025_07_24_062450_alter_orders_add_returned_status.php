<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'unprocessed',
            'pending',
            'processing_seller',
            'processing',
            'completed',
            'canceled',
            'failed',
            'returned',
            'shipping'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Rollback về trạng thái không có 'returned'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'unprocessed',
            'pending',
            'processing_seller',
            'processing',
            'completed',
            'canceled',
            'failed',
             'returned'
        ) DEFAULT 'pending'");
    }
};
