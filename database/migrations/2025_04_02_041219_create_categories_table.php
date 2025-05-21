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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable(); // Mô tả có thể để trống
            $table->string('image')->nullable(); // Ảnh có thể để trống
            $table->unsignedBigInteger('parent_id')->nullable(); // Danh mục cha
            $table->boolean('status')->default(1); // 1: Hiển thị, 0: Ẩn
            $table->integer('order')->default(0); // Thứ tự hiển thị
            // Thêm cột deleted_at để hỗ trợ xóa mềm
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
