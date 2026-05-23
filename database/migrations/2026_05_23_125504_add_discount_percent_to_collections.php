<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('collections', function (Blueprint $table) {
            if (!Schema::hasColumn('collections', 'discount_percent')) {
                $table->integer('discount_percent')->default(0)->after('original_price');
            }
        });
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'collection_id')) {
                $table->foreignId('collection_id')->nullable()->after('product_id')->constrained('collections')->nullOnDelete();
            }
        });
    }
    public function down(): void {
        Schema::table('collections', fn($t) => $t->dropColumnIfExists('discount_percent'));
        Schema::table('cart_items', fn($t) => $t->dropColumnIfExists('collection_id'));
    }
};
