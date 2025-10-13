<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('niveles_riesgo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Ej: Bajo, Medio, Alto, Muy Alto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles_riesgo');
    }
};
