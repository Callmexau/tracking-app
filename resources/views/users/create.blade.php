@extends('layouts.dashboard')

@section('title', 'Ajouter un utilisateur')

@section('content')
<div class="container-fluid p-0 animate__animated animate__fadeIn">

    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
        <h1 class="h3 text-dark fw-bold mt-2 mb-1">Nouveau compte agent</h1>
        <p class="text-muted small mb-0">Enregistrez un nouvel utilisateur et assignez-lui un rôle système spécifique.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" style="font-size: 13px;">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Veuillez corriger les erreurs suivantes :</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Prénom</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               class="form-control custom-input @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name') }}" 
                               placeholder="Ex: Claude" 
                               required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Nom de famille</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               class="form-control custom-input @error('last_name') is-invalid @enderror" 
                               value="{{ old('last_name') }}" 
                               placeholder="Ex: Yoka" 
                               required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Adresse Email Professionnelle</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px; font-size: 14px;"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control custom-input border-start-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" 
                                   placeholder="c.yoka@ecobank.com" 
                                   required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Rôle Fonctionnel</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px; font-size: 14px;"><i class="bi bi-shield-lock"></i></span>
                            <select name="role" id="role" class="form-select custom-input border-start-0 @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Sélectionnez un rôle...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr class="text-muted opacity-25 my-2">
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Mot de passe temporaire</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control custom-input @error('password') is-invalid @enderror" 
                               placeholder="••••••••" 
                               required>
                        <div class="form-text text-muted" style="font-size: 11px;">Longueur minimale : 8 caractères.</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Confirmer le mot de passe</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control custom-input" 
                               placeholder="••••••••" 
                               required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-2">
                    <a href="{{ route('users.index') }}" class="btn btn-light me-2 border fw-medium" style="font-size: 13px; border-radius: 8px; padding: 7px 18px;">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" style="font-size: 13px; border-radius: 8px; padding: 7px 22px;">
                        Créer le compte
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Customisation légère pour avoir des inputs modernes et homogènes */
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
    .input-group-text {
        border: 1px solid #d1d5db;
    }
</style>
@endpush