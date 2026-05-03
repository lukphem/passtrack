<!DOCTYPE html>
<html>
<head>
    <title>Course Registration Slip</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-size: 13.5px;
            padding: 20px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .school-name {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
        }

        .sub-text {
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .info-table td {
            padding: 4px 6px;
        }

        .table th, .table td {
            font-size: 13px;
            padding: 6px;
        }

        .signature-section {
            margin-top: 70px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 12px;
        }

        /* ✅ WATERMARK FIXED */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 400px;
            height: auto;
            filter: grayscale(100%);
        }

        .container {
            position: relative;
            z-index: 1;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

{{-- ================= WATERMARK ================= --}}
@if(config('school.logo'))
<div class="watermark">
    <img src="{{ asset('storage/school/logo.jpg') }}" alt="Watermark">
</div>
@endif

<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="header">

        {{-- SCHOOL BRANDING (UNCHANGED AS REQUESTED) --}}
        <div class="text-center mb-2">

            @if(config('school.logo'))
                <img src="{{ asset('storage/school/logo.jpg') }}"
                    alt="Logo"
                    style="height:60px; object-fit:contain; margin-bottom:5px;">
            @else
                <div style="font-size:40px;">🎓</div>
            @endif

            <div class="school-name">
                {{ config('school.name', 'SCHOOL NAME') }}
            </div>
        </div>

        <div class="sub-text">Office of the Registrar</div>

        <div class="sub-text" style="font-size:16px; margin-top:5px;">
            {{ $session->session_name }} - {{ $semester->semester_name }} Semester
        </div>

        <div class="title">
            COURSE REGISTRATION SLIP
        </div>

    </div>

    {{-- ================= STUDENT INFO ================= --}}
    <table class="table table-borderless info-table mb-2">
        <tr>
            <td><strong>Name:</strong> {{ $student->user->first_name }} {{ $student->user->last_name }}</td>
            <td><strong>Matric No:</strong> {{ $student->matric_no }}</td>
        </tr>
        <tr>
            <td><strong>Programme:</strong> {{ $programme->programme_name }}</td>
            <td><strong>Level:</strong> {{ $student->level }}</td>
        </tr>
    </table>

    {{-- ================= COURSES ================= --}}
    <table class="table table-bordered" >
        <thead class="table-dark text-white">
            <tr>
                <th width="40">#</th>
                <th>Course Code</th>
                <th>Course Title</th>
                <th width="90">Type</th>
                <th width="70">Units</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registeredCourses as $i => $course)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $course->course_code }}</td>
                <td>{{ $course->course_title }}</td>
                <td>
                    <span style="font-weight:600;">
                        {{ strtoupper($course->course_type) }}
                    </span>
                </td>
                <td>{{ $course->credit_unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= TOTAL ================= --}}
    @php
        $coreUnits = $registeredCourses->where('course_type', 'Core')->sum('credit_unit');
        $electiveUnits = $registeredCourses->where('course_type', 'Elective')->sum('credit_unit');
    @endphp

    <div class="text-end mt-2">
        <strong>Total Units: {{ $totalUnits }}</strong><br>
        <small>Core: {{ $coreUnits }} | Elective: {{ $electiveUnits }}</small>
    </div>

    {{-- ================= SIGNATURE SECTION ================= --}}
    <div class="row signature-section">

        <div class="col-md-3 signature-box">
            <div class="signature-line">Student Signature / Date</div>
        </div>

        <div class="col-md-3 signature-box">
            <div class="signature-line">Academic Advisor</div>
        </div>

        <div class="col-md-3 signature-box">
            <div class="signature-line">Head of Department (HOD)</div>
        </div>

        <div class="col-md-3 signature-box">
            <div class="signature-line">Faculty Officer / Registry</div>
        </div>

    </div>

    {{-- ================= FOOTER ================= --}}
    <div class="mt-5 small text-muted text-center">
        <em>
            This document is system-generated and becomes valid only after required signatures and official stamp.
        </em>
    </div>

    {{-- ================= PRINT BUTTON ================= --}}
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-dark">
            Print
        </button>
    </div>

</div>

</body>
</html>
