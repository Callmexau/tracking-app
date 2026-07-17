@extends('layouts.dashboard')

@section('title', 'Exportations')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Gestion des exportations</h1>
            <p class="text-muted small mb-0">Visualisez et gérez l'ensemble des exportations enregistrées sur la plateforme.</p>
        </div>

        @hasanyrole(['Super Admin','OPS'])
            <a href="{{ route('exports.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Nouvelle exportation
            </a>
        @endhasanyrole
    </div>

    {{-- Barre de recherche et export Excel --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-5 col-lg-6">
                    <form action="{{ route('exports.index') }}" method="GET" class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Rechercher par exportateur, référence ou devise..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('exports.index') }}" class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="col-md-7 col-lg-6">
                    <form action="{{ route('exports.export') }}" method="GET" class="row g-2 justify-content-md-end align-items-center">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>N°</th>
                        <th>Exportateur</th>
                        <th>Date domiciliation</th>
                        <th>Référence domiciliation</th>
                        <th>Devise</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th width="180">Actions</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($exports as $export)

                    <tr>

                        <td>{{ $export->numero }}</td>

                        <td>{{ $export->nom_exportateur }}</td>

                        <td>
                            {{ optional($export->date_domiciliation)->format('d/m/Y') }}
                        </td>

                        <td>{{ $export->reference_domiciliation }}</td>

                        <td>{{ $export->devise }}</td>

                        <td class="text-end">
                            {{ number_format($export->montant_facture, 2, ',', ' ') }}
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $export->statut_dossier }}
                            </span>
                        </td>

                        <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('exports.show', $export->id) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Bouton Modifier visible uniquement par Super Admin et OPS --}}
                                    @role(['Super Admin', 'OPS'])
                                        <a href="{{ route('exports.edit', $export->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endrole
                                </div>
                            </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">
                            Aucune exportation enregistrée.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-footer">

            {{ $exports->links() }}

        </div>

    </div>

</div>

@endsection
