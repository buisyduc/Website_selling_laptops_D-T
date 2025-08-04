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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            //Mỗi người dùng chỉ được wishlist 1 lần cho mỗi sản phẩm.
            $table->unique(['user_id', 'product_id']);

            // Liên kết với người dùng (khách hàng)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Liên kết với sản phẩm
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
