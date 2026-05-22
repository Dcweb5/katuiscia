<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('home_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('home_sections', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('home_sections', 'type')) {
                $table->string('type')->default('hero')->after('name');
            }
            if (!Schema::hasColumn('home_sections', 'product_ids')) {
                $table->json('product_ids')->nullable()->after('type');
            }
            if (!Schema::hasColumn('home_sections', 'collection_ids')) {
                $table->json('collection_ids')->nullable()->after('product_ids');
            }
            if (!Schema::hasColumn('home_sections', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('collection_ids');
            }
        });
    }
    public function down(): void {
        Schema::table('home_sections', fn($t) => $t->dropColumnIfExists(['name','type','product_ids','collection_ids','is_active']));
    }
};
