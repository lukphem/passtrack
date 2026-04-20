{{-- DELETE STUDENT MODAL --}}
<div class="modal fade" id="deleteStudentModal{{ $student->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}">
            @csrf
            @method('DELETE')
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠️ Delete Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>?</p>

                    <div class="alert alert-warning mt-3" role="alert">
                        <strong>Warning:</strong> This action is <u>permanent</u> and cannot be undone.
                    </div>

                    <ul class="text-danger small">
                        <li>All academic records, attendance, and performance data for this student will be permanently removed.</li>
                        <li>Any linked accounts or profiles associated with this student will be affected.</li>
                        <li>This action may impact reports or analytics that include this student.</li>
                    </ul>

                    <p class="text-muted small">If you are unsure, please consider marking the student as <strong>inactive</strong> instead.</p>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
                </div>

            </div>
        </form>
    </div>
</div>
