<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('teams', function (Blueprint $table) {
        $table->id();
        
        // Relaciones Obligatorias
        $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // El creador/líder (usuario real)

        // Datos del Equipo
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('team_logo')->nullable();
        
        // Datos del Líder (Información extra solicitada en formulario)
        $table->string('leader_name')->nullable();
        $table->string('leader_email')->nullable();
        $table->string('leader_career')->nullable();
        $table->string('leader_semester')->nullable();
        $table->string('leader_experience')->nullable();

        // Configuración del Equipo
        $table->integer('max_members')->default(5);
        $table->enum('visibility', ['public', 'private'])->default('public');
        $table->string('invite_code')->nullable()->unique(); // Para unirse a equipos privados
        
        // Requisitos
        $table->text('requirements')->nullable();
        $table->text('skills_needed')->nullable();

        $table->string('project_name')->nullable(); // Por si ya tienen nombre de proyecto
        
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
