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
        Schema::create('profesores', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('cedula', 10)->unique();
            $table->string('correo', 100)->unique();
            $table->string('celular', 10);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->string('password');
            $table->timestamps();

            // Índices
            $table->index('cedula');
            $table->index('correo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
