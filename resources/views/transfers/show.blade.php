@extends('layouts.dashboard')

@section('title', 'Détails du transfert')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Détails du transfert</h1>
        <p class="text-muted small mb-0">Réf. transaction : <span class="font-monospace fw-semibold">{{ $transfer->reference_transaction ?? 'N/A' }}</span></p>
    </div>
    <div>
        <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary px-3 me-2">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
        @role(['Super Admin', 'OPS', 'Contrôle Interne'])
            <a href="{{ route('transfers.edit', $transfer->id) }}" class="btn btn-primary px-3">
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
                        <p class="text-muted small mb-1">Date de dépôt</p>
                        <p class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($transfer->date_depot)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Segment clientèle</p>
                        <p class="fw-semibold text-dark">{{ $transfer->segment_clientele }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Donneur d'ordre</p>
                        <p class="fw-semibold text-dark">{{ $transfer->donneur_ordre }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Bénéficiaire</p>
                        <p class="fw-semibold text-dark">{{ $transfer->beneficiaire }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Montant de l'ordre</p>
                        <p class="fw-bold text-dark fs-5">{{ number_format($transfer->montant_ordre, 2, ',', ' ') }} {{ $transfer->devise }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Montant devise préfinancée</p>
                        <p class="fw-semibold text-dark">
                            {{ $transfer->montant_devise_prefinance ? number_format($transfer->montant_devise_prefinance, 2, ',', ' ') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary">Étapes de traitement</h5>
                @php
                    $badgeClass = match($transfer->statut) {
                        'Traité' => 'bg-success',
                        'Rejet' => 'bg-danger',
                        default => 'bg-warning text-dark'
                    };
                @endphp
                <span class="badge {{ $badgeClass }} fs-7">{{ $transfer->statut }}</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted py-2 w-50">Situation du dossier</td>
                            <td class="fw-semibold py-2">{{ $transfer->situation_dossier ?? 'N/A' }}</td>
                        </tr>
                        @if($transfer->situation_dossier === 'Allocation')
                        <tr>
                            <td class="text-muted py-2">Numéro d'allocation</td>
                            <td class="fw-semibold py-2 font-monospace">{{ $transfer->numero_allocation }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted py-2">Date d'envoi BEAC</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_envoi_beac ? \Carbon\Carbon::parse($transfer->date_envoi_beac)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Date de décision BEAC</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_decision ? \Carbon\Carbon::parse($transfer->date_decision)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Décision BEAC</td>
                            <td class="fw-semibold py-2">{{ $transfer->decision_beac ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Date de réception MT999</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_reception_mt999 ? \Carbon\Carbon::parse($transfer->date_reception_mt999)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Date d'envoi couverture XAF</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_envoi_couverture_xaf ? \Carbon\Carbon::parse($transfer->date_envoi_couverture_xaf)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Date de réception devise</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_reception_devise ? \Carbon\Carbon::parse($transfer->date_reception_devise)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Conditions réunies le</td>
                            <td class="fw-semibold py-2">{{ $transfer->conditions_reunies_le ? \Carbon\Carbon::parse($transfer->conditions_reunies_le)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Date de traitement</td>
                            <td class="fw-semibold py-2">{{ $transfer->date_traitement ? \Carbon\Carbon::parse($transfer->date_traitement)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-muted py-2 pt-3 fw-bold">Délai de traitement</td>
                            <td class="fw-bold text-primary py-2 pt-3">{{ $transfer->delai_traitement ?? 'En cours...' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 px-4">
                <h5 class="fw-bold mb-0 text-secondary">Références</h5>
            </div>
            <div class="card-body px-4 py-3">
                <div class="mb-3">
                    <p class="text-muted small mb-1">Référence N98</p>
                    <span class="badge bg-light text-dark border font-monospace">{{ $transfer->ref_n98 ?? 'N/A' }}</span>
                </div>
                <div>
                    <p class="text-muted small mb-1">Référence transaction</p>
                    <span class="text-dark fw-semibold font-monospace">{{ $transfer->reference_transaction ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent py-3 px-4">
                <h5 class="fw-bold mb-0 text-secondary">Commentaires</h5>
            </div>
            <div class="card-body px-4 py-3">
                <p class="text-dark mb-0 small">{{ $transfer->commentaire ?? 'Aucun commentaire renseigné pour ce transfert.' }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body px-4 py-3">
                <p class="text-muted small mb-0">
                    Enregistré par : 
                    <span class="fw-semibold text-dark">
                        {{ $transfer->creator ? $transfer->creator->name : 'Système / Utilisateur inconnu' }}
                    </span>
                </p>
                <p class="text-muted small mb-0 mt-2">
                    Créé le : 
                    <span class="fw-semibold text-dark">
                        {{ $transfer->created_at->format('d/m/Y à H:i') }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection