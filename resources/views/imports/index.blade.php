@extends('layouts.dashboard')

@section('title', 'Importations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Gestion des importations</h1>
            <p class="text-muted small mb-0">Visualisez et gérez l'ensemble des importations enregistrées sur la plateforme.</p>
        </div>

        @hasanyrole(['Super Admin','OPS'])
            <a href="{{ route('imports.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Nouvelle importation
            </a>
        @endhasanyrole
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-5 col-lg-6">
                    <form action="{{ route('imports.index') }}" method="GET" class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Rechercher par importateur, exportateur, référence..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('imports.index') }}" class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="col-md-7 col-lg-6">
                    <form action="{{ route('imports.export') }}" method="GET" class="row g-2 justify-content-md-end align-items-center">
                        <div class="col-auto">
                            <label class="col-form-label small text-muted">Période (Optionnel) :</label>
                        </div>
                        <div class="col-auto">
                            <input type="hidden" name="search" value="{{ request('search') }}">
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

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted">
                    <tr>
                        <th class="text-start">Date domiciliation</th>
                        <th class="text-start">Segment commercial</th>
                        <th class="text-start">Client importateur</th>
                        <th class="text-start">Client exportateur</th>
                        <th class="text-start">Devise</th>
                        <th class="text-end">Montant facture</th>
                        <th class="text-start">Réf facture</th>
                        <th class="text-center" width="170">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imports as $import)
                        <tr>
                            <td class="text-start">{{ optional($import->date_domiciliation)->format('d/m/Y') }}</td>
                            <td class="text-start">{{ $import->segment_commercial }}</td>
                            <td class="text-start">{{ $import->nom_client_importateur }}</td>
                            <td class="text-start">{{ $import->nom_client_exportateur }}</td>
                            <td class="text-start">{{ $import->devise }}</td>
                            <td class="text-end">{{ number_format($import->montant_facture_contrat_commercial, 2, ',', ' ') }}</td>
                            <td class="text-start">{{ $import->reference_facture }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('imports.show', $import) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @hasanyrole(['Super Admin','OPS'])
                                                <a href="{{ route('imports.edit', $import) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('imports.destroy', $import) }}" class="d-inline" onsubmit="return confirm('Supprimer cette importation ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endhasanyrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Aucune importation enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $imports->links() }}
        </div>
    </div>
</div>
@endsection
