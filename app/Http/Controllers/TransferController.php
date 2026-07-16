<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transfer;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransfersExport;

class TransferController extends Controller
{
    // Affiche la liste paginée des transferts avec prise en charge de la recherche
    public function index(Request $request)
    {
        $query = Transfer::query();

        // Filtrage par recherche
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('donneur_ordre', 'like', "%{$search}%")
                    ->orWhere('beneficiaire', 'like', "%{$search}%")
                    ->orWhere('ref_n98', 'like', "%{$search}%")
                    ->orWhere('reference_transaction', 'like', "%{$search}%");
            });
        }

        // Tri par ordre décroissant et pagination de 10 éléments par page
        $transfers = $query->latest()->paginate(20);

        return view('transfers.index', compact('transfers'));
    }

    // Affiche le formulaire de création d'un nouveau transfert
    public function create()
    {
        return view('transfers.create');
    }

    // Méthode pour enregistrer un nouveau transfert
    public function store(Request $request)
    {
        $validatedData = $this->validateTransfer($request);

        // Ajout de l'ID de l'utilisateur authentifié si disponible
        if (Auth::check()) {
            $validatedData['created_by'] = Auth::id();
        }

        // Calcul automatique du délai si la date de traitement et la date de dépôt sont remplies
        $this->calculateDelay($validatedData);

        // Sauvegarde dans la base de données via le modèle Transfer
        $transfer = Transfer::create($validatedData);

        // Journalisation (Log) technique
        Log::info("Nouveau transfert enregistré avec succès.", [
            'transfer_id'   => $transfer->id,
            'donneur_ordre' => $transfer->donneur_ordre,
            'montant'       => $transfer->montant_ordre,
            'enregistre_par' => Auth::id() ? 'Utilisateur ID: ' . Auth::id() : 'Système'
        ]);

        // Enregistrement dans les logs d'audit affichables dans l'application
        AuditLog::create([
            'user_id' => Auth::id(), // Enregistre l'OPS ou le Super Admin connecté
            'action' => 'CRÉATION',
            'description' => 'Enregistrement d\'un nouveau transfert (Réf N98 : ' . ($transfer->ref_n98 ?? 'N/A') . ') au profit de ' . $transfer->beneficiaire . ' (Montant : ' . number_format($transfer->montant_ordre, 2, ',', ' ') . ' ' . $transfer->devise . ')',
        ]);

        return redirect()->route('transfers.index')->with('success', 'Transfert enregistré avec succès.');
    }

    // Affiche les détails d'un transfert spécifique
    public function show($id)
    {
        $transfer = Transfer::findOrFail($id);

        return view('transfers.show', compact('transfer'));
    }

    // Affiche le formulaire de modification d'un transfert
    public function edit($id)
    {
        $transfer = Transfer::findOrFail($id);

        return view('transfers.edit', compact('transfer'));
    }

    // Met à jour un transfert existant dans la base de données
    public function update(Request $request, $id)
    {
        $transfer = Transfer::findOrFail($id);

        // Pour la règle unique sur ref_n98, on ignore la référence actuelle du transfert en cours de modification
        $validatedData = $request->validate([
            'date_depot'                => 'required|date',
            'segment_clientele'         => 'required|string|in:ECG,CCB,CIB',
            'ref_n98'                   => 'required|string|max:50|unique:transfers,ref_n98,' . $transfer->id,
            'donneur_ordre'             => 'required|string|max:255',
            'beneficiaire'              => 'required|string|max:255',
            'reference_transaction'     => 'nullable|string|max:100',
            'devise'                    => 'required|string|in:XAF,XOF,EUR,USD,CAD,GBP',
            'montant_ordre'             => 'required|numeric|min:0',
            'montant_devise_prefinance' => 'nullable|numeric|min:0',
            'situation_dossier'         => 'nullable|string|in:Préfinancement,Allocation,Instance,Marge',
            'numero_allocation'         => 'nullable|required_if:situation_dossier,Allocation|string|max:50',
            'date_envoi_beac'           => 'nullable|date',
            'date_decision'             => 'nullable|date',
            'decision_beac'             => 'nullable|string|in:Favorable,Rejet,Suspens BEAC',
            'date_reception_mt999'      => 'nullable|date',
            'date_envoi_couverture_xaf' => 'nullable|date',
            'date_reception_devise'     => 'nullable|date',
            'conditions_reunies_le'     => 'nullable|date',
            'date_traitement'           => 'nullable|date',
            'statut'                    => 'required|string|in:Non traité,Traité,Rejet',
            'commentaire'               => 'nullable|string',
        ]);

        // Calcul automatique du délai si les dates sont modifiées
        $this->calculateDelay($validatedData);

        // Mise à jour des données
        $transfer->update($validatedData);

        // Journalisation (Log) technique
        Log::info("Transfert mis à jour avec succès.", [
            'transfer_id'   => $transfer->id,
            'modifie_par' => Auth::id() ? 'Utilisateur ID: ' . Auth::id() : 'Système'
        ]);

        // Enregistrement dans les logs d'audit
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'MODIFICATION',
            'description' => 'Mise à jour du transfert (Réf N98 : ' . ($transfer->ref_n98 ?? 'N/A') . ') au profit de ' . $transfer->beneficiaire,
        ]);

        return redirect()->route('transfers.index')->with('success', 'Transfert mis à jour avec succès.');
    }

    public function export(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // Nom du fichier avec horodatage et extension .xlsx
        $fileName = 'export_transferts_' . date('Y_m_d_His') . '.xlsx';

        // Enregistrement de l'action d'export dans les logs système
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'EXPORT',
            'description' => 'Exportation des données de transferts au format Excel (Période du ' . ($start ?? 'début') . ' au ' . ($end ?? 'fin') . ')',
        ]);

        // Utilisation de la classe de la façade Excel pointant vers la classe d'export dédiée
        return Excel::download(new TransfersExport($start, $end), $fileName);
    }

    // Validation des données du transfert avec règles spécifiques
    private function validateTransfer(Request $request)
    {
        return $request->validate([
            'date_depot'                => 'required|date',
            'segment_clientele'         => 'required|string|in:ECG,CCB,CIB',
            'ref_n98'                   => 'required|string|max:50|unique:transfers,ref_n98',
            'donneur_ordre'             => 'required|string|max:255',
            'beneficiaire'              => 'required|string|max:255',
            'reference_transaction'     => 'nullable|string|max:100',
            'devise'                    => 'required|string|in:XAF,XOF,EUR,USD,CAD,GBP',
            'montant_ordre'             => 'required|numeric|min:0',
            'montant_devise_prefinance' => 'nullable|numeric|min:0',
            'situation_dossier'         => 'nullable|string|in:Préfinancement,Allocation,Instance,Marge',
            'numero_allocation'         => 'nullable|required_if:situation_dossier,Allocation|string|max:50',
            'date_envoi_beac'           => 'nullable|date',
            'date_decision'             => 'nullable|date',
            'decision_beac'             => 'nullable|string|in:Favorable,Rejet,Suspens BEAC',
            'date_reception_mt999'      => 'nullable|date',
            'date_envoi_couverture_xaf' => 'nullable|date',
            'date_reception_devise'     => 'nullable|date',
            'conditions_reunies_le'     => 'nullable|date',
            'date_traitement'           => 'nullable|date',
            'statut'                    => 'required|string|in:Non traité,Traité,Rejet',
            'commentaire'               => 'nullable|string',
        ]);
    }

    // Calcul automatique du délai de traitement en jours si les dates sont remplies
    private function calculateDelay(array &$data)
    {
        if (!empty($data['date_traitement']) && !empty($data['date_depot'])) {
            $debut = Carbon::parse($data['date_depot']);
            $fin = Carbon::parse($data['date_traitement']);

            $diff = $debut->diffInDays($fin);
            if ($diff >= 0) {
                $data['delai_traitement'] = $diff . ' jour(s)';
            }
        }
    }
}
