<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::with('student.programme.department')
            ->where('role', 'student');

        // SEARCH
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhereHas('student', function ($q2) use ($search) {
                        $q2->where('matric_no', 'like', "%$search%");
                    });
            });
        }

        // PROGRAMME
        if (!empty($this->filters['programme_id'])) {
            $programmeId = $this->filters['programme_id'];

            $query->whereHas('student', function ($q) use ($programmeId) {
                $q->where('programme_id', $programmeId);
            });
        }

        // DEPARTMENT
        if (!empty($this->filters['department_id'])) {
            $departmentId = $this->filters['department_id'];

            $query->whereHas('student.programme', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        // LEVEL
        if (!empty($this->filters['level'])) {
            $level = $this->filters['level'];

            $query->whereHas('student', function ($q) use ($level) {
                $q->where('level', $level);
            });
        }

        // MODE OF ADMISSION
        if (!empty($this->filters['mode_of_admission'])) {
            $mode = $this->filters['mode_of_admission'];

            $query->whereHas('student', function ($q) use ($mode) {
                $q->where('mode_of_admission', $mode);
            });
        }

        // STATUS
        if (!empty($this->filters['status'])) {
            $status = $this->filters['status'];

            $query->whereHas('student', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return $query->get()->map(function ($user) {
            return [
                $user->student?->matric_no,
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->student?->programme?->programme_name,
                $user->student?->programme?->department?->dept_name,
                $user->student?->level,
                $user->student?->mode_of_admission,
                $user->student?->status,
                $user->student?->phone,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Matric No',
            'First Name',
            'Last Name',
            'Email',
            'Programme',
            'Department',
            'Level',
            'Entry Mode',
            'Status',
            'Phone',
        ];
    }

    // ✅ BOLD HEADER STYLE
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
