@extends('layouts.dashboard')

@section('title', 'Liste des transferts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Gestion des transferts</h1>
        <p class="text-muted small mb-0">Visualisez et gérez l'ensemble des transferts enregistrés sur la plateforme.</p>
    </div>
    {{-- Bouton menant à la page de création --}}
    @role(['Super Admin', 'OPS'])
        <a href="{{ route('transfers.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-1"></i> Nouveau transfert
        </a>
    @endrole
</div>

{{-- Message de succès après enregistrement --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Barre de recherche et filtres d'exportation --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            {{-- Barre de recherche --}}
            <div class="col-md-5 col-lg-6">
                <form action="{{ route('transfers.index') }}" method="GET" class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        placeholder="Rechercher par donneur d'ordre, bénéficiaire, réf. N98..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- Formulaire d'exportation Excel --}}
            <div class="col-md-7 col-lg-6">
                <form action="{{ route('transfers.export') }}" method="GET" class="row g-2 justify-content-md-end align-items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="col-auto">
                        <label class="col-form-label small text-muted">Période (Optionnel) :</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request('start_date') }}" title="Date de début">
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request('end_date') }}" title="Date de fin">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success btn-sm px-3">
                            <i class="bi bi-file-earmark-excel me-1"></i> Exporter Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th class="ps-4 py-3">Date de dépôt</th>
                        <th>Réf. N98</th>
                        <th>Donneur d'ordre</th>
                        <th>Bénéficiaire</th>
                        <th>Montant</th>
                        <th>Devise</th>
                        <th>Statut</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transfers as $transfer)
                        <tr>
                            <td class="ps-4 fw-semibold">
                                {{ \Carbon\Carbon::parse($transfer->date_depot)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $transfer->ref_n98 ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-truncate" style="max-width: 180px;" title="{{ $transfer->donneur_ordre }}">
                                {{ $transfer->donneur_ordre }}
                            </td>
                            <td class="text-truncate" style="max-width: 180px;" title="{{ $transfer->beneficiaire }}">
                                {{ $transfer->beneficiaire }}
                            </td>
                            <td class="fw-bold">
                                {{ number_format($transfer->montant_ordre, 2, ',', ' ') }}
                            </td>
                            <td class="text-muted small">
                                {{ $transfer->devise }}
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($transfer->statut) {
                                        'Traité' => 'bg-success',
                                        'Rejet' => 'bg-danger',
                                        default => 'bg-warning text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $transfer->statut }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('transfers.show', $transfer->id) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Bouton Modifier visible uniquement par Super Admin et OPS --}}
                                    @role(['Super Admin', 'OPS'])
                                        <a href="{{ route('transfers.edit', $transfer->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-3 text-secondary-subtle"></i>
                                Aucun transfert ne correspond à vos critères.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination éventuelle --}}
@if ($transfers instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-4">
        {{ $transfers->links() }}
    </div>
@endif

@endsection
