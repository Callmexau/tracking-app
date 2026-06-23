<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transfer; 
use App\Models\AuditLog; 
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
        $transfers = $query->latest()->paginate(10);

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
        $validatedData = $request->validate([
            'date_depot'            => 'required|date',
            'segment_clientele'     => 'required|string|in:ECG,CCB,CIB',
            'ref_n98'               => 'nullable|string|max:50|unique:transfers,ref_n98', // <-- Validation d'unicité ajoutée
            'donneur_ordre'         => 'required|string|max:255',
            'beneficiaire'          => 'required|string|max:255',
            'reference_transaction' => 'nullable|string|max:100',
            'devise'                => 'required|string|in:XAF,XOF,EUR,USD,CAD,GBP',
            'montant_ordre'         => 'required|numeric|min:0',
            'montant_devise_prefinance' => 'nullable|numeric|min:0',
            'situation_dossier'     => 'nullable|string|in:Préfinancement,Allocation,Instance,Marge',
            'numero_allocation'     => 'nullable|required_if:situation_dossier,Allocation|string|max:50',
            'date_envoi_beac'       => 'nullable|date',
            'date_decision'         => 'nullable|date',
            'decision_beac'         => 'nullable|string|in:Favorable,Rejet,Suspens BEAC',
            'date_reception_mt999'  => 'nullable|date',
            'date_envoi_couverture_xaf' => 'nullable|date',
            'date_reception_devise' => 'nullable|date',
            'conditions_reunies_le' => 'nullable|date',
            'date_traitement'       => 'nullable|date',
            'statut'                => 'required|string|in:Non traité,Traité,Rejet',
            'commentaire'           => 'nullable|string',
        ]);

        // Ajout de l'ID de l'utilisateur authentifié si disponible
        if (auth()->check()) {
            $validatedData['created_by'] = auth()->id();
        }

        // Calcul automatique du délai si la date de traitement et la date de dépôt sont remplies
        if (!empty($validatedData['date_traitement']) && !empty($validatedData['date_depot'])) {
            $debut = Carbon::parse($validatedData['date_depot']);
            $fin = Carbon::parse($validatedData['date_traitement']);
            
            $diff = $debut->diffInDays($fin);
            if ($diff >= 0) {
                $validatedData['delai_traitement'] = $diff . ' jour(s)';
            }
        }

        // Sauvegarde dans la base de données via le modèle Transfer
        $transfer = Transfer::create($validatedData);

        // Journalisation (Log) technique
        Log::info("Nouveau transfert enregistré avec succès.", [
            'transfer_id'   => $transfer->id,
            'donneur_ordre' => $transfer->donneur_ordre,
            'montant'       => $transfer->montant_ordre,
            'enregistre_par' => auth()->id() ? 'Utilisateur ID: ' . auth()->id() : 'Système'
        ]);

        // Enregistrement dans les logs d'audit affichables dans l'application
        AuditLog::create([
            'user_id' => auth()->id(), // Enregistre l'OPS ou le Super Admin connecté
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

    public function export(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        
        // Nom du fichier avec horodatage et extension .xlsx
        $fileName = 'export_transferts_' . date('Y_m_d_His') . '.xlsx';

        // Enregistrement de l'action d'export dans les logs système de l'application
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'EXPORT',
            'description' => 'Exportation des données de transferts au format Excel (Période du ' . ($start ?? 'début') . ' au ' . ($end ?? 'fin') . ')',
        ]);

        // Utilisation de la classe de la façade Excel pointant vers la classe d'export dédiée
        return Excel::download(new TransfersExport($start, $end), $fileName);
    }
}