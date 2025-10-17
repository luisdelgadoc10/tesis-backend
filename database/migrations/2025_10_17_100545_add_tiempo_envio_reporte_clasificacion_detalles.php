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
            $table->float('tiempo_envio_reporte')->nullable()->after('riesgo_final');
        });
    }

    public function down(): void
    {
        Schema::table('clasificacion_detalles', function (Blueprint $table) {
            $table->dropColumn('tiempo_envio_reporte');
        });
    }
};
