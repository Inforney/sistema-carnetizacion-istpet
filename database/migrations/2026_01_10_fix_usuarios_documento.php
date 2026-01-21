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
            // Cambiar cedula a VARCHAR para soportar 0 inicial y pasaportes
            $table->string('cedula', 20)->change();

            // Agregar tipo de documento
            $table->enum('tipo_documento', ['cedula', 'pasaporte', 'ruc'])->default('cedula')->after('cedula');

            // Agregar token para recuperación de contraseña
            $table->string('reset_token', 100)->nullable()->after('password');
            $table->timestamp('reset_token_expira')->nullable()->after('reset_token');

            // Agregar nacionalidad
            $table->string('nacionalidad', 50)->default('Ecuatoriana')->after('tipo_usuario');

            // Agregar fecha de registro
            $table->timestamp('fecha_registro')->nullable()->after('nacionalidad');
        });

        // Asegurar que la cédula sea única
        Schema::table('usuarios', function (Blueprint $table) {
            $table->unique('cedula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropUnique(['cedula']);
            $table->dropColumn(['tipo_documento', 'reset_token', 'reset_token_expira', 'nacionalidad', 'fecha_registro']);
        });
    }
};
