<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add 'cancel_refund' to enum values of order_returns.type
        DB::statement("ALTER TABLE `order_returns` MODIFY COLUMN `type` ENUM('return','return_refund','cancel_refund') NOT NULL DEFAULT 'return'");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE `order_returns` MODIFY COLUMN `type` ENUM('return','return_refund') NOT NULL DEFAULT 'return'");
    }
};
