<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesSatisfaccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveles_satisfaccion')->insert([
            ['nombre' => 'Muy Insatisfecho', 'abreviatura' => 'MI', 'valor' => 1],
            ['nombre' => 'Insatisfecho', 'abreviatura' => 'I', 'valor' => 2],
            ['nombre' => 'Neutral', 'abreviatura' => 'N', 'valor' => 3],
            ['nombre' => 'Satisfecho', 'abreviatura' => 'S', 'valor' => 4],
            ['nombre' => 'Muy Satisfecho', 'abreviatura' => 'MS', 'valor' => 5],
        ]);
    }
}
