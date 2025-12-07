<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('lastname')->nullable(); // Tu campo personalizado
        $table->string('email')->unique();
        $table->string('phone')->nullable();    // Tu campo personalizado
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        
        // Roles: Define quién es quién
        $table->enum('role', ['admin', 'judge', 'student'])->default('student');
        

        $table->rememberToken();
        $table->timestamps();
    });
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
