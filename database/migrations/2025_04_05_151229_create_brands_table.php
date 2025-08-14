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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

             // Thông tin thương hiệu
             $table->string('name');
             $table->string('slug')->unique();
             $table->text('description')->nullable();
             $table->string('logo')->nullable(); // Logo thương hiệu
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
        Schema::dropIfExists('brands');
    }
};
