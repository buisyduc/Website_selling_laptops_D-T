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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_transaction_id')->nullable()->after('payment_status');
        $table->text('payment_redirect_url')->nullable()->after('payment_transaction_id');
        $table->longText('payment_response_data')->nullable()->after('payment_redirect_url');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'payment_transaction_id',
            'payment_redirect_url',
            'payment_response_data',
        ]);
    });
}

};
