@extends('layouts.dashboard')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-dark fw-bold mb-1">Gestion des utilisateurs</h1>
            <p class="text-muted small mb-0">Administrez les accès, les rôles et les statuts des agents de la plateforme.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-person-plus me-2" style="font-size: 1rem;"></i> Ajouter un utilisateur
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" style="font-size: 13px;">
            <i class="bi bi-check-circle-fill me-2 text-success" style="font-size: 16px;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="table-light text-uppercase tracking-wider" style="font-size: 11px; font-weight: 600; color: #6b7280;">
                        <tr>
                            <th class="py-3 px-4">Nom complet</th>
                            <th class="py-3 px-4">Adresse Email</th>
                            <th class="py-3 px-4">Rôle Système</th>
                            <th class="py-3 px-4 text-center">Statut</th>
                            <th class="py-3 px-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-bottom">
                                <td class="py-3 px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold me-3" 
                                             style="width: 34px; height: 34px; font-size: 12px; background: linear-gradient(135deg, #00548f, #00a3ad);">
                                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark">{{ $user->first_name }} {{ $user->last_name }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-muted">
                                    {{ $user->email }}
                                </td>

                                <td class="py-3 px-4">
                                    @php
                                        $role = $user->getRoleNames()->first() ?? 'Aucun rôle';
                                        $roleClass = match($role) {
                                            'Super Admin' => 'bg-danger-subtle text-danger',
                                            'Controle Interne' => 'bg-warning-subtle text-warning-dark',
                                            'OPS' => 'bg-info-subtle text-info-dark',
                                            default => 'bg-secondary-subtle text-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $roleClass }} px-2.5 py-1.5 rounded-pill fw-medium" style="font-size: 11px;">
                                        {{ $role }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-center">
                                    @if($user->is_active)
                                        <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill fw-medium" style="font-size: 11px;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 6px; vertical-align: middle;"></i> Actif
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill fw-medium" style="font-size: 11px;">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 6px; vertical-align: middle;"></i> Inactif
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 text-end">
                                    <div class="btn-group">
                                        {{-- Le Super Admin a tous les droits, mais le Contrôle Interne ne peut agir que sur les profils OPS et CCB --}}
                                        @if(auth()->user()->hasRole('Super Admin') || 
                                            (auth()->user()->hasRole('Controle Interne') && $user->hasAnyRole(['OPS', 'CCB'])))
                                            
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light border-0 text-muted" title="Modifier l'utilisateur" style="border-radius: 6px; padding: 5px 10px;">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            {{-- Formulaire de suppression (DELETE) correctement sécurisé pour l'interface --}}
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="ms-1" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border-0 text-danger" title="Supprimer" style="border-radius: 6px; padding: 5px 10px;">
                                                    <i class="bi bi-shield-slash"></i>
                                                </button>
                                            </form>

                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people mb-2 d-block style" style="font-size: 2rem; color: #d1d5db;"></i>
                                    Aucun utilisateur n'a été trouvé dans le système.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 px-2">
        <div class="text-muted small">
            Affichage des enregistrements dynamiques
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Teintes foncées personnalisées pour le contraste des badges Bootstrap-subtle */
    .text-warning-dark { color: #856404 !important; }
    .text-info-dark { color: #055160 !important; }
    
    .table th {
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(244, 247, 246, 0.6) !important;
    }
</style>
@endpush