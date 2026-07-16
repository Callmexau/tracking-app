@extends('layouts.dashboard')

@section('title', 'Détails de l\'exportation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Détails de l'exportation</h1>
            <p class="text-muted small mb-0">Réf. domiciliation : <span class="font-monospace fw-semibold">{{ $export->reference_domiciliation }}</span></p>
        </div>
        <div>
            <a href="{{ route('exports.index') }}" class="btn btn-outline-secondary px-3 me-2">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            @role(['Super Admin', 'OPS'])
            <a href="{{ route('exports.edit', $export->id) }}" class="btn btn-primary px-3">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>
            @endrole
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 px-4">
                    <h5 class="fw-bold mb-0 text-primary">Informations générales</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Numéro</p>
                            <p class="fw-semibold text-dark">{{ $export->numero }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Nom exportateur</p>
                            <p class="fw-semibold text-dark">{{ $export->nom_exportateur }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Date de domiciliation</p>
                            <p class="fw-semibold text-dark">{{ optional($export->date_domiciliation)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Référence domiciliation</p>
                            <p class="fw-semibold text-dark font-monospace">{{ $export->reference_domiciliation }}</p>
                        </div>
                        <div class="col-sm-12">
                            <p class="text-muted small mb-1">Référence facture / contrat</p>
                            <p class="fw-semibold text-dark">{{ $export->reference_facture_contrat }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 px-4">
                    <h5 class="fw-bold mb-0 text-primary">Informations financières</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Devise</p>
                            <p class="fw-semibold text-dark">{{ $export->devise }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Montant facture</p>
                            <p class="fw-bold text-dark fs-5">{{ number_format($export->montant_facture, 2, ',', ' ') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Montant règlement</p>
                            <p class="fw-semibold text-dark">{{ $export->montant_reglement ? number_format($export->montant_reglement, 2, ',', ' ') : '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Nature exportation</p>
                            <p class="fw-semibold text-dark">{{ $export->nature_exportation }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent py-3 px-4">
                    <h5 class="fw-bold mb-0 text-primary">Bon à embarquer et quittance</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Référence Bon à Embarquer</p>
                            <p class="fw-semibold text-dark">{{ $export->reference_bon_embarquer ?: '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Montant Bon à Embarquer</p>
                            <p class="fw-semibold text-dark">{{ $export->montant_bon_embarquer ? number_format($export->montant_bon_embarquer, 2, ',', ' ') : '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Référence quittance</p>
                            <p class="fw-semibold text-dark">{{ $export->reference_quittance ?: '—' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Montant quittance</p>
                            <p class="fw-semibold text-dark">{{ $export->montant_quittance ? number_format($export->montant_quittance, 2, ',', ' ') : '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 px-4">
                    <h5 class="fw-bold mb-0 text-secondary">Suivi du dossier</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted py-2">Date ouverture dossier</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_ouverture_dossier)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Date échéance contrat</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_echeance_contrat)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Date effective exportation</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_effective_exportation)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Date rapatriement</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_rapatriement)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Date rétrocession BEAC</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_retrocession_beac)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Date apurement</td>
                                <td class="fw-semibold py-2">{{ optional($export->date_apurement)->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <p class="text-muted small mb-1">Statut dossier</p>
                    @php
                        $badgeClass = match($export->statut_dossier) {
                            'Apuré' => 'bg-success',
                            'En cours' => 'bg-warning text-dark',
                            default => 'bg-danger'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-7">{{ $export->statut_dossier }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
