<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Add columns if they do not exist
            if (!Schema::hasColumn('comments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('comments', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('comments', 'comment')) {
                $table->text('comment')->nullable()->after('product_id');
            }
        });

        Schema::table('comments', function (Blueprint $table) {
            // Add foreign keys separately to avoid issues if columns already exist
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (Schema::hasColumn('comments', 'product_id')) {
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Drop foreign keys if exist
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}

            // Drop columns
            if (Schema::hasColumn('comments', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('comments', 'product_id')) {
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
