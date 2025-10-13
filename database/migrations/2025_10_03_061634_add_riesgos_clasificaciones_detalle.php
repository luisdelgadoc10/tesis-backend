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
            $table->float('riesgo_incendio')->nullable()->after('resultado_modelo');
            $table->float('riesgo_colapso')->nullable()->after('riesgo_incendio');
            $table->float('riesgo_final')->nullable()->after('riesgo_colapso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clasificacion_detalles', function (Blueprint $table) {
            $table->dropColumn(['riesgo_incendio', 'riesgo_colapso', 'riesgo_final']);
        });
    }
};
