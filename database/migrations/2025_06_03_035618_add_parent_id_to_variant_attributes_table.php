<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variant_attributes', function (Blueprint $table) {
            // Thêm cột parent_id để làm quan hệ cha-con
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('name')
                  ->constrained('variant_attributes')
                  ->onDelete('cascade');

            // Thêm xóa mềm nếu chưa có
            if (!Schema::hasColumn('variant_attributes', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('variant_attributes', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');

            if (Schema::hasColumn('variant_attributes', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
