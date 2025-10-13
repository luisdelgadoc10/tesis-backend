<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica; // asegúrate de que el modelo sea el correcto

class ActividadAlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ['descripcion' => 'Almacenamiento y depósito', 'funcion_id' => 8],
            ['descripcion' => 'Estacionamiento', 'funcion_id' => 8],
        ];

        foreach ($datos as $dato) {
            ActividadEconomica::create($dato);
        }
    }
}
