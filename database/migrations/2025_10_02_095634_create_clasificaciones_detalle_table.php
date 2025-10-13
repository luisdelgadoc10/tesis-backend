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
        Schema::create('clasificacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clasificacion_id')
                  ->constrained('clasificaciones')
                  ->onDelete('cascade'); // elimina detalles si se elimina la clasificación

            $table->json('datos_entrada')->nullable();   // parámetros enviados al modelo ML
            $table->json('resultado_modelo')->nullable(); // resultado del modelo ML

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clasificacion_detalles', function (Blueprint $table) {
            $table->dropUnique(['clasificacion_id']);
        });
        Schema::dropIfExists('clasificacion_detalles');
    }
};
