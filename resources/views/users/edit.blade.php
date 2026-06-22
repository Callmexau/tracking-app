@extends('layouts.dashboard')

@section('title', "Modifier l'utilisateur")

@section('content')
<div class="container-fluid p-0 animate__animated animate__fadeIn">

    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small fw-medium">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
        <h1 class="h3 text-dark fw-bold mt-2 mb-1">Modifier le compte de {{ $user->first_name }}</h1>
        <p class="text-muted small mb-0">Mettez à jour les privilèges d'accès, l'état d'activité ou réinitialisez le mot de passe.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Prénom</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               class="form-control custom-input @error('first_name') is-invalid @enderror" 
                               value="{{ old('first_name', $user->first_name) }}" 
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
                               value="{{ old('last_name', $user->last_name) }}" 
                               required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Adresse Email Professionnelle</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px; font-size: 14px;"><i class="bi bi-envelope"></i></span>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control custom-input border-start-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="role" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Rôle Fonctionnel</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px; font-size: 14px;"><i class="bi bi-shield-lock"></i></span>
                            <select name="role" id="role" class="form-select custom-input border-start-0 @error('role') is-invalid @enderror" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="is_active" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Statut du compte</label>
                        <select name="is_active" id="is_active" class="form-select custom-input @error('is_active') is-invalid @enderror" required>
                            <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Actif (Autorisé)</option>
                            <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Inactif (Bloqué)</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <hr class="text-muted opacity-25 my-2">
                        <div class="alert alert-light border rounded-3 p-3 mb-0" style="font-size: 12px; color: #6b7280;">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i> Laissez les champs de mot de passe **vides** si vous ne souhaitez pas modifier le mot de passe actuel de l'agent.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Nouveau mot de passe (Optionnel)</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control custom-input @error('password') is-invalid @enderror" 
                               placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Confirmer le nouveau mot de passe</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control custom-input" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-2">
                    <a href="{{ route('users.index') }}" class="btn btn-light me-2 border fw-medium" style="font-size: 13px; border-radius: 8px; padding: 7px 18px;">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" style="font-size: 13px; border-radius: 8px; padding: 7px 22px;">
                        Sauvegarder
                    </button>
                </div>

            </form>
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
    .input-group-text {
        border: 1px solid #d1d5db;
    }
</style>
@endpush