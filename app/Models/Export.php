<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'numero',
        'nom_exportateur',
        'date_domiciliation',
        'reference_domiciliation',
        'reference_facture_contrat',
        'devise',
        'montant_facture',
        'montant_reglement',
        'reference_bon_embarquer',
        'montant_bon_embarquer',
        'reference_quittance',
        'montant_quittance',
        'nature_exportation',
        'date_ouverture_dossier',
        'date_echeance_contrat',
        'date_effective_exportation',
        'date_rapatriement',
        'date_retrocession_beac',
        'date_apurement',
        'statut_dossier',
        'commentaire',
        'created_by',
    ];

    protected $casts = [
        'date_domiciliation' => 'date',
        'date_ouverture_dossier' => 'date',
        'date_echeance_contrat' => 'date',
        'date_effective_exportation' => 'date',
        'date_rapatriement' => 'date',
        'date_retrocession_beac' => 'date',
        'date_apurement' => 'date',

        'montant_facture' => 'decimal:2',
        'montant_reglement' => 'decimal:2',
        'montant_bon_embarquer' => 'decimal:2',
        'montant_quittance' => 'decimal:2',
    ];
}
