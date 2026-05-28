<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cart_id')) {
                $table->foreignId('cart_id')->nullable()->after('user_id')->constrained('carts')->nullOnDelete();
            }
        });
    }
    public function down(): void {
        Schema::table('orders', fn($t) => $t->dropColumnIfExists('cart_id'));
    }
};
