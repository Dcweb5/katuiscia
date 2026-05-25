<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'total_revenue')) {
                $table->decimal('total_revenue', 10, 2)->default(0)->after('purchased');
            }
            if (!Schema::hasColumn('leads', 'orders_count')) {
                $table->integer('orders_count')->default(0)->after('total_revenue');
            }
        });
    }
    public function down(): void {
        Schema::table('leads', fn($t) => $t->dropColumnIfExists(['total_revenue','orders_count']));
    }
};
