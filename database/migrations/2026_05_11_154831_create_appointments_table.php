<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('type')->default('individu'); // individu, entreprise
            $table->string('company_name')->nullable();
            $table->string('siret')->nullable();
            $table->string('source'); // formation, grossiste
            $table->text('message')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('status')->default('en_attente'); // en_attente, confirme, refuse
            $table->date('appointment_date')->nullable();
            $table->string('appointment_time')->nullable();
            $table->text('admin_instructions')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
