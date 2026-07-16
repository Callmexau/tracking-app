@extends('layouts.dashboard')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid p-0 animate__animated animate__fadeIn">

    <div class="mb-4">
        <h1 class="h3 text-dark fw-bold mb-1">Mon Profil et Paramètres</h1>
        <p class="text-muted small mb-0">Gérez vos informations personnelles et mettez à jour votre mot de passe en toute sécurité.</p>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="h6 fw-bold text-dark mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
                            <i class="bi bi-check-circle me-2"></i> Informations mises à jour avec succès.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
                            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-semibold text-dark" style="font-size: 13px;">Prénom</label>
                                <input type="text" name="first_name" id="first_name" class="form-control custom-input @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-semibold text-dark" style="font-size: 13px;">Nom de famille</label>
                                <input type="text" name="last_name" id="last_name" class="form-control custom-input @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold text-dark" style="font-size: 13px;">Adresse Email Professionnelle</label>
                                <input type="email" name="email" id="email" class="form-control custom-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary" style="font-size: 13px; border-radius: 8px; padding: 8px 20px;">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="h6 fw-bold text-dark mb-0">Modifier le mot de passe</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 13px; border-radius: 8px;">
                            <i class="bi bi-check-circle me-2"></i> Mot de passe mis à jour avec succès.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-semibold text-dark" style="font-size: 13px;">Mot de passe actuel</label>
                                <input type="password" name="current_password" id="current_password" class="form-control custom-input @error('current_password', 'updatePassword') is-invalid @enderror" placeholder="Votre mot de passe" required>
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-dark" style="font-size: 13px;">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control custom-input @error('password', 'updatePassword') is-invalid @enderror" placeholder="Nouveau mot de passe" required>
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-dark" style="font-size: 13px;">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control custom-input" placeholder="Confirmer le mot de passe" required>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-dark" style="font-size: 13px; border-radius: 8px; padding: 8px 20px;">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100 d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <div class="bg-light rounded-circle d-inline-flex p-3 text-primary">
                        <i class="bi bi-person-badge fs-2"></i>
                    </div>
                </div>
                <h5 class="h6 fw-bold text-dark mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>

                <hr class="opacity-25 w-75 mx-auto">

                <div class="mt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-medium px-3 py-2" style="font-size: 12px; border-radius: 20px;">
                        Rôle : {{ $user->getRoleNames()->first() ?? 'Aucun rôle' }}
                    </span>
                </div>

                <div class="mt-4 pt-3 text-start small border-top pt-3 border-light">
                    <p class="text-muted mb-1"><i class="bi bi-shield-check me-2 text-success"></i>Accès sécurisé : <strong>Activé</strong></p>
                    <p class="text-muted mb-0"><i class="bi bi-calendar-event me-2 text-info"></i>Créé le : {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-input {
        border-radius: 8px;
        font-size: 13px;
        padding: 8px 14px;
        border: 1px solid #d1d5db;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .custom-input:focus {
        border-color: var(--brand-secondary);
        box-shadow: 0 0 0 3px rgba(0, 163, 173, 0.15);
        color: #1f2937;
    }
</style>
@endpush
