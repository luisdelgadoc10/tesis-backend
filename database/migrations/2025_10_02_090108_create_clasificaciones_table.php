<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clasificaciones', function (Blueprint $table) {
            $table->id();
    
            // Establecimiento
            $table->foreignId('establecimiento_id')
                  ->constrained('establecimientos')
                  ->onDelete('cascade');

            // Actividad Económica
            $table->foreignId('actividad_economica_id')
                  ->constrained('actividad_economica')
                  ->onDelete('restrict');

            // Función
            $table->foreignId('funcion_id')
                  ->constrained('funciones')
                  ->onDelete('restrict');

            // Usuario que crea la clasificación
            $table->foreignId('user_id')
                  ->constrained('users') // apunta a 'users.id'
                  ->onDelete('cascade'); // si se elimina el usuario, se eliminan sus clasificaciones

            $table->timestamp('fecha_clasificacion');
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clasificaciones');
    }
};