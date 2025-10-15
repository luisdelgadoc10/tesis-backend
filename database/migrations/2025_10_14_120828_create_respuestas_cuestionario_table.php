<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_cuestionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clasificacion_id')->constrained('clasificaciones')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->foreignId('nivel_satisfaccion_id')->constrained('niveles_satisfaccion')->onDelete('cascade');
            $table->dateTime('fecha_cuestionario')->nullable(); // 🕒 fecha del envío o registro de la encuesta
            $table->timestamps();

            // Evita duplicados: cada pregunta solo una vez por clasificación
            $table->unique(['clasificacion_id', 'pregunta_id'], 'unique_respuesta_por_pregunta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_cuestionario');
    }
};
