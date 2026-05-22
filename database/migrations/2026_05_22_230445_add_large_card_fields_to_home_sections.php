<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('home_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('home_sections', 'large_type')) {
                $table->string('large_type')->nullable()->after('collection_ids');
            }
            if (!Schema::hasColumn('home_sections', 'large_product_id')) {
                $table->foreignId('large_product_id')->nullable()->after('large_type')->constrained('products')->nullOnDelete();
            }
            if (!Schema::hasColumn('home_sections', 'large_collection_id')) {
                $table->foreignId('large_collection_id')->nullable()->after('large_product_id')->constrained('collections')->nullOnDelete();
            }
        });
    }
    public function down(): void { Schema::table('home_sections', fn($t) => $t->dropColumnIfExists(['large_type','large_product_id','large_collection_id'])); }
};
