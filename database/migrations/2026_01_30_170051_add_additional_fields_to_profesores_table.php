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
        Schema::table('profesores', function (Blueprint $table) {
            $table->string('especialidad', 150)->nullable()->after('celular');
            $table->date('fecha_ingreso')->nullable()->after('especialidad');
            $table->string('foto_url')->nullable()->after('fecha_ingreso');
            $table->string('horario')->nullable()->after('foto_url');
            $table->string('departamento', 100)->nullable()->after('horario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesores', function (Blueprint $table) {
            $table->dropColumn(['especialidad', 'fecha_ingreso', 'foto_url', 'horario', 'departamento']);
        });
    }
};
