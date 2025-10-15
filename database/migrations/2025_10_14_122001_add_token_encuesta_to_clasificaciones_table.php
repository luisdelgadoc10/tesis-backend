<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clasificaciones', function (Blueprint $table) {
            $table->string('token_encuesta')->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('clasificaciones', function (Blueprint $table) {
            $table->dropColumn('token_encuesta');
        });
    }
};
