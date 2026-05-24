<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LecturerStudentsExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $filters;
    protected $lecturer;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->lecturer = auth()->user()->lecturer;
    }

    /* ===============================
       DATA COLLECTION
    ================================ */
    public function collection()
    {
        $lecturerId = $this->lecturer->id;

        $query = DB::table('coursereg_student')
            ->join('students', 'students.id', '=', 'coursereg_student.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('courses', 'courses.id', '=', 'coursereg_student.course_id')
            ->leftJoin('programmes', 'programmes.id', '=', 'students.programme_id')
            ->leftJoin('academic_sessions', 'academic_sessions.id', '=', 'coursereg_student.session_id')
            ->leftJoin('semesters', 'semesters.id', '=', 'coursereg_student.semester_id')
            ->where('courses.lecturer_id', $lecturerId)

            ->when($this->filters['course_id'] ?? null, fn($q, $id) =>
                $q->where('coursereg_student.course_id', $id)
            )
            ->when($this->filters['session_id'] ?? null, fn($q, $id) =>
                $q->where('coursereg_student.session_id', $id)
            )
            ->when($this->filters['semester_id'] ?? null, fn($q, $id) =>
                $q->where('coursereg_student.semester_id', $id)
            )
            ->when($this->filters['programme_id'] ?? null, fn($q, $id) =>
                $q->where('students.programme_id', $id)
            )
            ->when($this->filters['level'] ?? null, fn($q, $level) =>
                $q->where('students.level', $level)
            );

        $data = $query->select(
            'students.matric_no',
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) as student_name"),
            'courses.course_code',
            'courses.course_title',
            'programmes.programme_name',
            'students.level',
            'academic_sessions.session_name',
            'semesters.semester_name'
        )->get();

        return $data->values()->map(function ($item, $index) {
            return [
                '#' => $index + 1,
                'Matric No' => $item->matric_no,
                'Student Name' => $item->student_name,
                'Course Code' => $item->course_code,
                'Course Title' => $item->course_title,
                'Programme' => $item->programme_name,
                'Level' => $item->level,
                'Session' => $item->session_name,
                'Semester' => $item->semester_name,
            ];
        });
    }

    /* ===============================
       HEADINGS (Row 3)
    ================================ */
    public function headings(): array
    {
        return [
            '#',
            'Matric No',
            'Student Name',
            'Course Code',
            'Course Title',
            'Programme',
            'Level',
            'Session',
            'Semester',
        ];
    }

    /* ===============================
       HEADER + DATE (Row 1 & 2)
    ================================ */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lecturerName = $this->lecturer->first_name . ' ' . $this->lecturer->last_name;

                $title = "LECTURER STUDENTS REPORT - {$lecturerName}";
                $date  = "Generated: " . now()->format('d M Y, h:i A');

                // Row 1 (Title)
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', $title);

                // Row 2 (Date)
                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', $date);
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
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
