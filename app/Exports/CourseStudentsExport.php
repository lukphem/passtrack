<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseStudentsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $students;
    protected $course;
    protected $filters;

    public function __construct($students, $course, $filters = [])
    {
        $this->students = $students;
        $this->course = $course;
        $this->filters = $filters;
    }

    /* ===============================
       DATA
    ================================ */
    public function collection()
    {
        return $this->students->values()->map(function ($student, $index) {
            return [
                '#'          => $index + 1,
                'Matric No'  => $student->matric_no,
                'Name'       => ($student->user->first_name ?? '') . ' ' . ($student->user->last_name ?? ''),
                'Programme'  => $student->programme->programme_name ?? 'N/A',
                'Level'      => $student->level ?? 'N/A',
                'Attendance' => $student->attendance ?? 'N/A',
            ];
        });
    }

    /* ===============================
       HEADINGS (Row 4)
    ================================ */
    public function headings(): array
    {
        return [
            'S/N',
            'Matric No',
            'Name',
            'Programme',
            'Level',
            'Attendance',
        ];
    }

    /* ===============================
       HEADER (Row 1–3)
    ================================ */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lecturer = auth()->user()->lecturer;
                $lecturerName = $lecturer->first_name . ' ' . $lecturer->last_name;

                $courseTitle = $this->course->course_code . ' - ' . $this->course->course_title;

                $session = $this->filters['session_name'] ?? 'All Sessions';
                $semester = $this->filters['semester_name'] ?? 'All Semesters';

                // Row 1: Title
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', "COURSE STUDENTS REPORT");

                // Row 2: Lecturer + Course
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', "Lecturer: {$lecturerName} | Course: {$courseTitle}");

                // Row 3: Session + Semester + Date
                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue(
                    'A3',
                    "Session: {$session} | Semester: {$semester} | Generated: " . now()->format('d M Y, h:i A')
                );
            },
        ];
    }

    /* ===============================
       STYLING
    ================================ */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            4 => [ // headings
                'font' => ['bold' => true],
            ],
        ];
    }
}
