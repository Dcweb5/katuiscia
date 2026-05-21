<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->integer('stock')->default(0);
            $table->string('badge')->nullable(); // Best-seller, Nouveau, etc.
            $table->string('need')->nullable();   // hydratation, eclat, restauration
            $table->string('image_primary')->nullable();   // image 1a (hover primary)
            $table->string('image_secondary')->nullable(); // image 1b (hover secondary)
            $table->text('ingredients')->nullable();
            $table->string('size')->nullable();   // 50ml, 100ml, etc.
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Table pivot : product + category
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['category_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
    }
};
