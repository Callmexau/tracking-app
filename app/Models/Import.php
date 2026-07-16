<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Import extends Model
{
    protected $fillable = [
        'date_domiciliation',
        'segment_commercial',
        'nom_client_importateur',
        'nom_client_exportateur',
        'devise',
        'reference_facture',
        'montant_facture_contrat_commercial',
        'montant_reglement',
        'montant_di',
        'ref_declaration_detail',
        'montant_declaration_detail',
        'ref_quittance_paiement_droits_et_taxes_douane',
        'montant_quittance',
        'numero_di',
        'pays',
        'date_apurement',
        'mise_en_demeure',
        'code_identification_unique_importateur',
        'type_importation',
        'nature_importation',
        'ref_domiciliation',
        'statut_apurement',
        'vlc_ad_ah',
        'references_mt298',
        'date_reglement',
        'ref_transaction',
        'created_by',
    ];

    protected $casts = [
        'date_domiciliation' => 'date',
        'date_apurement' => 'date',
        'date_reglement' => 'date',
        'montant_facture_contrat_commercial' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
