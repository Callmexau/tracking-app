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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();
            $table->string('client_name')->nullable();

            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency')->default('XAF');

            $table->string('type'); // dépôt, retrait, transfert
            $table->string('status')->default('pending'); // pending, approved, rejected

            $table->text('comment')->nullable();

            $table->foreignId('created_by')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};