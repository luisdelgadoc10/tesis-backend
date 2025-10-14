<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            UserSeeder::class,
        ]);

        $this->call([
            FuncionSeeder::class,
        ]);

        $this->call([
            ActividadSaludSeeder::class,
        ]);

        $this->call([
            ActividadEncuentroSeeder::class,
        ]);

        $this->call([
            ActividadHospedajeSeeder::class,
        ]);
        $this->call([
            ActividadEducacionSeeder::class,
        ]);
        $this->call([
            ActividadIndustrialSeeder::class,
        ]);

        $this->call([
            ActividadOficinasSeeder::class,
        ]);

        $this->call([
            ActividadComercioSeeder::class,
        ]);

        $this->call([
            ActividadAlmacenSeeder::class,
        ]);

        $this->call([
            NivelesRiesgoSeeder::class,
        ]);

        $this->call([
            SubfuncionSeeder::class,
        ]);

        this->call([
            PermissionSeeder::class,
        ]);

        this->call([
            RolSeeder::class,
        ]);

        this->call([
            RolePermissionSeeder::class,
        ]);

        this->call([
            UserRoleSeeder::class,
        ]);

        this->call([
            NivelesSatisfaccionSeeder::class,
        ]);

        this->call([
            PreguntasSeeder::class,
        ]);


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
