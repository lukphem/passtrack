<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsTemplateExport implements WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'programme_id',
            'matric_no',
            'mode_of_admission',
            'entry_level',
            'level',
            'phone',
            'gender',
            'date_of_birth',
            'nationality',
            'state_of_origin',
            'lga_of_origin',
            'address',
        ];
    }

    /**
     * Optional: Add styling & example row
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Bold header
                $event->sheet->getStyle('A1:O1')->getFont()->setBold(true);

                // Sample row (Row 2)
                $event->sheet->setCellValue('A2', 'John');
                $event->sheet->setCellValue('B2', 'Doe');
                $event->sheet->setCellValue('C2', 'john@example.com');
                $event->sheet->setCellValue('D2', '1');
                $event->sheet->setCellValue('E2', 'CSC/2026/001');
                $event->sheet->setCellValue('F2', 'UTME');
                $event->sheet->setCellValue('G2', '100');
                $event->sheet->setCellValue('H2', '100');
                $event->sheet->setCellValue('I2', '+2348012345678');
                $event->sheet->setCellValue('J2', 'male');
                $event->sheet->setCellValue('K2', '2000-01-01');
                $event->sheet->setCellValue('L2', 'Nigeria');
                $event->sheet->setCellValue('M2', 'Lagos');
                $event->sheet->setCellValue('N2', 'Ikeja');
                $event->sheet->setCellValue('O2', '123 Street, Lagos');
            },
        ];
    }
}
