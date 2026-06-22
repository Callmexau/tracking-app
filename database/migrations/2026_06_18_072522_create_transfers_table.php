<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {

            $table->id();

            // Informations générales
            $table->date('date_depot');
            $table->string('segment_clientele')->nullable();
            $table->string('ref_n98')->unique();
            $table->string('donneur_ordre');
            $table->string('beneficiaire');
            $table->string('devise', 10);

            // Montants
            $table->decimal('montant_ordre', 18, 2)->default(0);
            $table->decimal('montant_devise_prefinance', 18, 2)->nullable();

            // Suivi dossier
            $table->string('situation_dossier')->nullable(); 
            $table->string('numero_allocation', 50)->nullable(); 

            // Processus BEAC
            $table->date('date_envoi_beac')->nullable();
            $table->date('date_decision')->nullable();
            $table->enum('decision_beac', [
                'Favorable',
                'Rejet',
                'Suspens BEAC'
            ])->nullable();

            $table->date('date_reception_mt999')->nullable();
            $table->date('date_envoi_couverture_xaf')->nullable();
            $table->date('date_reception_devise')->nullable();
            $table->date('date_traitement')->nullable();
            $table->date('conditions_reunies_le')->nullable();

            // Référence transaction & Délais
            $table->string('reference_transaction')->nullable();
            $table->string('delai_traitement')->nullable(); 
            $table->text('commentaire')->nullable();

            // Statut global du dossier
            $table->enum('statut', [
                'Non traité',
                'Traité',
                'Rejet'
            ])->default('Non traité');

            // Traçabilité
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            // Index de recherche
            $table->index('ref_n98');
            $table->index('statut');
            $table->index('decision_beac');
            $table->index('date_depot');
            $table->index('date_traitement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};