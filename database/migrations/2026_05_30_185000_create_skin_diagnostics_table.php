<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skin_diagnostics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('skin_type');
            $table->string('concern');
            $table->text('current_products')->nullable();
            $table->string('image_path');
            $table->json('analysis_result'); // Contains: imperfections, severity, consult_specialist, analysis_details, recommended_products, general_advice
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skin_diagnostics');
    }
};
