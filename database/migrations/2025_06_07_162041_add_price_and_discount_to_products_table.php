<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('discount')->default(0); // nếu cần
            $table->unsignedBigInteger('sold')->default(0); // nếu muốn thống kê bán chạy
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'discount', 'sold']);
        });
    }
};
