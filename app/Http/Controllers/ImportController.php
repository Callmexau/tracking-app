<?php

namespace App\Http\Controllers;

use App\Exports\ImportsExport;
use App\Models\AuditLog;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        $query = Import::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom_client_importateur', 'like', "%{$search}%")
                    ->orWhere('nom_client_exportateur', 'like', "%{$search}%")
                    ->orWhere('reference_facture', 'like', "%{$search}%")
                    ->orWhere('ref_transaction', 'like', "%{$search}%");
            });
        }

        if ($start = $request->input('start_date')) {
            $query->whereRaw('DATE(date_domiciliation) >= ?', [$start]);
        }

        if ($end = $request->input('end_date')) {
            $query->whereRaw('DATE(date_domiciliation) <= ?', [$end]);
        }

        $imports = $query->orderBy('date_domiciliation', 'desc')->paginate(20)->withQueryString();

        return view('imports.index', compact('imports'));
    }

    public function create()
    {
        return view('imports.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateImport($request);
        $validated['created_by'] = Auth::id();
        $import = Import::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CRÉATION',
            'description' => 'Enregistrement d\'une nouvelle importation (Réf facture : ' . ($import->reference_facture ?? 'N/A') . ') pour ' . $import->nom_client_importateur . ' / ' . $import->nom_client_exportateur,
        ]);

        return redirect()->route('imports.index')->with('success', 'Importation enregistrée avec succès.');
    }

    public function show(Import $import)
    {
        return view('imports.show', compact('import'));
    }

    public function edit(Import $import)
    {
        return view('imports.edit', compact('import'));
    }

    public function update(Request $request, Import $import)
    {
        $validated = $this->validateImport($request, $import->id);
        $import->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'MODIFICATION',
            'description' => 'Mise à jour de l\'importation (Réf facture : ' . ($import->reference_facture ?? 'N/A') . ') pour ' . $import->nom_client_importateur . ' / ' . $import->nom_client_exportateur,
        ]);

        return redirect()->route('imports.index')->with('success', 'Importation modifiée avec succès.');
    }

    public function destroy(Import $import)
    {
        $import->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'SUPPRESSION',
            'description' => 'Suppression de l\'importation (Réf facture : ' . ($import->reference_facture ?? 'N/A') . ') pour ' . $import->nom_client_importateur . ' / ' . $import->nom_client_exportateur,
        ]);

        return redirect()->route('imports.index')->with('success', 'Importation supprimée avec succès.');
    }

    public function export(Request $request)
    {
        $fileName = 'importations_' . date('Y_m_d_His') . '.xlsx';

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'EXPORT',
            'description' => 'Exportation des données des importations au format Excel.',
        ]);

        return Excel::download(
            new ImportsExport(
                $request->input('search'),
                $request->input('start_date'),
                $request->input('end_date')
            ),
            $fileName
        );
    }

    private function validateImport(Request $request, $importId = null)
    {
        return $request->validate([
            'date_domiciliation' => 'nullable|date',
            'segment_commercial' => 'nullable|string|max:255',
            'nom_client_importateur' => 'nullable|string|max:255',
            'nom_client_exportateur' => 'nullable|string|max:255',
            'devise' => 'nullable|string|max:10',
            'reference_facture' => 'nullable|string|max:255',
            'montant_facture_contrat_commercial' => 'nullable|numeric',
            'montant_reglement' => 'nullable|string|max:255',
            'montant_di' => 'nullable|string|max:255',
            'ref_declaration_detail' => 'nullable|string|max:255',
            'montant_declaration_detail' => 'nullable|string|max:255',
            'ref_quittance_paiement_droits_et_taxes_douane' => 'nullable|string|max:255',
            'montant_quittance' => 'nullable|string|max:255',
            'numero_di' => 'nullable|string|max:255',
            'pays' => 'nullable|string|max:255',
            'date_apurement' => 'nullable|string|max:255',
            'mise_en_demeure' => 'nullable|string|max:255',
            'code_identification_unique_importateur' => 'nullable|string|max:255',
            'type_importation' => 'nullable|string|max:50',
            'nature_importation' => 'nullable|string|max:50',
            'ref_domiciliation' => 'nullable|string|max:255',
            'statut_apurement' => 'nullable|string|max:255',
            'vlc_ad_ah' => 'nullable|string|max:50',
            'references_mt298' => 'nullable|string|max:255',
            'date_reglement' => 'nullable|string|max:255',
            'ref_transaction' => 'nullable|string|max:255',
        ]);
    }
}
