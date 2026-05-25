<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'abandoned_at')) {
                $table->timestamp('abandoned_at')->nullable()->after('session_id');
            }
            if (!Schema::hasColumn('carts', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable()->after('abandoned_at');
            }
            if (!Schema::hasColumn('carts', 'email')) {
                $table->string('email')->nullable()->after('email_sent_at');
            }
        });

        // Track email sequences for leads
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'email_sequence_step')) {
                $table->integer('email_sequence_step')->default(0)->after('last_emailed_at');
            }
        });
    }
    public function down(): void {
        Schema::table('carts', fn($t) => $t->dropColumnIfExists(['abandoned_at','email_sent_at','email']));
        Schema::table('leads', fn($t) => $t->dropColumnIfExists('email_sequence_step'));
    }
};
