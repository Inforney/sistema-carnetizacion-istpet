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
        Schema::table('laboratorios', function (Blueprint $table) {
            // Agregar campo tipo después de nombre
            $table->enum('tipo', ['laboratorio', 'aula_interactiva'])
                ->default('laboratorio')
                ->after('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratorios', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
