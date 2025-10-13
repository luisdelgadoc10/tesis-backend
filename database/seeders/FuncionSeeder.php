<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Funcion;

class FuncionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funciones = [
            'SALUD',
            'ENCUENTRO',
            'HOSPEDAJE',
            'EDUCACION',
            'INDUSTRIAL',
            'OFICINAS ADMINISTRATIVAS',
            'COMERCIO',
            'ALMACEN',
        ];

        foreach ($funciones as $nombre) {
            Funcion::create(['nombre' => $nombre]);
        }
    }
}
