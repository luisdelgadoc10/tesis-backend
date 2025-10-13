<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->delete();
        // Usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123', // se encripta automáticamente por el cast
            'estado' => 1,
        ]);

        User::create([
            'name' => 'Luis Delgado',
            'email' => 'luis@correo.com',
            'password' => '12345678', // se encripta automáticamente por el cast
            'estado' => 1,
        ]);

        // Usuario normal
        User::create([
            'name' => 'Usuario Normal',
            'email' => 'user@example.com',
            'password' => 'user12345',
        ]);

        // Puedes crear varios con un loop
        User::factory()->count(5)->create();
    }
}
