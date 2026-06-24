@extends('layouts.dashboard')

@section('title', 'Modifier le transfert')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Modifier le transfert</h1>
        <p class="text-muted small mb-0">Mettez à jour les informations du transfert sélectionné.</p>
    </div>
    <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<form action="{{ route('transfers.update', $transfer->id) }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @method('PUT')

    <div class="row">
        {{-- SECTION 1 : Informations Générales --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-dark fs-6">
                        <i class="bi bi-info-circle me-2"></i>
                        Informations Générales
                    </h5>
                </div>

                <div class="card-body">

                    {{-- Date de dépôt --}}
                    <div class="mb-3">
                        <label for="date_depot" class="form-label fw-semibold small">
                            Date de dépôt <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            class="form-control @error('date_depot') is-invalid @enderror"
                            id="date_depot"
                            name="date_depot"
                            required
                            value="{{ old('date_depot', $transfer->date_depot ? \Carbon\Carbon::parse($transfer->date_depot)->format('Y-m-d') : '') }}">
                        @error('date_depot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Segment clientèle --}}
                    <div class="mb-3">
                        <label for="segment_clientele" class="form-label fw-semibold small">
                            Segment Clientèle <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('segment_clientele') is-invalid @enderror"
                            id="segment_clientele"
                            name="segment_clientele"
                            required>

                            <option value="" disabled {{ old('segment_clientele', $transfer->segment_clientele) ? '' : 'selected' }}>
                                Sélectionner un segment
                            </option>

                            <option value="ECG" {{ old('segment_clientele', $transfer->segment_clientele) == 'ECG' ? 'selected' : '' }}>
                                ECG
                            </option>

                            <option value="CCB" {{ old('segment_clientele', $transfer->segment_clientele) == 'CCB' ? 'selected' : '' }}>
                                CCB
                            </option>

                            <option value="CIB" {{ old('segment_clientele', $transfer->segment_clientele) == 'CIB' ? 'selected' : '' }}>
                                CIB
                            </option>
                        </select>
                        @error('segment_clientele')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Référence N98 --}}
                    <div class="mb-3">
                        <label for="ref_n98" class="form-label fw-semibold small">
                            Réf. N98
                        </label>

                        <input
                            type="text"
                            class="form-control text-uppercase @error('ref_n98') is-invalid @enderror"
                            id="ref_n98"
                            name="ref_n98"
                            maxlength="50"
                            value="{{ old('ref_n98', $transfer->ref_n98) }}"
                            placeholder="Ex : XXXX">
                        @error('ref_n98')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Donneur d'ordre --}}
                    <div class="mb-3">
                        <label for="donneur_ordre" class="form-label fw-semibold small">
                            Donneur d'ordre <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('donneur_ordre') is-invalid @enderror"
                            id="donneur_ordre"
                            name="donneur_ordre"
                            maxlength="255"
                            required
                            value="{{ old('donneur_ordre', $transfer->donneur_ordre) }}"
                            placeholder="Nom du donneur d'ordre">
                        @error('donneur_ordre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bénéficiaire --}}
                    <div class="mb-3">
                        <label for="beneficiaire" class="form-label fw-semibold small">
                            Bénéficiaire <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('beneficiaire') is-invalid @enderror"
                            id="beneficiaire"
                            name="beneficiaire"
                            maxlength="255"
                            required
                            value="{{ old('beneficiaire', $transfer->beneficiaire) }}"
                            placeholder="Nom du bénéficiaire">
                        @error('beneficiaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Référence transaction --}}
                    <div class="mb-3">
                        <label for="reference_transaction" class="form-label fw-semibold small">
                            Référence Transaction
                        </label>

                        <input
                            type="text"
                            class="form-control text-uppercase @error('reference_transaction') is-invalid @enderror"
                            id="reference_transaction"
                            name="reference_transaction"
                            maxlength="100"
                            value="{{ old('reference_transaction', $transfer->reference_transaction) }}"
                            placeholder="Ex : TRX123456789">
                        @error('reference_transaction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- SECTION 2 : Montants & Devises --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-dark fs-6">
                        <i class="bi bi-currency-exchange me-2"></i>
                        Montants et Devises
                    </h5>
                </div>

                <div class="card-body">

                    {{-- Devise --}}
                    <div class="mb-3">
                        <label for="devise" class="form-label fw-semibold small">
                            Devise <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('devise') is-invalid @enderror"
                            id="devise"
                            name="devise"
                            required>

                            <option value="" disabled {{ old('devise', $transfer->devise) ? '' : 'selected' }}>
                                Sélectionner la devise
                            </option>

                            <option value="XAF" {{ old('devise', $transfer->devise) == 'XAF' ? 'selected' : '' }}>
                                XAF (Franc CFA)
                            </option>

                            <option value="XOF" {{ old('devise', $transfer->devise) == 'XOF' ? 'selected' : '' }}>
                                XOF (Franc CFA BCEAO)
                            </option>

                            <option value="EUR" {{ old('devise', $transfer->devise) == 'EUR' ? 'selected' : '' }}>
                                EUR (Euro)
                            </option>

                            <option value="USD" {{ old('devise', $transfer->devise) == 'USD' ? 'selected' : '' }}>
                                USD (Dollar US)
                            </option>

                            <option value="CAD" {{ old('devise', $transfer->devise) == 'CAD' ? 'selected' : '' }}>
                                CAD (Dollar Canadien)
                            </option>

                            <option value="GBP" {{ old('devise', $transfer->devise) == 'GBP' ? 'selected' : '' }}>
                                GBP (Livre Sterling)
                            </option>

                        </select>
                        @error('devise')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Montant ordre --}}
                    <div class="mb-3">
                        <label for="montant_ordre" class="form-label fw-semibold small">
                            Montant sur l'ordre de transfert <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="form-control @error('montant_ordre') is-invalid @enderror"
                            id="montant_ordre"
                            name="montant_ordre"
                            required
                            value="{{ old('montant_ordre', $transfer->montant_ordre) }}"
                            placeholder="0.00">
                        @error('montant_ordre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Montant préfinancé --}}
                    <div class="mb-3">
                        <label for="montant_devise_prefinance" class="form-label fw-semibold small">
                            Montant en devise (Préfinancé)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="form-control @error('montant_devise_prefinance') is-invalid @enderror"
                            id="montant_devise_prefinance"
                            name="montant_devise_prefinance"
                            value="{{ old('montant_devise_prefinance', $transfer->montant_devise_prefinance) }}"
                            placeholder="0.00">
                        @error('montant_devise_prefinance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- SECTION 3 : Suivi et Traitement (BEAC) --}}
        <div class="col-xl-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold text-dark fs-6">
                        <i class="bi bi-shield-check me-2"></i>
                        Suivi et Traitement (BEAC)
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row">
                        {{-- Situation dossier --}}
                        <div class="col-md-4 mb-3">
                            <label for="situation_dossier" class="form-label fw-semibold small">
                                Situation dossier
                            </label>

                            <select
                                class="form-select @error('situation_dossier') is-invalid @enderror"
                                id="situation_dossier"
                                name="situation_dossier">

                                <option value="">Sélectionner</option>

                                <option value="Préfinancement" {{ old('situation_dossier', $transfer->situation_dossier) == 'Préfinancement' ? 'selected' : '' }}>
                                    Préfinancement
                                </option>

                                <option value="Allocation" {{ old('situation_dossier', $transfer->situation_dossier) == 'Allocation' ? 'selected' : '' }}>
                                    Allocation
                                </option>

                                <option value="Instance" {{ old('situation_dossier', $transfer->situation_dossier) == 'Instance' ? 'selected' : '' }}>
                                    Instance
                                </option>

                                <option value="Marge" {{ old('situation_dossier', $transfer->situation_dossier) == 'Marge' ? 'selected' : '' }}>
                                    Marge
                                </option>

                            </select>
                            @error('situation_dossier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Numéro allocation --}}
                        <div class="col-md-4 mb-3" id="bloc_allocation" style="display:none;">
                            <label for="numero_allocation" class="form-label fw-semibold small">
                                Numéro d'allocation
                            </label>

                            <input
                                type="text"
                                class="form-control @error('numero_allocation') is-invalid @enderror"
                                id="numero_allocation"
                                name="numero_allocation"
                                value="{{ old('numero_allocation', $transfer->numero_allocation) }}"
                                maxlength="50"
                                placeholder="Ex : 0010, 0160, 0215...">
                            @error('numero_allocation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date envoi BEAC --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_envoi_beac" class="form-label fw-semibold small">
                                Date d'envoi à la BEAC
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_envoi_beac') is-invalid @enderror"
                                id="date_envoi_beac"
                                name="date_envoi_beac"
                                value="{{ old('date_envoi_beac', $transfer->date_envoi_beac ? \Carbon\Carbon::parse($transfer->date_envoi_beac)->format('Y-m-d') : '') }}">
                            @error('date_envoi_beac')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Date décision --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_decision" class="form-label fw-semibold small">
                                Date de la décision
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_decision') is-invalid @enderror"
                                id="date_decision"
                                name="date_decision"
                                value="{{ old('date_decision', $transfer->date_decision ? \Carbon\Carbon::parse($transfer->date_decision)->format('Y-m-d') : '') }}">
                            @error('date_decision')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Décision BEAC --}}
                        <div class="col-md-4 mb-3">
                            <label for="decision_beac" class="form-label fw-semibold small">
                                Décision
                            </label>

                            <select
                                class="form-select @error('decision_beac') is-invalid @enderror"
                                id="decision_beac"
                                name="decision_beac">

                                <option value="">Sélectionner</option>

                                <option value="Favorable" {{ old('decision_beac', $transfer->decision_beac) == 'Favorable' ? 'selected' : '' }}>
                                    Favorable
                                </option>

                                <option value="Rejet" {{ old('decision_beac', $transfer->decision_beac) == 'Rejet' ? 'selected' : '' }}>
                                    Rejet
                                </option>

                                <option value="Suspens BEAC" {{ old('decision_beac', $transfer->decision_beac) == 'Suspens BEAC' ? 'selected' : '' }}>
                                    Suspens BEAC
                                </option>

                            </select>
                            @error('decision_beac')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Réception MT999 --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_reception_mt999" class="form-label fw-semibold small">
                                Date réception MT999
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_reception_mt999') is-invalid @enderror"
                                id="date_reception_mt999"
                                name="date_reception_mt999"
                                value="{{ old('date_reception_mt999', $transfer->date_reception_mt999 ? \Carbon\Carbon::parse($transfer->date_reception_mt999)->format('Y-m-d') : '') }}">
                            @error('date_reception_mt999')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Envoi couverture XAF --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_envoi_couverture_xaf" class="form-label fw-semibold small">
                                Date d'envoi de la couverture en XAF
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_envoi_couverture_xaf') is-invalid @enderror"
                                id="date_envoi_couverture_xaf"
                                name="date_envoi_couverture_xaf"
                                value="{{ old('date_envoi_couverture_xaf', $transfer->date_envoi_couverture_xaf ? \Carbon\Carbon::parse($transfer->date_envoi_couverture_xaf)->format('Y-m-d') : '') }}">
                            @error('date_envoi_couverture_xaf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Réception devise --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_reception_devise" class="form-label fw-semibold small">
                                Date de réception de la devise
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_reception_devise') is-invalid @enderror"
                                id="date_reception_devise"
                                name="date_reception_devise"
                                value="{{ old('date_reception_devise', $transfer->date_reception_devise ? \Carbon\Carbon::parse($transfer->date_reception_devise)->format('Y-m-d') : '') }}">
                            @error('date_reception_devise')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Conditions réunies --}}
                        <div class="col-md-4 mb-3">
                            <label for="conditions_reunies_le" class="form-label fw-semibold small">
                                Conditions réunies pour traitement dans la Marge le
                            </label>

                            <input
                                type="date"
                                class="form-control @error('conditions_reunies_le') is-invalid @enderror"
                                id="conditions_reunies_le"
                                name="conditions_reunies_le"
                                value="{{ old('conditions_reunies_le', $transfer->conditions_reunies_le ? \Carbon\Carbon::parse($transfer->conditions_reunies_le)->format('Y-m-d') : '') }}">
                            @error('conditions_reunies_le')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Date traitement --}}
                        <div class="col-md-4 mb-3">
                            <label for="date_traitement" class="form-label fw-semibold small">
                                Date de traitement
                            </label>

                            <input
                                type="date"
                                class="form-control @error('date_traitement') is-invalid @enderror"
                                id="date_traitement"
                                name="date_traitement"
                                value="{{ old('date_traitement', $transfer->date_traitement ? \Carbon\Carbon::parse($transfer->date_traitement)->format('Y-m-d') : '') }}">
                            @error('date_traitement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Statut --}}
                        <div class="col-md-4 mb-3">
                            <label for="statut" class="form-label fw-semibold small">
                                Statut <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select @error('statut') is-invalid @enderror"
                                id="statut"
                                name="statut"
                                required>

                                <option value="Non traité" {{ old('statut', $transfer->statut) == 'Non traité' ? 'selected' : '' }}>
                                    Non traité
                                </option>

                                <option value="Traité" {{ old('statut', $transfer->statut) == 'Traité' ? 'selected' : '' }}>
                                    Traité
                                </option>

                                <option value="Rejet" {{ old('statut', $transfer->statut) == 'Rejet' ? 'selected' : '' }}>
                                    Rejet
                                </option>

                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Délai traitement --}}
                        <div class="col-md-4 mb-3">
                            <label for="delai_traitement" class="form-label fw-semibold small">
                                Délai de traitement
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light @error('delai_traitement') is-invalid @enderror"
                                id="delai_traitement"
                                name="delai_traitement"
                                readonly
                                value="{{ old('delai_traitement', $transfer->delai_traitement) }}"
                                placeholder="Calcul automatique">
                            @error('delai_traitement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Commentaire --}}
                        <div class="col-md-12 mb-3">
                            <label for="commentaire" class="form-label fw-semibold small">
                                Commentaires / Motifs
                            </label>

                            <textarea
                                class="form-control @error('commentaire') is-invalid @enderror"
                                id="commentaire"
                                name="commentaire"
                                rows="4"
                                placeholder="Informations complémentaires...">{{ old('commentaire', $transfer->commentaire) }}</textarea>
                            @error('commentaire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5 gap-2">
        <button type="reset" class="btn btn-outline-secondary px-4">Annuler</button>
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save me-2"></i>Enregistrer les modifications
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const situation = document.getElementById('situation_dossier');
    const blocAllocation = document.getElementById('bloc_allocation');
    const numeroAllocation = document.getElementById('numero_allocation');

    const dateDepot = document.getElementById('date_depot');
    const dateTraitement = document.getElementById('date_traitement');
    const delaiTraitement = document.getElementById('delai_traitement');

    function gererAffichage() {
        if (situation.value === 'Allocation') {
            blocAllocation.style.display = 'block';
        } else {
            blocAllocation.style.display = 'none';
            if (numeroAllocation) {
                numeroAllocation.value = '';
            }
        }
    }

    function calculerDelai() {
        if (!dateDepot.value || !dateTraitement.value) {
            delaiTraitement.value = '';
            return;
        }

        const debut = new Date(dateDepot.value);
        const fin = new Date(dateTraitement.value);

        const diff = Math.ceil(
            (fin - debut) / (1000 * 60 * 60 * 24)
        );

        if (diff >= 0) {
            delaiTraitement.value = diff + ' jour(s)';
        } else {
            delaiTraitement.value = '';
        }
    }

    situation.addEventListener('change', gererAffichage);
    dateDepot.addEventListener('change', calculerDelai);
    dateTraitement.addEventListener('change', calculerDelai);

    // Exécuté au chargement initial de la page pour pré-remplir les données
    gererAffichage();
    calculerDelai();
});
</script>
@endpush

@endsection