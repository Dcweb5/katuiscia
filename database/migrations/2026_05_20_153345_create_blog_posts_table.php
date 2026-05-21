<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('image')->nullable();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('status')->default('draft'); // draft, published
            $table->string('tags')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('blog_posts');
    }
};
