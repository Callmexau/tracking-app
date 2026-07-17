@extends('layouts.dashboard')

@section('title', 'Détails de l\'importation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Détails de l'importation</h3>
            <p class="text-muted small mb-0">Consultez les informations enregistrées pour cette importation.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('imports.edit', $import) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                @foreach([
                    'Date domiciliation' => optional($import->date_domiciliation)->format('d/m/Y'),
                    'Segment commercial' => $import->segment_commercial,
                    'Nom client importateur' => $import->nom_client_importateur,
                    'Nom client exportateur' => $import->nom_client_exportateur,
                    'Devise' => $import->devise,
                    'Référence facture' => $import->reference_facture,
                    'Montant facture' => number_format($import->montant_facture_contrat_commercial, 2, ',', ' '),
                    'Montant règlement' => $import->montant_reglement,
                    'Montant DI' => $import->montant_di,
                    'Réf déclaration détail' => $import->ref_declaration_detail,
                    'Montant déclaration détail' => $import->montant_declaration_detail,
                    'Réf quittance paiement' => $import->ref_quittance_paiement_droits_et_taxes_douane,
                    'Montant quittance' => $import->montant_quittance,
                    'N° DI' => $import->numero_di,
                    'Pays' => $import->pays,
                    'Date apurement' => is_string($import->date_apurement) ? $import->date_apurement : optional($import->date_apurement)->format('d/m/Y'),
                    'Mise en demeure' => $import->mise_en_demeure,
                    'Code ID importateur' => $import->code_identification_unique_importateur,
                    'Type importation' => $import->type_importation,
                    'Nature importation' => $import->nature_importation,
                    'Réf domiciliation' => $import->ref_domiciliation,
                    'Statut apurement' => $import->statut_apurement,
                    'VLC/AD/AH' => $import->vlc_ad_ah,
                    'Références MT298' => $import->references_mt298,
                    'Date règlement' => is_string($import->date_reglement) ? $import->date_reglement : optional($import->date_reglement)->format('d/m/Y'),
                    'Réf transaction' => $import->ref_transaction,
                ] as $label => $value)
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-white">
                            <p class="text-muted small mb-1">{{ $label }}</p>
                            <p class="fw-semibold mb-0">{{ $value ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
