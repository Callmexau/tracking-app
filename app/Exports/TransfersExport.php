<?php

namespace App\Exports;

use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransfersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transfer::query();

        if ($this->startDate) {
            $query->whereDate('date_depot', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('date_depot', '<=', $this->endDate);
        }

        return $query->get();
    }

    /**
     * En-têtes des colonnes dans le fichier Excel exporté
     */
    public function headings(): array
    {
        return [
            'Date de dépôt', 'Segment Clientèle', 'Réf N98', 
            'Donneur d\'ordre', 'Bénéficiaire', 'Réf Transaction', 'Devise', 
            'Montant Ordre', 'Montant Devise Préf.', 'Situation Dossier', 
            'Envoi BEAC', 'Date Décision', 'Décision BEAC', 
            'Réception MT999', 'Envoi Couverture', 'Réception Devise', 
            'Conditions Réunies', 'Date Traitement', 'Statut', 'Commentaire', 
            'Délai Traitement'
        ];
    }

    /**
     * Mappe les colonnes de la base de données
     * @param mixed $transfer
     * @return array
     */
    public function map($transfer): array
    {
        $situation = $transfer->situation_dossier;
        
        if ($situation === 'Allocation' && !empty($transfer->numero_allocation)) {
            $situation .= ' - ' . $transfer->numero_allocation;
        }

        // Fonction locale pour extraire uniquement la date (YYYY-MM-DD) sans l'heure
        $formatDate = function($date) {
            return $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : null;
        };

        return [
            $formatDate($transfer->date_depot),
            $transfer->segment_clientele,
            $transfer->ref_n98,
            $transfer->donneur_ordre,
            $transfer->beneficiaire,
            $transfer->reference_transaction,
            $transfer->devise,
            $montantOrdre = is_numeric($transfer->montant_ordre) ? (float)$transfer->montant_ordre : $transfer->montant_ordre,
            $transfer->montant_devise_prefinance,
            $situation,
            $formatDate($transfer->date_envoi_beac),
            $formatDate($transfer->date_decision),
            $transfer->decision_beac,
            $formatDate($transfer->date_reception_mt999),
            $formatDate($transfer->date_envoi_couverture_xaf),
            $formatDate($transfer->date_reception_devise),
            $formatDate($transfer->conditions_reunies_le),
            $formatDate($transfer->date_traitement),
            $transfer->statut,
            $transfer->commentaire,
            $transfer->delai_traitement,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Style des en-têtes (Ligne 1)
        $sheet->getStyle('A1:U1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'], // Texte blanc
                'name' => 'Calibri',
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => '000000'], // Fond noir
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 2. Hauteur de la ligne d'en-tête
        $sheet->getRowDimension(1)->setRowHeight(25);

        // 3. Ajout de bordures fines sur tout le tableau
        $maxRow = $sheet->getHighestRow();
        if ($maxRow > 1) {
            $sheet->getStyle('A2:U' . $maxRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'D3D3D3'], // Bordure gris clair
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true, // <-- ACTIVE LE RETOUR À LA LIGNE DANS LES CELLULES
                ],
            ]);
        }

        // 4. DONNER DE L'ESPACE : Forcer des largeurs de colonnes confortables (au lieu de l'autoSize qui compresse)
        // Vous pouvez ajuster ces valeurs en millimètres ou caractères si besoin
        $columnWidths = [
            'A' => 15, // Date de dépôt
            'B' => 18, // Segment Clientèle
            'C' => 15, // Réf N98
            'D' => 25, // Donneur d'ordre
            'E' => 25, // Bénéficiaire
            'F' => 25, // Réf Transaction
            'G' => 12, // Devise
            'H' => 18, // Montant Ordre
            'I' => 18, // Montant Devise Préf.
            'J' => 25, // Situation Dossier (Allocation - 0034 tiendra parfaitement sur 2 lignes)
            'K' => 15, // Envoi BEAC
            'L' => 15, // Date Décision
            'M' => 18, // Décision BEAC
            'N' => 18, // Réception MT999
            'O' => 18, // Envoi Couverture
            'P' => 18, // Réception Devise
            'Q' => 18, // Conditions Réunies
            'R' => 18, // Date Traitement
            'S' => 15, // Statut
            'T' => 30, // Commentaire
            'U' => 18, // Délai Traitement
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }
}