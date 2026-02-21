<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Verificar si la columna ya existe antes de agregarla
            if (!Schema::hasColumn('usuarios', 'semestre')) {
                $table->enum('semestre', [
                    'PRIMER NIVEL',
                    'SEGUNDO NIVEL',
                    'TERCER NIVEL',
                    'CUARTO NIVEL',
                    'QUINTO NIVEL',
                    'SEXTO NIVEL'
                ])->after('carrera')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'semestre')) {
                $table->dropColumn('semestre');
            }
        });
    }
};
