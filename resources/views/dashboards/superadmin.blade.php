@extends('layouts.dashboard')

@section('title', 'Super Administrateur')

@section('content')

{{-- PAGE HEADER --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">
            Tableau de bord Administrateur
        </h1>
        <p class="text-muted small mb-0">
            Vue globale et analytique de la plateforme de suivi des transferts
        </p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-person-plus me-2"></i> Nouvel Utilisateur
        </a>
    </div>
</div>

{{-- SECTION 1 : KPI UTILISATEURS --}}
<h6 class="text-uppercase text-muted fw-bold tracking-wider small mb-3">Gestion des Utilisateurs</h6>
<div class="row mb-4">
    {{-- Contrôle Interne --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 transition-hover">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small fw-semibold text-uppercase">Contrôle Interne</div>
                    <div class="fs-3 fw-bold text-primary mt-1">{{ $totalControleInterne ?? 0 }}</div>
                </div>
                <div class="bg-light-primary rounded-3 p-3 ms-3">
                    <i class="bi bi-person-badge fs-4 text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Opérateurs / Ops --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 transition-hover">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small fw-semibold text-uppercase">Opération (Ops)</div>
                    <div class="fs-3 fw-bold text-success mt-1">{{ $totalOps ?? 0 }}</div>
                </div>
                <div class="bg-light-success rounded-3 p-3 ms-3">
                    <i class="bi bi-person-gear fs-4 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Gestionnaires CCB --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 transition-hover">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small fw-semibold text-uppercase">Gestionnaires CCB</div>
                    <div class="fs-3 fw-bold text-indigo-purple mt-1">{{ $totalCCB ?? 0 }}</div>
                </div>
                <div class="bg-light-indigo rounded-3 p-3 ms-3">
                    <i class="bi bi-person-vcard fs-4 text-indigo"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Comptes actifs --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 transition-hover">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-muted small fw-semibold text-uppercase">Comptes Actifs</div>
                    <div class="fs-3 fw-bold text-info mt-1">{{ $activeUsers ?? 0 }}</div>
                </div>
                <div class="bg-light-info rounded-3 p-3 ms-3">
                    <i class="bi bi-shield-check fs-4 text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2 : KPI TRANSFERTS --}}
<h6 class="text-uppercase text-muted fw-bold tracking-wider small mb-3">Activité des Transferts</h6>
<div class="row mb-4">
    {{-- Total Transferts --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 border-start border-4 border-dark">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase">Total Transferts</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="fs-3 fw-bold text-dark">{{ $totalTransfers ?? 0 }}</span>
                    <i class="bi bi-arrow-left-right text-muted fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- En attente --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 border-start border-4 border-warning">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase">En attente</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="fs-3 fw-bold text-warning">{{ $pendingTransfers ?? 0 }}</span>
                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Traités --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 border-start border-4 border-success">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase">Traités</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="fs-3 fw-bold text-success">{{ $processedTransfers ?? 0 }}</span>
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejetés --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase">Rejetés</div>
                <div class="d-flex align-items-center justify-content-between mt-2">
                    <span class="fs-3 fw-bold text-danger">{{ $rejectedTransfers ?? 0 }}</span>
                    <i class="bi bi-x-circle text-danger fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 3 : ACTIVITÉ & UTILISATEURS --}}
<div class="row mb-4">
    {{-- Derniers utilisateurs --}}
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-dark fs-6">
                    Utilisateurs Récemment Créés
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-muted">
                        <tr>
                            <th class="ps-4">Identité</th>
                            <th>Email</th>
                            <th class="pe-4">Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-primary text-uppercase" style="width: 36px; height: 36px; font-size: 14px;">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark d-block mb-0">{{ $user->first_name }} {{ $user->last_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-secondary small">{{ $user->email }}</td>
                                <td class="pe-4">
                                    <span class="badge bg-primary px-2 py-1 rounded-pill">
                                        {{ $user->getRoleNames()->first() ?? 'Aucun' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                    Aucun utilisateur récent disponible
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Audit / Activité Corrigé --}}
    <div class="col-lg-5">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="mb-0 fw-bold text-dark fs-6">
                    Journal d'Activité Récent
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon bg-soft-success">
                            <i class="bi bi-plus-circle text-success"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold text-dark small">Création d'utilisateurs</p>
                            <span class="text-muted fs-7">Système mis à jour</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon bg-soft-primary">
                            <i class="bi bi-shield-lock text-primary"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold text-dark small">Gestion des rôles et accès</p>
                            <span class="text-muted fs-7">Sécurité configurée</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon bg-soft-info">
                            <i class="bi bi-send text-info"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold text-dark small">Création des transferts internationaux</p>
                            <span class="text-muted fs-7">En attente de traitement</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon bg-soft-warning">
                            <i class="bi bi-pencil-square text-warning"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-semibold text-dark small">Modifications des dossiers</p>
                            <span class="text-muted fs-7">Vérifications d'audit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRAPHIQUE --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold text-dark fs-6">
            Évolution Temporelle des Transferts
        </h5>
        <select class="form-select form-select-sm border-0 bg-light w-auto">
            <option>7 derniers jours</option>
            <option>30 derniers jours</option>
            <option>Cette année</option>
        </select>
    </div>
    <div class="card-body">
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="transfersChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .fs-7 { font-size: 0.8rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .transition-hover:hover { transform: translateY(-3px); transition: all 0.25s ease; }
    
    .bg-light-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-light-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-light-info { background-color: rgba(13, 202, 240, 0.1); }
    .bg-light-indigo { background-color: rgba(102, 16, 242, 0.1); }
    
    .text-indigo-purple { color: #6610f2; }
    .text-indigo { color: #6610f2; }

    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.12); color: #0d6efd; }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.12); color: #198754; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.12); color: #ffc107; }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.12); color: #0dcaf0; }

    /* NOUVEAU STYLE TIMELINE ADAPTÉ */
    .timeline {
        position: relative;
        padding-left: 1rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 27px;
        top: 15px;
        bottom: 15px;
        width: 2px;
        background-color: #e9ecef;
    }
    .timeline-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: relative;
        z-index: 2;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        flex-shrink: 0;
        background-color: #fff;
    }
    .timeline-content {
        margin-left: 1rem;
        padding-top: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('transfersChart').getContext('2d');
        
        const chartLabels = @json($chartData['labels'] ?? []);
        const chartValues = @json($chartData['data'] ?? []);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Transferts enregistrés',
                    data: chartValues,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            borderDash: [5, 5]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Transferts : ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush