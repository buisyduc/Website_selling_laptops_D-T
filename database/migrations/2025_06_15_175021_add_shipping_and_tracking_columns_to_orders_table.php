<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->string('name')->after('user_id');
            // $table->string('email')->after('name');
            // $table->string('phone')->after('email');
            $table->string('shipping_method')->nullable()->after('shipping_address');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('shipping_method');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_fee');
            $table->date('expected_delivery_date')->nullable()->after('note');

            $table->timestamp('confirmed_at')->nullable()->after('expected_delivery_date');
            $table->timestamp('shipped_at')->nullable()->after('confirmed_at');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                // 'name',
                // 'email',
                // 'phone',
                'shipping_method',
                'shipping_fee',
                'discount_amount',
                'expected_delivery_date',
                'confirmed_at',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
            ]);
        });
    }
};
