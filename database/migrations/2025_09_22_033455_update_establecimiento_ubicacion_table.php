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
        Schema::table('establecimientos', function (Blueprint $table) {
            // Campos de ubicación
            $table->decimal('latitud', 10, 7)->nullable()->after('direccion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');

            // Clave foránea hacia actividad_economica
            $table->unsignedBigInteger('actividad_economica_id')->nullable()->after('longitud');

            $table->foreign('actividad_economica_id')
                  ->references('id')
                  ->on('actividad_economica') // 👈 asegúrate del nombre real de la tabla
                  ->onDelete('set null');     // si se elimina la actividad, el establecimiento queda libre
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropForeign(['actividad_economica_id']);
            $table->dropColumn(['latitud', 'longitud', 'actividad_economica_id']);
        });
    }
};
