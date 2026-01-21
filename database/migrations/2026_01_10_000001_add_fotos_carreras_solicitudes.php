<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar y agregar campos a tabla usuarios
        if (!Schema::hasColumn('usuarios', 'carrera')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('carrera', 200)->nullable()->after('ciclo_nivel');
            });
        }

        if (!Schema::hasColumn('usuarios', 'foto_url')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('foto_url', 255)->nullable()->after('carrera');
            });
        }

        // Crear tabla de solicitudes de cambio de contraseña si no existe
        if (!Schema::hasTable('solicitudes_password')) {
            Schema::create('solicitudes_password', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->string('tipo_usuario', 50); // estudiante, profesor
                $table->string('documento', 20);
                $table->string('correo', 100);
                $table->enum('estado', ['pendiente', 'atendida', 'rechazada'])->default('pendiente');
                $table->unsignedBigInteger('atendida_por_admin_id')->nullable();
                $table->text('notas_admin')->nullable();
                $table->timestamps();

                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
                $table->index(['estado', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('usuarios', 'foto_url')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('foto_url');
            });
        }

        if (Schema::hasColumn('usuarios', 'carrera')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('carrera');
            });
        }

        Schema::dropIfExists('solicitudes_password');
    }
};
