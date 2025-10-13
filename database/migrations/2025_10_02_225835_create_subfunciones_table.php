<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subfunciones', function (Blueprint $table) {
            $table->id();

            // Relación con la tabla funciones
            $table->foreignId('funcion_id')->constrained('funciones')->onDelete('cascade');

            // Código como I-1, 4.1, etc
            $table->string('codigo', 20)->unique();

            // Descripción de la subfunción
            $table->text('descripcion');

            // Riesgos asociados
            $table->foreignId('riesgo_incendio')->constrained('niveles_riesgo');
            $table->foreignId('riesgo_colapso')->constrained('niveles_riesgo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subfunciones');
    }
};
