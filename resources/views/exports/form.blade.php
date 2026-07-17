<div class="row">
    <div class="col-12 mb-3">
        <p class="small text-muted mb-2">Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires.</p>
    </div>

    {{-- Numéro --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Numéro <span class="text-danger">*</span></label>
        <input type="text" name="numero" class="form-control"
            value="{{ old('numero', $export->numero ?? '') }}">
    </div>

    {{-- Nom exportateur --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Nom Exportateur <span class="text-danger">*</span></label>
        <input type="text" name="nom_exportateur" class="form-control"
            value="{{ old('nom_exportateur', $export->nom_exportateur ?? '') }}">
    </div>

    {{-- Date domiciliation --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de domiciliation <span class="text-danger">*</span></label>
        <input type="date" name="date_domiciliation" class="form-control"
            value="{{ old('date_domiciliation', isset($export) ? optional($export->date_domiciliation)->format('Y-m-d') : '') }}">
    </div>

    {{-- Référence domiciliation --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Référence de domiciliation <span class="text-danger">*</span></label>
        <input type="text" name="reference_domiciliation" class="form-control"
            value="{{ old('reference_domiciliation', $export->reference_domiciliation ?? '') }}">
    </div>

    {{-- Référence facture --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Référence de la facture définitive ou contrat <span class="text-danger">*</span></label>
        <input type="text" name="reference_facture_contrat" class="form-control"
            value="{{ old('reference_facture_contrat', $export->reference_facture_contrat ?? '') }}">
    </div>

    {{-- Devise --}}
    <div class="col-md-3 mb-3">
        <label class="form-label">Devise <span class="text-danger">*</span></label>

        <select name="devise" class="form-select">
            <option value="">Sélectionner une devise</option>

            <option value="XAF" {{ old('devise', $export->devise ?? '') == 'XAF' ? 'selected' : '' }}>Franc CFA (XAF)</option>
            <option value="XOF" {{ old('devise', $export->devise ?? '') == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
            <option value="USD" {{ old('devise', $export->devise ?? '') == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
            <option value="EUR" {{ old('devise', $export->devise ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
            <option value="GBP" {{ old('devise', $export->devise ?? '') == 'GBP' ? 'selected' : '' }}>Livre sterling (GBP)</option>
            <option value="CHF" {{ old('devise', $export->devise ?? '') == 'CHF' ? 'selected' : '' }}>Franc suisse (CHF)</option>
            <option value="CAD" {{ old('devise', $export->devise ?? '') == 'CAD' ? 'selected' : '' }}>Dollar canadien (CAD)</option>
            <option value="AUD" {{ old('devise', $export->devise ?? '') == 'AUD' ? 'selected' : '' }}>Dollar australien (AUD)</option>
            <option value="JPY" {{ old('devise', $export->devise ?? '') == 'JPY' ? 'selected' : '' }}>Yen japonais (JPY)</option>
            <option value="CNY" {{ old('devise', $export->devise ?? '') == 'CNY' ? 'selected' : '' }}>Yuan chinois (CNY)</option>
        </select>
    </div>

    {{-- Montant facture --}}
    <div class="col-md-3 mb-3">
        <label class="form-label">Montant facture / contrat <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="montant_facture" class="form-control"
            value="{{ old('montant_facture', $export->montant_facture ?? '') }}">
    </div>

    {{-- Montant règlement --}}
    <div class="col-md-3 mb-3">
        <label class="form-label">Montant règlement exportation</label>
        <input type="number" step="0.01" name="montant_reglement" class="form-control"
            value="{{ old('montant_reglement', $export->montant_reglement ?? '') }}">
    </div>

    {{-- Nature --}}
    <div class="col-md-3 mb-3">
        <label class="form-label">Nature de l'exportation <span class="text-danger">*</span></label>

        <select name="nature_exportation" class="form-select">
            <option value="">Sélectionner</option>

            <option value="Bien"
                {{ old('nature_exportation', $export->nature_exportation ?? '') == 'Bien' ? 'selected' : '' }}>
                Bien
            </option>

            <option value="Service"
                {{ old('nature_exportation', $export->nature_exportation ?? '') == 'Service' ? 'selected' : '' }}>
                Service
            </option>
        </select>
    </div>

    {{-- Référence Bon --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Référence Bon à Embarquer</label>
        <input type="text" name="reference_bon_embarquer" class="form-control"
            value="{{ old('reference_bon_embarquer', $export->reference_bon_embarquer ?? '') }}">
    </div>

    {{-- Montant Bon --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Montant Bon à Embarquer</label>
        <input type="number" step="0.01" name="montant_bon_embarquer" class="form-control"
            value="{{ old('montant_bon_embarquer', $export->montant_bon_embarquer ?? '') }}">
    </div>

    {{-- Référence quittance --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Référence quittance</label>
        <input type="text" name="reference_quittance" class="form-control"
            value="{{ old('reference_quittance', $export->reference_quittance ?? '') }}">
    </div>

    {{-- Montant quittance --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Montant quittance</label>
        <input type="number" step="0.01" name="montant_quittance" class="form-control"
            value="{{ old('montant_quittance', $export->montant_quittance ?? '') }}">
    </div>

    {{-- Date ouverture --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date ouverture dossier <span class="text-danger">*</span></label>
        <input type="date" name="date_ouverture_dossier" class="form-control"
            value="{{ old('date_ouverture_dossier', isset($export) ? optional($export->date_ouverture_dossier)->format('Y-m-d') : '') }}">
    </div>

    {{-- Date échéance --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date échéance contrat</label>
        <input type="date" name="date_echeance_contrat" class="form-control"
            value="{{ old('date_echeance_contrat', isset($export) ? optional($export->date_echeance_contrat)->format('Y-m-d') : '') }}">
    </div>

    {{-- Date effective --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date effective exportation</label>
        <input type="date" name="date_effective_exportation" class="form-control"
            value="{{ old('date_effective_exportation', isset($export) ? optional($export->date_effective_exportation)->format('Y-m-d') : '') }}">
    </div>

    {{-- Date rapatriement --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date rapatriement recettes</label>
        <input type="date" name="date_rapatriement" class="form-control"
            value="{{ old('date_rapatriement', isset($export) ? optional($export->date_rapatriement)->format('Y-m-d') : '') }}">
    </div>

    {{-- Date rétrocession --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date rétrocession BEAC</label>
        <input type="date" name="date_retrocession_beac" class="form-control"
            value="{{ old('date_retrocession_beac', isset($export) ? optional($export->date_retrocession_beac)->format('Y-m-d') : '') }}">
    </div>

    {{-- Date apurement --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Date apurement</label>
        <input type="date" name="date_apurement" class="form-control"
            value="{{ old('date_apurement', isset($export) ? optional($export->date_apurement)->format('Y-m-d') : '') }}">
    </div>

    {{-- Statut --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Statut dossier <span class="text-danger">*</span></label>

        <select name="statut_dossier" class="form-select">

            <option value="">Sélectionner</option>

            <option value="Non apuré"
                {{ old('statut_dossier', $export->statut_dossier ?? '') == 'Non apuré' ? 'selected' : '' }}>
                Non apuré
            </option>

            <option value="En cours"
                {{ old('statut_dossier', $export->statut_dossier ?? '') == 'En cours' ? 'selected' : '' }}>
                En cours
            </option>

            <option value="Apuré"
                {{ old('statut_dossier', $export->statut_dossier ?? '') == 'Apuré' ? 'selected' : '' }}>
                Apuré
            </option>

        </select>
    </div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('exports.index') }}" class="btn btn-secondary">
        Annuler
    </a>

    <button type="submit" class="btn btn-primary">
        Enregistrer
    </button>

</div>
