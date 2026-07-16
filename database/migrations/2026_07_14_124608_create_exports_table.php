<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exports', function (Blueprint $table) {

            $table->id();

            // Informations générales
            $table->string('numero')->unique();
            $table->string('nom_exportateur');
            $table->date('date_domiciliation');
            $table->string('reference_domiciliation')->unique();
            $table->string('reference_facture_contrat');
            $table->string('devise', 10);

            // Montants
            $table->decimal('montant_facture', 18, 2);
            $table->decimal('montant_reglement', 18, 2)->nullable();

            // Bon à embarquer
            $table->string('reference_bon_embarquer')->nullable();
            $table->decimal('montant_bon_embarquer', 18, 2)->nullable();

            // Quittance
            $table->string('reference_quittance')->nullable();
            $table->decimal('montant_quittance', 18, 2)->nullable();

            // Nature
            $table->enum('nature_exportation', [
                'Bien',
                'Service'
            ]);

            // Suivi
            $table->date('date_ouverture_dossier');
            $table->date('date_echeance_contrat')->nullable();
            $table->date('date_effective_exportation')->nullable();
            $table->date('date_rapatriement')->nullable();
            $table->date('date_retrocession_beac')->nullable();
            $table->date('date_apurement')->nullable();

            // Statut
            $table->enum('statut_dossier', [
                'Non apuré',
                'En cours',
                'Apuré'
            ])->default('Non apuré');

            // Divers
            $table->text('commentaire')->nullable();

            // Traçabilité
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            // Index
            $table->index('numero');
            $table->index('reference_domiciliation');
            $table->index('nom_exportateur');
            $table->index('statut_dossier');
            $table->index('date_domiciliation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exports');
    }
};
