<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages','name')) {
                $table->string('name')->after('id');
                $table->string('email')->after('name');
                $table->string('subject')->nullable()->after('email');
                $table->text('message')->after('subject');
                $table->boolean('is_read')->default(false)->after('message');
            }
        });
        Schema::table('home_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('home_sections','name')) {
                $table->string('name')->after('id');
                $table->string('type')->default('products')->after('name');
                $table->json('product_ids')->nullable()->after('type');
                $table->integer('order')->default(0)->after('product_ids');
                $table->boolean('is_active')->default(true)->after('order');
                $table->string('title')->nullable()->after('is_active');
                $table->string('subtitle')->nullable()->after('title');
            }
        });
    }
    public function down(): void {
        Schema::table('contact_messages', fn($t) => $t->dropColumnIfExists(['name','email','subject','message','is_read']));
        Schema::table('home_sections', fn($t) => $t->dropColumnIfExists(['name','type','product_ids','order','is_active','title','subtitle']));
    }
};
