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
        
        // El creador del evento (Admin)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->string('title'); // Usamos 'title' como en tu modelo
        $table->string('organizer')->nullable();
        $table->string('location')->nullable();
        $table->text('description')->nullable();
        
        // Contacto
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        
        // Detalles
        $table->integer('max_participants')->nullable();
        $table->text('requirements')->nullable();
        
        // Fechas y Horas
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->time('start_time')->nullable();
        $table->time('end_time')->nullable();
        
        // Archivos e Imágenes (URLs)
        $table->string('image_url')->nullable();
        $table->string('banner_url')->nullable();
        $table->string('documents_info')->nullable();
        
        // Configuración extra
        $table->string('modality')->default('Presencial'); // Presencial/Virtual
        $table->string('registration_link')->nullable();
        $table->string('main_category')->nullable(); // Categoría principal (texto)

        // Estado para lógica del sistema
        $table->boolean('is_active')->default(true); 

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
