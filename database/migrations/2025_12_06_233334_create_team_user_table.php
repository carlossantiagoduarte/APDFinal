<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();

            // Llaves foráneas para conectar Usuario y Equipo
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Opcional: Rol dentro del equipo (ej: 'lider', 'miembro')
            $table->string('role')->default('member');
            
            // Opcional: Estado (para saber si el líder lo aceptó o sigue pendiente)
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->timestamps(); // Fecha de unión
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};