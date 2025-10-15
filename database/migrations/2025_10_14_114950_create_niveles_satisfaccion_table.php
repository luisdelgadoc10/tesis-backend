<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles_satisfaccion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('abreviatura', 5);
            $table->unsignedTinyInteger('valor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles_satisfaccion');
    }
};
