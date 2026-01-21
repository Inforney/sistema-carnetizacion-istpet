<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('administradores')->insert([
            [
                'usuario' => 'admin',
                'email' => 'admin@istpet.edu.ec',
                'password' => Hash::make('IstpetAdmin2026!'),
                'rol' => 'super_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usuario' => 'admin2',
                'email' => 'admin2@istpet.edu.ec',
                'password' => Hash::make('Admin2026!'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
