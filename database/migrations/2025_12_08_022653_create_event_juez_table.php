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
        Schema::create('event_juez', function (Blueprint $table) {
            $table->id();
            
            // --- ESTAS SON LAS LÍNEAS IMPORTANTES ---
            // Relación con la tabla de eventos
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            
            // Relación con la tabla de usuarios (jueces)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // ----------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_juez');
    }
};