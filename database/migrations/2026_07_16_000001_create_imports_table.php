<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->date('date_domiciliation');
            $table->string('segment_commercial')->nullable();
            $table->string('nom_client_importateur')->nullable();
            $table->string('nom_client_exportateur')->nullable();
            $table->string('devise')->nullable();
            $table->string('reference_facture')->nullable();
            $table->decimal('montant_facture_contrat_commercial', 18, 2)->nullable();
            $table->string('montant_reglement')->nullable();
            $table->string('montant_di')->nullable();
            $table->string('ref_declaration_detail')->nullable();
            $table->string('montant_declaration_detail')->nullable();
            $table->string('ref_quittance_paiement_droits_et_taxes_douane')->nullable();
            $table->string('montant_quittance')->nullable();
            $table->string('numero_di')->nullable();
            $table->string('pays')->nullable();
            $table->date('date_apurement')->nullable();
            $table->string('mise_en_demeure')->nullable();
            $table->string('code_identification_unique_importateur')->nullable();
            $table->string('type_importation')->nullable();
            $table->string('nature_importation')->nullable();
            $table->string('ref_domiciliation')->nullable();
            $table->string('statut_apurement')->nullable();
            $table->string('vlc_ad_ah')->nullable();
            $table->string('references_mt298')->nullable();
            $table->date('date_reglement')->nullable();
            $table->string('ref_transaction')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('imports');
    }
};
