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
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        
        // ¿A qué equipo califican?
        $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
        
        // ¿Quién es el juez?
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // Puntuación
        $table->integer('score');
        
        // Feedback
        $table->text('feedback')->nullable();
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
