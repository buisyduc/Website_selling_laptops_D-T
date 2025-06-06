<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            // thêm cột status, kiểu INT (0=inactive, 1=active)
            $table->tinyInteger('status')->default(1)->after('slug');
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
