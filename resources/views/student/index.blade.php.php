@extends('admin.dashboard.layouts.admin')

@section('title', 'Student Management')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Student Management</h4>
        <small class="text-muted">Manage all student records</small>
    </div>

    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Student
    </a>
</div>

{{-- Search Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text"
                   class="form-control border-start-0"
                   placeholder="Search by name, student ID, or email...">
        </div>
    </div>
</div>

{{-- Students Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light text-uppercase small">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Faculty / Department</th>
                    <th>Level</th>
                    <th>GPA</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                {{-- Student Row --}}
                <tr>
                    <td>STU001</td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar bg-primary text-white rounded-circle">
                                A
                            </div>
                            <span class="fw-medium">Alice Johnson</span>
                        </div>
                    </td>

                    <td>
                        <div class="small">
                            <div>
                                <i class="bi bi-envelope me-1"></i>
                                alice.j@university.edu
                            </div>
                            <div>
                                <i class="bi bi-telephone me-1"></i>
                                +1234567890
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="fw-medium">Engineering</div>
                        <small class="text-muted">Computer Science</small>
                    </td>

                    <td>300L</td>

                    <td>
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            3.80
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-success-subtle text-success">
                            active
                        </span>
                    </td>

                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </td>
                </tr>

                {{-- Student Row --}}
                <tr>
                    <td>STU002</td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar bg-info text-white rounded-circle">
                                B
                            </div>
                            <span class="fw-medium">Bob Smith</span>
                        </div>
                    </td>

                    <td>
                        <div class="small">
                            <div>
                                <i class="bi bi-envelope me-1"></i>
                                bob.s@university.edu
                            </div>
                            <div>
                                <i class="bi bi-telephone me-1"></i>
                                +1234567891
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="fw-medium">Sciences</div>
                        <small class="text-muted">Physics</small>
                    </td>

                    <td>200L</td>

                    <td>
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            3.50
                        </span>
                    </td>

                    <td>
                        <span class="badge bg-success-subtle text-success">
                            active
                        </span>
                    </td>

                    <td class="text-end">
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@endsection
