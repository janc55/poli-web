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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); 
            $table->json('day_of_week');  // Cambiado: ahora JSON para array de días
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('appointment_duration'); // en minutos
            $table->timestamps();

            // Opcional: índice para queries JSON rápidas (ej. buscar por día específico)
            $table->index(['doctor_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
