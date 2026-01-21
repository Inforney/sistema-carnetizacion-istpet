<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaboratorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laboratorios = [
            [
                'nombre' => 'Laboratorio de Computación 1',
                'capacidad' => 30,
                'ubicacion' => 'Edificio A - Piso 2',
                'codigo_qr_lab' => 'ISTPET-LAB-' . Str::uuid(),
                'estado' => 'disponible',
            ],
            [
                'nombre' => 'Laboratorio de Computación 2',
                'capacidad' => 25,
                'ubicacion' => 'Edificio A - Piso 2',
                'codigo_qr_lab' => 'ISTPET-LAB-' . Str::uuid(),
                'estado' => 'disponible',
            ],
            [
                'nombre' => 'Laboratorio de Redes',
                'capacidad' => 28,
                'ubicacion' => 'Edificio B - Piso 1',
                'codigo_qr_lab' => 'ISTPET-LAB-' . Str::uuid(),
                'estado' => 'disponible',
            ],
        ];

        foreach ($laboratorios as $laboratorio) {
            $laboratorio['created_at'] = now();
            $laboratorio['updated_at'] = now();
            DB::table('laboratorios')->insert($laboratorio);
        }
    }
}
