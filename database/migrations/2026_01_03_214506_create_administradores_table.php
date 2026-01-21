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
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('rol', ['super_admin', 'admin'])->default('admin');
            $table->timestamps();

            // Índices
            $table->index('usuario');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};
