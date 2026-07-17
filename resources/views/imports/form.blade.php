<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Date domiciliation</label>
        <input type="date" name="date_domiciliation" class="form-control" value="{{ old('date_domiciliation', isset($import) ? optional($import->date_domiciliation)->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Segment commercial</label>
        <input type="text" name="segment_commercial" class="form-control" value="{{ old('segment_commercial', $import->segment_commercial ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nom du client importateur</label>
        <input type="text" name="nom_client_importateur" class="form-control" value="{{ old('nom_client_importateur', $import->nom_client_importateur ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nom du client exportateur</label>
        <input type="text" name="nom_client_exportateur" class="form-control" value="{{ old('nom_client_exportateur', $import->nom_client_exportateur ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Devise</label>
        <select name="devise" class="form-select">
            <option value="">Sélectionner une devise</option>
            <option value="XAF" {{ old('devise', $import->devise ?? '') == 'XAF' ? 'selected' : '' }}>Franc CFA (XAF)</option>
            <option value="XOF" {{ old('devise', $import->devise ?? '') == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
            <option value="USD" {{ old('devise', $import->devise ?? '') == 'USD' ? 'selected' : '' }}>Dollar américain (USD)</option>
            <option value="EUR" {{ old('devise', $import->devise ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
            <option value="GBP" {{ old('devise', $import->devise ?? '') == 'GBP' ? 'selected' : '' }}>Livre sterling (GBP)</option>
            <option value="CHF" {{ old('devise', $import->devise ?? '') == 'CHF' ? 'selected' : '' }}>Franc suisse (CHF)</option>
            <option value="CAD" {{ old('devise', $import->devise ?? '') == 'CAD' ? 'selected' : '' }}>Dollar canadien (CAD)</option>
            <option value="AUD" {{ old('devise', $import->devise ?? '') == 'AUD' ? 'selected' : '' }}>Dollar australien (AUD)</option>
            <option value="JPY" {{ old('devise', $import->devise ?? '') == 'JPY' ? 'selected' : '' }}>Yen japonais (JPY)</option>
            <option value="CNY" {{ old('devise', $import->devise ?? '') == 'CNY' ? 'selected' : '' }}>Yuan chinois (CNY)</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Référence de la facture</label>
        <input type="text" name="reference_facture" class="form-control" value="{{ old('reference_facture', $import->reference_facture ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Montant facture/contrat commercial</label>
        <input type="number" step="0.01" name="montant_facture_contrat_commercial" class="form-control" value="{{ old('montant_facture_contrat_commercial', $import->montant_facture_contrat_commercial ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Montant de règlement</label>
        <input type="text" name="montant_reglement" class="form-control" value="{{ old('montant_reglement', $import->montant_reglement ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Montant de la DI</label>
        <input type="text" name="montant_di" class="form-control" value="{{ old('montant_di', $import->montant_di ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Réf de la déclaration en détail</label>
        <input type="text" name="ref_declaration_detail" class="form-control" value="{{ old('ref_declaration_detail', $import->ref_declaration_detail ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Montant de la déclaration en détail</label>
        <input type="text" name="montant_declaration_detail" class="form-control" value="{{ old('montant_declaration_detail', $import->montant_declaration_detail ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Réf de la quittance de paiement des droits et taxes de douane</label>
        <input type="text" name="ref_quittance_paiement_droits_et_taxes_douane" class="form-control" value="{{ old('ref_quittance_paiement_droits_et_taxes_douane', $import->ref_quittance_paiement_droits_et_taxes_douane ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Montant de la quittance</label>
        <input type="text" name="montant_quittance" class="form-control" value="{{ old('montant_quittance', $import->montant_quittance ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">N° de la DI</label>
        <input type="text" name="numero_di" class="form-control" value="{{ old('numero_di', $import->numero_di ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Pays</label>
        <input type="text" name="pays" class="form-control" value="{{ old('pays', $import->pays ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date d'apurement</label>
        <input type="text" name="date_apurement" class="form-control" value="{{ old('date_apurement', isset($import) ? (is_string($import->date_apurement) ? $import->date_apurement : optional($import->date_apurement)->format('Y-m-d')) : '') }}" placeholder="JJ/MM/AAAA ou texte libre">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Mise en demeure</label>
        <input type="text" name="mise_en_demeure" class="form-control" value="{{ old('mise_en_demeure', $import->mise_en_demeure ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Code identification unique importateur</label>
        <input type="text" name="code_identification_unique_importateur" class="form-control" value="{{ old('code_identification_unique_importateur', $import->code_identification_unique_importateur ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Type d'importation</label>
        <input type="text" name="type_importation" class="form-control" value="{{ old('type_importation', $import->type_importation ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Nature importation</label>
        <input type="text" name="nature_importation" class="form-control" value="{{ old('nature_importation', $import->nature_importation ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Réf domiciliation</label>
        <input type="text" name="ref_domiciliation" class="form-control" value="{{ old('ref_domiciliation', $import->ref_domiciliation ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Statut apurement</label>
        <input type="text" name="statut_apurement" class="form-control" value="{{ old('statut_apurement', $import->statut_apurement ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">VLC/AD/AH</label>
        <select name="vlc_ad_ah" class="form-select">
            <option value="">Sélectionner</option>
            <option value="VLC" {{ old('vlc_ad_ah', $import->vlc_ad_ah ?? '') == 'VLC' ? 'selected' : '' }}>VLC</option>
            <option value="AD" {{ old('vlc_ad_ah', $import->vlc_ad_ah ?? '') == 'AD' ? 'selected' : '' }}>AD</option>
            <option value="AH" {{ old('vlc_ad_ah', $import->vlc_ad_ah ?? '') == 'AH' ? 'selected' : '' }}>AH</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Références MT298</label>
        <input type="text" name="references_mt298" class="form-control" value="{{ old('references_mt298', $import->references_mt298 ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de règlement</label>
        <input type="text" name="date_reglement" class="form-control" value="{{ old('date_reglement', isset($import) ? (is_string($import->date_reglement) ? $import->date_reglement : optional($import->date_reglement)->format('Y-m-d')) : '') }}" placeholder="JJ/MM/AAAA ou texte libre">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Réf transaction</label>
        <input type="text" name="ref_transaction" class="form-control" value="{{ old('ref_transaction', $import->ref_transaction ?? '') }}">
    </div>
</div>
