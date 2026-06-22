<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            // L'agent qui a fait l'action (lié à ta table users)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // Ex: 'user.updated', 'profile.password'
            $table->text('description'); // Ex: "L'utilisateur X a modifié le rôle de Y"
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};