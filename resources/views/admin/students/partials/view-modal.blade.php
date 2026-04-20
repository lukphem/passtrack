{{-- VIEW STUDENT MODAL --}}
<div class="modal fade" id="viewStudentModal{{ $student->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content printable-modal">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5>Student Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                {{-- PERSONAL INFO --}}
                <h6 class="fw-bold mb-3">Personal Information</h6>
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        @if(!empty($student->student?->profile_photo))
                        <img src="{{ asset('storage/'.$student->student->profile_photo) }}"
                             alt="Profile Photo"
                             class="img-thumbnail"
                             style="max-width:150px; max-height:200px;">
                        @else
                        <img src="https://via.placeholder.com/150x200?text=No+Photo"
                             class="img-thumbnail">
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th>First Name:</th>
                                <td>{{ $student->first_name ?? '-' }}</td>
                                <th>Middle Name:</th>
                                <td>{{ $student->middle_name ?? '-' }}</td>
                                <th>Last Name:</th>
                                <td>{{ $student->last_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>{{ ucfirst($student->student?->gender ?? '-') }}</td>
                                <th>Date of Birth:</th>
                                <td>{{ $student->student?->date_of_birth ?? '-' }}</td>
                                <th>Nationality:</th>
                                <td>{{ $student->student?->nationality ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>State / Province:</th>
                                <td>{{ $student->student?->state_of_origin ?? '-' }}</td>
                                <th>LGA / City:</th>
                                <td>{{ $student->student?->lga_of_origin ?? '-' }}</td>
                                <th>Email:</th>
                                <td>{{ $student->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $student->student?->phone ?? '-' }}</td>
                                <th>Address:</th>
                                <td colspan="3">{{ $student->student?->address ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- ACADEMIC INFO --}}
                <h6 class="fw-bold mt-4 mb-3">Academic Information</h6>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th>Programme:</th>
                        <td>{{ $student->student?->programme->programme_name ?? '-' }}</td>
                        <th>Mode of Admission:</th>
                        <td>{{ $student->student?->mode_of_admission ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Entry Level:</th>
                        <td>{{ $student->student?->entry_level ?? '-' }}</td>
                        <th>Current Level:</th>
                        <td>{{ $student->student?->level ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Admission Session:</th>
                        <td>{{ $student->student?->admission_session ?? '-' }}</td>
                        <th>Matric No:</th>
                        <td>{{ $student->student?->matric_no ?? '-' }}</td>
                    </tr>
                </table>

                {{-- PREVIOUS EDUCATION --}}
                <h6 class="fw-bold mt-4 mb-3">Previous Education Record</h6>
                <table class="table table-bordered table-sm table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>School / Institution</th>
                            <th>Qualification</th>
                            <th>Year Completed</th>
                            <th>Grade / CGPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->previousEducations ?? [] as $education)
                        <tr>
                            <td>{{ $education->institution_name ?? '-' }}</td>
                            <td>{{ $education->qualification ?? '-' }}</td>
                            <td>{{ $education->year_completed ?? '-' }}</td>
                            <td>{{ $education->grade ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No previous education records</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- O-LEVEL / SECONDARY RESULTS --}}
                <h6 class="fw-bold mt-4 mb-3">O-Level / Secondary School Results</h6>
                <table class="table table-bordered table-sm table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Exam Type</th>
                            <th>Exam Number</th>
                            <th>Year</th>
                            <th>Subjects & Grades</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($student->olevelResults ?? [] as $result)
                        <tr>
                            <td>{{ $result->exam_type ?? '-' }}</td>
                            <td>{{ $result->exam_number ?? '-' }}</td>
                            <td>{{ $result->exam_year ?? '-' }}</td>
                            <td>{{ $result->subjects_grades ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No O-Level results</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- NEXT OF KIN --}}
                <h6 class="fw-bold mt-4 mb-3">Next of Kin Details</h6>
                <table class="table table-bordered table-sm table-striped">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $student->next_of_kin_name ?? '-' }}</td>
                        <th>Relationship:</th>
                        <td>{{ $student->next_of_kin_relationship ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $student->next_of_kin_phone ?? '-' }}</td>
                        <th>Address:</th>
                        <td>{{ $student->next_of_kin_address ?? '-' }}</td>
                    </tr>
                </table>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-secondary" onclick="printModal('viewStudentModal{{ $student->id }}')">Print</button>
            </div>

        </div>
    </div>
</div>

{{-- PRINT SCRIPT --}}
<script>
function printModal(modalId) {
    var modalContent = document.getElementById(modalId).querySelector('.modal-content').innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = modalContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // reload page to restore modals & JS
}
</script>

{{-- PRINT STYLES --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .printable-modal, .printable-modal * {
        visibility: visible;
    }
    .printable-modal {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 12pt;
    }
    th, td {
        border: 1px solid #000 !important;
        padding: 5px !important;
    }
    .modal-footer, .btn-close {
        display: none !important;
    }
}
</style>
