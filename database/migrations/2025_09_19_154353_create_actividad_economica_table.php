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
        Schema::create('actividad_economica', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');    // descripcion de la actividad económica
            $table->foreignId('funcion_id')->constrained('funciones')->onDelete('cascade'); 
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_economica');
    }
};
