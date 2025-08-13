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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('ci')->nullable(); // Carnet de identidad
            $table->string('ci_extension')->nullable(); // Extensión del CI
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable(); // M/F/Otro
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('license_number')->unique();
            $table->text('bio')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
