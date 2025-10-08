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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // Identificación y Tipo
            $table->enum('type', ['promotion', 'campaign'])->default('promotion'); // 👈 Nuevo: Diferencia entre promoción simple y campaña compleja
            $table->string('slug')->unique(); // 👈 Nuevo: Para URLs limpias (ej: /promociones/campana-vacunacion)
            $table->string('title', 150); // Título de la Oferta (más conciso)
            $table->string('short_description', 255)->nullable(); // 👈 Nuevo: Para cards en la landing (el 'subtitle')

            // Contenido Principal (Para la página dedicada)
            $table->longText('full_description')->nullable(); // 👈 Nuevo: Contenido rico (HTML/Markdown) para la página dedicada de la campaña
            $table->string('image_url')->nullable(); // 👈 Nuevo: Imagen principal o banner

            // Detalle de la Oferta y Validez
            $table->decimal('discount_percentage', 5, 2)->nullable(); // 👈 Mejorado: Mejor que un string, para cálculos si es necesario
            $table->string('discount_details')->nullable(); // 👈 Nuevo: Si es "2x1", "consulta gratis", etc. (mantiene la flexibilidad del 'discount' original)
            $table->date('start_date'); // 👈 Nuevo: Cuando comienza a ser válida/visible
            $table->date('end_date')->nullable(); // 👈 Mejorado: Una fecha `date` es mejor para lógica de expiración que un string.
            $table->string('validity_notes')->nullable(); // Ej: 'Válido solo en sede central', 'Cupos limitados' (similar al 'valid_until' original)

            // Estado
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // 👈 Nuevo: Para destacar en la landing page principal

            // Metadatos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
