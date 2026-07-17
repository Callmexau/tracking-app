<?php

namespace App\Exports;

use App\Models\Import;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?string $search;
    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct(?string $search = null, ?string $startDate = null, ?string $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Import::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom_client_importateur', 'like', "%{$this->search}%")
                    ->orWhere('nom_client_exportateur', 'like', "%{$this->search}%")
                    ->orWhere('reference_facture', 'like', "%{$this->search}%")
                    ->orWhere('ref_transaction', 'like', "%{$this->search}%");
            });
        }

        if ($this->startDate) {
            $query->whereRaw('DATE(date_domiciliation) >= ?', [$this->startDate]);
        }

        if ($this->endDate) {
            $query->whereRaw('DATE(date_domiciliation) <= ?', [$this->endDate]);
        }

        return $query->orderBy('date_domiciliation', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date domiciliation',
            'Segment commercial',
            'Nom du client importateur',
            'Nom du client exportateur',
            'Devise',
            'Référence de la facture',
            'Montant facture/contrat commercial',
            'Montant de règlement',
            'Montant de la DI',
            'Réf de la déclaration en détail',
            'Montant de la déclaration en détail',
            'Réf de la quittance de paiement des droits et taxes de douane',
            'Montant de la quittance',
            'N° de la DI',
            'Pays',
            'Date d\'apurement',
            'Mise en demeure',
            'Code identification unique importateur',
            'Type d\'importation',
            'Nature importation',
            'Réf domiciliation',
            'Statut apurement',
            'VLC/AD/AH',
            'Références MT298',
            'Date de règlement',
            'Réf transaction',
        ];
    }

    public function map($import): array
    {
        return [
            optional($import->date_domiciliation)->format('Y-m-d'),
            $import->segment_commercial,
            $import->nom_client_importateur,
            $import->nom_client_exportateur,
            $import->devise,
            $import->reference_facture,
            $import->montant_facture_contrat_commercial,
            $import->montant_reglement,
            $import->montant_di,
            $import->ref_declaration_detail,
            $import->montant_declaration_detail,
            $import->ref_quittance_paiement_droits_et_taxes_douane,
            $import->montant_quittance,
            $import->numero_di,
            $import->pays,
            optional($import->date_apurement)->format('Y-m-d'),
            $import->mise_en_demeure,
            $import->code_identification_unique_importateur,
            $import->type_importation,
            $import->nature_importation,
            $import->ref_domiciliation,
            $import->statut_apurement,
            $import->vlc_ad_ah,
            $import->references_mt298,
            optional($import->date_reglement)->format('Y-m-d'),
            $import->ref_transaction,
        ];
    }
}
