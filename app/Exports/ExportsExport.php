<?php

namespace App\Exports;

use App\Models\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Export::orderBy('numero', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Numero',
            'Nom Exportateur',
            'Date de Domiciliation',
            'Référence de Domiciliation',
            'Référence de la Facture Définitive ou Contrat',
            'Devises',
            'Montant de la Facture Définitive ou Contrat',
            'Montant Règlement de l\'Exportation',
            'Référence Bon à Embarquer',
            'Montant Bon à Embarquer',
            'Référence Quittance de Paiement des Droits et Taxes de Douane Dus Liés à l\'Exportation',
            'Montant de la Quittance de Paiement des Droits et Taxes de Douane Dus Liés à l\'Exportation',
            'Nature de l\'Exportation (Bien ou Service)',
            'Date d\'Ouverture du Dossier',
            'Date d\'Échéance du Contrat d\'Exportation',
            'Date Effective d\'Exportation',
            'Date de Rapatriement effectif des Devises à BEAC',
            'Date d\'Apurement',
            'Statut Dossier',
        ];
    }

    public function map($export): array
    {
        return [
            $export->numero,
            $export->nom_exportateur,
            optional($export->date_domiciliation)->format('Y-m-d'),
            $export->reference_domiciliation,
            $export->reference_facture_contrat,
            $export->devise,
            $export->montant_facture,
            $export->montant_reglement,
            $export->reference_bon_embarquer,
            $export->montant_bon_embarquer,
            $export->reference_quittance,
            $export->montant_quittance,
            $export->nature_exportation,
            optional($export->date_ouverture_dossier)->format('Y-m-d'),
            optional($export->date_echeance_contrat)->format('Y-m-d'),
            optional($export->date_effective_exportation)->format('Y-m-d'),
            optional($export->date_rapatriement)->format('Y-m-d'),
            optional($export->date_apurement)->format('Y-m-d'),
            $export->statut_dossier,
        ];
    }
}
