<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // boolean, integer, json, string
            $table->timestamps();
        });

        // 2. Create loyalty_transactions table
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points'); // signed integer
            $table->string('type'); // purchase, review, signup, exchange, admin
            $table->string('description');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->timestamps();
        });

        // 3. Add user_id to coupons table
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->onDelete('cascade');
        });

        // 4. Seed default settings
        $now = now();
        DB::table('settings')->insert([
            [
                'key' => 'loyalty_enabled',
                'value' => '1',
                'type' => 'boolean',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'loyalty_points_per_euro',
                'value' => '1',
                'type' => 'integer',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'loyalty_points_per_review',
                'value' => '50',
                'type' => 'integer',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'loyalty_points_for_signup',
                'value' => '100',
                'type' => 'integer',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'loyalty_rewards',
                'value' => json_encode([
                    ['id' => 1, 'points' => 500, 'type' => 'free_shipping', 'value' => 0, 'name' => 'Livraison gratuite', 'min_order_amount' => 0],
                    ['id' => 2, 'points' => 1000, 'type' => 'fixed', 'value' => 10, 'name' => '10 € de réduction', 'min_order_amount' => 50],
                    ['id' => 3, 'points' => 2000, 'type' => 'fixed', 'value' => 25, 'name' => '25 € de réduction', 'min_order_amount' => 100]
                ]),
                'type' => 'json',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('settings');
    }
};
