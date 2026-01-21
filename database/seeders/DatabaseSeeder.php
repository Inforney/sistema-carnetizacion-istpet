<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdministradorSeeder::class,
            ProfesorSeeder::class,
            LaboratorioSeeder::class,
            UsuarioSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Base de datos poblada exitosamente!');
        $this->command->info('');
        $this->command->info('🔑 Credenciales de acceso:');
        $this->command->info('');
        $this->command->info('👤 ADMINISTRADOR:');
        $this->command->info('   Usuario: admin');
        $this->command->info('   Password: IstpetAdmin2026!');
        $this->command->info('');
        $this->command->info('👨‍🏫 PROFESOR:');
        $this->command->info('   Cédula: 1711223344');
        $this->command->info('   Password: Profesor2026!');
        $this->command->info('');
        $this->command->info('👨‍🎓 ESTUDIANTE:');
        $this->command->info('   Cédula: 1750123456');
        $this->command->info('   Password: Estudiante2026!');
        $this->command->info('');
    }
}
