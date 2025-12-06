<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Datos básicos
            $table->string('title');
            $table->string('organizer');
            $table->string('location');
            
            // Mejorado: longText
            $table->longText('description');
            $table->string('email');
            $table->string('phone');
            $table->integer('max_participants');
            $table->longText('requirements')->nullable();

            // Fechas
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('image_url')->nullable();

            // Mejorado: longText
            $table->longText('documents_info')->nullable();

            // Campos extra fusionados
            $table->string('banner_url')->nullable();
            $table->string('modality')->nullable();
            $table->string('registration_link')->nullable();
            $table->string('main_category',100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
