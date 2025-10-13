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
        Schema::table('clasificacion_detalles', function (Blueprint $table) {
            $table->string('riesgo_incendio')->nullable()->change();
            $table->string('riesgo_colapso')->nullable()->change();
            $table->string('riesgo_final')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clasificacion_detalles', function (Blueprint $table) {
            //
        });
    }
};
