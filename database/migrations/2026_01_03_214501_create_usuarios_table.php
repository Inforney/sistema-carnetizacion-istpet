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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('cedula', 10)->unique();
            $table->string('correo_institucional', 100)->unique();
            $table->string('celular', 10);
            $table->string('ciclo_nivel', 20);
            $table->enum('tipo_usuario', ['estudiante', 'graduado'])->default('estudiante');
            $table->string('foto_url', 255)->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'bloqueado'])->default('activo');
            $table->string('password');
            $table->timestamps();
            
            // Índices
            $table->index('cedula');
            $table->index('correo_institucional');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
