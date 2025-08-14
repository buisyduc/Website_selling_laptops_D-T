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
        Schema::table('orders', function (Blueprint $table) {
            // Thay đổi độ rộng của total_amount, ví dụ: từ DECIMAL(8,2) → DECIMAL(15,2)
            $table->decimal('total_amount', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Quay lại kiểu cũ (nếu biết rõ kiểu cũ là bao nhiêu, ví dụ 8,2)
            $table->decimal('total_amount', 10, 2)->change();
        });
    }
};
