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

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('leader_name');
            $table->string('leader_email');
            $table->string('leader_career');
            $table->string('leader_semester');

            // Mejorado: longText
            $table->longText('leader_experience')->nullable();

            $table->integer('max_members');

            $table->enum('visibility',['Privado','Público']);

            // Mejorado: longText
            $table->longText('requirements')->nullable();

            $table->string('invite_code')->unique();

            // Extra campos fusionados
            $table->string('team_logo')->nullable();
            $table->longText('description')->nullable();
            $table->longText('skills_needed')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
