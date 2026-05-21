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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('id');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->string('postal_code', 20)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('postal_code');
            $table->boolean('newsletter')->default(false)->after('country');
            $table->date('birthday')->nullable()->after('newsletter');
            $table->string('avatar')->nullable()->after('birthday');
            $table->boolean('is_admin')->default(false)->after('avatar');
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->integer('loyalty_points')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname', 'lastname', 'phone', 'city', 'postal_code',
                'country', 'newsletter', 'birthday', 'avatar',
                'is_admin', 'is_active', 'loyalty_points',
            ]);
        });
    }
};
