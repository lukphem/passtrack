<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\Programme;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
};
use Maatwebsite\Excel\Concerns\SkipsFailures;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $programme = Programme::find($row['programme_id']);

        if (!$programme) {
            return null;
        }

        $user = User::create([
            'first_name' => $row['first_name'],
            'last_name'  => $row['last_name'],
            'email'      => $row['email'],
            'password'   => Hash::make('password'),
            'role'       => 'student',
        ]);

        $matricNo = $row['matric_no'] ?? $this->generateMatricNo($programme);

        return new Student([
            'user_id' => $user->id,
            'programme_id' => $programme->id,
            'matric_no' => $matricNo,
            'mode_of_admission' => $row['mode_of_admission'] ?? null,
            'entry_level' => $row['entry_level'] ?? 100,
            'level' => $row['level'] ?? 100,
            'phone' => $row['phone'] ?? null,
            'gender' => $row['gender'] ?? null,
            'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
            'nationality' => $row['nationality'] ?? null,
            'state_of_origin' => $row['state_of_origin'] ?? null,
            'lga_of_origin' => $row['lga_of_origin'] ?? null,
            'address' => $row['address'] ?? null,
        ]);
    }

    private function parseDate($value)
    {
        if (!$value) {
            return null;
        }

        if (is_string($value)) {
            return date('Y-m-d', strtotime($value));
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return null;
    }

    public function rules(): array
    {
        return [
            '*.first_name' => 'required|string',
            '*.last_name'  => 'required|string',
            '*.email'      => 'required|email|unique:users,email',
            '*.programme_id' => 'required|exists:programmes,id',
            '*.matric_no' => 'nullable|unique:students,matric_no',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.email.required' => 'Email is required',
            '*.email.unique' => 'This email already exists',
            '*.programme_id.exists' => 'Invalid programme selected',
            '*.first_name.required' => 'First name is required',
            '*.last_name.required' => 'Last name is required',
        ];
    }

    private function generateMatricNo($programme)
    {
        $year = date('Y');
        $prefix = strtoupper(substr($programme->programme_name, 0, 3));

        $count = Student::whereYear('created_at', $year)->count() + 1;
        $number = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$year}/{$number}";
    }
}
