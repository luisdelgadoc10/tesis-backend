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
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->string('razon_social');
            $table->string('ruc', 11)->unique(); // Perú: RUC de 11 dígitos
            $table->string('direccion')->nullable();

            // Datos de contacto
            $table->string('propietario')->nullable();
            $table->string('telefono', 9)->nullable();
            $table->string('correo_electronico')->nullable();

            // Estado por defecto 1 (activo)
            $table->boolean('estado')->default(1)->comment('1 = activo, 0 = inactivo');

            $table->softDeletes(); // 👈 borrado lógico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
