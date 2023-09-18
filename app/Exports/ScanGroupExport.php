<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScanGroupExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements  WithCustomValueBinder, WithTitle, FromArray, WithHeadings, WithCustomStartCell, WithColumnWidths, WithStyles
{
    protected $postIds;

    public function __construct(array $postIds)
    {
        $this->postIds = $postIds;
    }

    public function array(): array
    {
        return $this->postIds;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "Scan Group";
    }

    public function headings(): array
    {
        return ['id', 'Name', 'Keyword'];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 55,
            'B' => 55,
            'C' => 55,
        ];
    }

    public
    function styles(Worksheet $sheet)
    {
        $cellCor = 'A1:C1';

        $sheet->getStyle($cellCor)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'color' => [
                    'argb' => 'ffffffff',
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'ff91d2ff',
                ],
            ]
        ]);
    }

}
