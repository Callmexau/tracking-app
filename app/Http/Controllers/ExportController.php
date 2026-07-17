<?php

namespace App\Http\Controllers;

use App\Exports\ExportsExport;
use App\Models\AuditLog;
use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Liste des exportations.
     */
    public function index(Request $request)
    {
        $query = Export::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_exportateur', 'like', "%{$search}%")
                    ->orWhere('reference_domiciliation', 'like', "%{$search}%")
                    ->orWhere('reference_facture_contrat', 'like', "%{$search}%")
                    ->orWhere('devise', 'like', "%{$search}%");
            });
        }

        if ($start = $request->input('start_date')) {
            $query->whereRaw('DATE(date_domiciliation) >= ?', [$start]);
        }

        if ($end = $request->input('end_date')) {
            $query->whereRaw('DATE(date_domiciliation) <= ?', [$end]);
        }

        $exports = $query->orderBy('numero', 'asc')->paginate(20)->withQueryString();

        return view('exports.index', compact('exports'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('exports.create');
    }

    /**
     * Enregistrement d'une exportation.
     */
    public function store(Request $request)
    {
        $rules = [
            'numero' => 'required|string|max:255|unique:exports,numero',
            'nom_exportateur' => 'required|string|max:255',
            'date_domiciliation' => 'required|date',
            'reference_domiciliation' => 'required|string|max:255|unique:exports,reference_domiciliation',

            'reference_facture_contrat' => 'required|string|max:255',

            'devise' => 'required|string|max:10',

            'montant_facture' => 'required|numeric',
            'montant_reglement' => 'nullable|numeric',

            'reference_bon_embarquer' => 'nullable|string|max:255',
            'montant_bon_embarquer' => 'nullable|numeric',

            'reference_quittance' => 'nullable|string|max:255',
            'montant_quittance' => 'nullable|numeric',

            'nature_exportation' => 'required|in:Bien,Service',

            'date_ouverture_dossier' => 'required|date',
            'date_echeance_contrat' => 'nullable|date',
            'date_effective_exportation' => 'nullable|date',
            'date_rapatriement' => 'nullable|date',
            'date_retrocession_beac' => 'nullable|date',
            'date_apurement' => 'nullable|date',

            'statut_dossier' => 'required|in:Non apuré,En cours,Apuré',

            'commentaire' => 'nullable|string',
        ];

        $messages = [
            'required' => 'Le champ :attribute est requis.',
            'date' => "Le champ :attribute doit être une date valide.",
            'numeric' => "Le champ :attribute doit être un nombre.",
            'string' => "Le champ :attribute doit être une chaîne de caractères.",
            'max' => "Le champ :attribute ne doit pas dépasser :max caractères.",
            'in' => "Le champ :attribute contient une valeur invalide.",
            'unique' => "Le champ :attribute a déjà été pris.",
        ];

        $attributes = [
            'numero' => 'Numéro',
            'nom_exportateur' => 'Nom Exportateur',
            'date_domiciliation' => 'Date de domiciliation',
            'reference_domiciliation' => 'Référence de domiciliation',
            'reference_facture_contrat' => 'Référence de la facture définitive ou contrat',
            'devise' => 'Devise',
            'montant_facture' => 'Montant facture / contrat',
            'nature_exportation' => "Nature de l'exportation",
            'date_ouverture_dossier' => 'Date ouverture dossier',
            'statut_dossier' => 'Statut dossier',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $validated['created_by'] = Auth::id();

        $export = Export::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CRÉATION',
            'description' => 'Enregistrement d\'une nouvelle exportation (N° : ' . ($export->numero ?? 'N/A') . ') pour ' . $export->nom_exportateur . ' - Réf domiciliation : ' . $export->reference_domiciliation,
        ]);

        return redirect()
            ->route('exports.index')
            ->with('success', 'Exportation enregistrée avec succès.');
    }

    /**
     * Affichage d'une exportation.
     */
    public function show(Export $export)
    {
        return view('exports.show', compact('export'));
    }

    public function export(Request $request)
    {
        $fileName = 'exportations_' . date('Y_m_d_His') . '.xlsx';

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'EXPORT',
            'description' => 'Exportation des données des exportations au format Excel.',
        ]);

        return Excel::download(
            new ExportsExport(
                $request->input('search'),
                $request->input('start_date'),
                $request->input('end_date')
            ),
            $fileName
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Export $export)
    {
        return view('exports.edit', compact('export'));
    }

    /**
     * Mise à jour d'une exportation.
     */
    public function update(Request $request, Export $export)
    {
        $rules = [
            'numero' => 'required|string|max:255|unique:exports,numero,' . $export->id,
            'nom_exportateur' => 'required|string|max:255',
            'date_domiciliation' => 'required|date',
            'reference_domiciliation' => 'required|string|max:255|unique:exports,reference_domiciliation,' . $export->id,

            'reference_facture_contrat' => 'required|string|max:255',

            'devise' => 'required|string|max:10',

            'montant_facture' => 'required|numeric',
            'montant_reglement' => 'nullable|numeric',

            'reference_bon_embarquer' => 'nullable|string|max:255',
            'montant_bon_embarquer' => 'nullable|numeric',

            'reference_quittance' => 'nullable|string|max:255',
            'montant_quittance' => 'nullable|numeric',

            'nature_exportation' => 'required|in:Bien,Service',

            'date_ouverture_dossier' => 'required|date',
            'date_echeance_contrat' => 'nullable|date',
            'date_effective_exportation' => 'nullable|date',
            'date_rapatriement' => 'nullable|date',
            'date_retrocession_beac' => 'nullable|date',
            'date_apurement' => 'nullable|date',

            'statut_dossier' => 'required|in:Non apuré,En cours,Apuré',

            'commentaire' => 'nullable|string',
        ];

        $messages = [
            'required' => 'Le champ :attribute est requis.',
            'date' => "Le champ :attribute doit être une date valide.",
            'numeric' => "Le champ :attribute doit être un nombre.",
            'string' => "Le champ :attribute doit être une chaîne de caractères.",
            'max' => "Le champ :attribute ne doit pas dépasser :max caractères.",
            'in' => "Le champ :attribute contient une valeur invalide.",
            'unique' => "Le champ :attribute a déjà été pris.",
        ];

        $attributes = [
            'numero' => 'Numéro',
            'nom_exportateur' => 'Nom Exportateur',
            'date_domiciliation' => 'Date de domiciliation',
            'reference_domiciliation' => 'Référence de domiciliation',
            'reference_facture_contrat' => 'Référence de la facture définitive ou contrat',
            'devise' => 'Devise',
            'montant_facture' => 'Montant facture / contrat',
            'nature_exportation' => "Nature de l'exportation",
            'date_ouverture_dossier' => 'Date ouverture dossier',
            'statut_dossier' => 'Statut dossier',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $export->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'MODIFICATION',
            'description' => 'Mise à jour de l\'exportation (N° : ' . ($export->numero ?? 'N/A') . ') pour ' . $export->nom_exportateur . ' - Réf domiciliation : ' . $export->reference_domiciliation,
        ]);

        return redirect()
            ->route('exports.index')
            ->with('success', 'Exportation modifiée avec succès.');
    }
}
