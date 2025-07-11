<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class directorResultsExcel implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $flattened = [];

        foreach ($this->data as $professor) {
            foreach ($professor['coursesPerProfessor'] as $course) {
                $flattened[] = [
                    $professor['Professor'],
                    $course['courses'],
                    $course['scorePerCourse']
                ];
            }
        }

        return $flattened;
    }

    public function headings(): array
    {
        return ["Catedrático", "Clase", "Promedio"];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
            ],
        ];
        $sheet->getStyle($sheet->calculateWorksheetDimension())
        ->getAlignment()
        ->setHorizontal('center');


        return $styles;
    }
}

