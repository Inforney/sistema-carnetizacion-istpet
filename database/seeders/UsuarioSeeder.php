<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 5 estudiantes
        $usuarios = [
            [
                'nombres' => 'Kevin Gabriel',
                'apellidos' => 'Huilca Campaña',
                'cedula' => '1750123456',
                'correo_institucional' => 'kevin.huilca@istpet.edu.ec',
                'celular' => '0991234567',
                'ciclo_nivel' => 'TERCER NIVEL',
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make('Estudiante2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Ana María',
                'apellidos' => 'López Torres',
                'cedula' => '1751234567',
                'correo_institucional' => 'ana.lopez@istpet.edu.ec',
                'celular' => '0992345678',
                'ciclo_nivel' => 'TERCER NIVEL',
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make('Estudiante2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Diego',
                'apellidos' => 'Fernández Ruiz',
                'cedula' => '1752345678',
                'correo_institucional' => 'diego.fernandez@istpet.edu.ec',
                'celular' => '0993456789',
                'ciclo_nivel' => 'SEGUNDO NIVEL',
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make('Estudiante2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Sofía',
                'apellidos' => 'Mendoza Castro',
                'cedula' => '1753456789',
                'correo_institucional' => 'sofia.mendoza@istpet.edu.ec',
                'celular' => '0994567890',
                'ciclo_nivel' => 'TERCER NIVEL',
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make('Estudiante2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombres' => 'Andrés',
                'apellidos' => 'Vargas Moreno',
                'cedula' => '1754567890',
                'correo_institucional' => 'andres.vargas@istpet.edu.ec',
                'celular' => '0995678901',
                'ciclo_nivel' => 'PRIMER NIVEL',
                'tipo_usuario' => 'estudiante',
                'estado' => 'activo',
                'password' => Hash::make('Estudiante2026!'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($usuarios as $usuario) {
            // Insertar usuario
            $usuarioId = DB::table('usuarios')->insertGetId($usuario);

            // Crear carnet para cada usuario
            DB::table('carnets')->insert([
                'usuario_id' => $usuarioId,
                'codigo_qr' => 'ISTPET-CARNET-' . Str::uuid(),
                'fecha_emision' => now()->toDateString(),
                'fecha_vencimiento' => null, // Estudiantes activos no tienen vencimiento
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
