<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('estado')->default(1); // 1 = activo, 0 = inactivo
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean('estado')->default(1);
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('estado');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
