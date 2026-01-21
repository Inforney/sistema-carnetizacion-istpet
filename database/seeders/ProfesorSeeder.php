<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('profesores')->insert([
            [
                'nombres' => 'Carlos',
                'apellidos' => 'Ramírez',
                'cedula' => '1711223344',
                'correo' => 'carlos.ramirez@istpet.edu.ec',
                'celular' => '0998877665',
                'estado' => 'activo',
                'password' => Hash::make('Profesor2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'María',
                'apellidos' => 'González',
                'cedula' => '1722334455',
                'correo' => 'maria.gonzalez@istpet.edu.ec',
                'celular' => '0987766554',
                'estado' => 'activo',
                'password' => Hash::make('Profesor2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Juan',
                'apellidos' => 'Morales',
                'cedula' => '1733445566',
                'correo' => 'juan.morales@istpet.edu.ec',
                'celular' => '0976655443',
                'estado' => 'activo',
                'password' => Hash::make('Profesor2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
