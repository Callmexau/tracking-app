<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'date_depot',
        'segment_clientele',
        'ref_n98',
        'donneur_ordre',
        'beneficiaire',
        'reference_transaction',
        'devise',
        'montant_ordre',
        'montant_devise_prefinance',
        'situation_dossier',
        'numero_allocation',
        'date_envoi_beac',
        'date_decision',
        'decision_beac',
        'date_reception_mt999',
        'date_envoi_couverture_xaf',
        'date_reception_devise',
        'conditions_reunies_le',
        'date_traitement',
        'statut',
        'commentaire',
        'delai_traitement',
        'created_by',
    ];

    protected $casts = [
        'date_depot'                => 'date',
        'date_envoi_beac'           => 'date',
        'date_decision'             => 'date',
        'date_reception_mt999'      => 'date',
        'date_envoi_couverture_xaf' => 'date',
        'date_reception_devise'     => 'date',
        'conditions_reunies_le'     => 'date',
        'date_traitement'           => 'date',
    ];

    // Relation avec le modèle User (si l'ID de l'utilisateur est stocké dans created_by)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}