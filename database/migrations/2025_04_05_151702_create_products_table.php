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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
           // Thông tin cơ bản
           $table->string('name');
           $table->string('slug')->unique();
           $table->text('description')->nullable();

           // Hình ảnh đại diện (mà không cần liên kết khóa ngoại với bảng product_images)
           $table->string('image')->nullable();

           // Liên kết khóa ngoại với bảng categories và brands
           $table->foreignId('category_id')->constrained()->onDelete('cascade');
           $table->foreignId('brand_id')->constrained()->onDelete('cascade');

           // Một số thông tin phụ trợ
           $table->date('release_date')->nullable();

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
        Schema::dropIfExists('products');
    }
};
