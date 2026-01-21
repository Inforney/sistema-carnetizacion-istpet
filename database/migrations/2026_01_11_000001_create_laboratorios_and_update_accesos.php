<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Crear tabla laboratorios si no existe
        if (!Schema::hasTable('laboratorios')) {
            Schema::create('laboratorios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('codigo_qr', 50)->unique();
                $table->string('ubicacion', 200)->nullable();
                $table->integer('capacidad')->default(30);
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->timestamps();
            });
        }

        // Insertar los 3 laboratorios
        DB::table('laboratorios')->insert([
            [
                'nombre' => 'Laboratorio de Computación 1',
                'codigo_qr' => 'LAB-ISTPET-001',
                'ubicacion' => 'Edificio A - Piso 2',
                'capacidad' => 30,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Laboratorio de Computación 2',
                'codigo_qr' => 'LAB-ISTPET-002',
                'ubicacion' => 'Edificio A - Piso 2',
                'capacidad' => 30,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Laboratorio de Redes',
                'codigo_qr' => 'LAB-ISTPET-003',
                'ubicacion' => 'Edificio B - Piso 1',
                'capacidad' => 25,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Agregar campos a la tabla accesos
        if (Schema::hasTable('accesos')) {
            Schema::table('accesos', function (Blueprint $table) {
                if (!Schema::hasColumn('accesos', 'metodo_registro')) {
                    $table->enum('metodo_registro', ['qr_estudiante', 'manual_profesor'])
                        ->default('manual_profesor')
                        ->after('hora_salida');
                }

                if (!Schema::hasColumn('accesos', 'marcado_ausente')) {
                    $table->boolean('marcado_ausente')->default(false)->after('metodo_registro');
                }

                if (!Schema::hasColumn('accesos', 'nota_ausencia')) {
                    $table->text('nota_ausencia')->nullable()->after('marcado_ausente');
                }

                if (!Schema::hasColumn('accesos', 'profesor_valida_id')) {
                    $table->unsignedBigInteger('profesor_valida_id')->nullable()->after('nota_ausencia');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('laboratorios');

        if (Schema::hasTable('accesos')) {
            Schema::table('accesos', function (Blueprint $table) {
                $table->dropColumn(['metodo_registro', 'marcado_ausente', 'nota_ausencia', 'profesor_valida_id']);
            });
        }
    }
};
