<!-- DELETE COURSE MODAL -->
@foreach ($courses as $course)


<div class="modal fade" id="deleteCourse{{ $course->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <!-- HEADER -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Deletion
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <p class="fw-bold mb-2">
                    Are you sure you want to delete this course?
                </p>

                <p class="text-muted small mb-0">
                    Deleting
                    <strong>{{ $course->course_code }} - {{ $course->course_title }}</strong> will:
                </p>

                <ul class="text-muted small mt-2">
                    <li>Remove the course from the system (soft delete)</li>
                    <li>Detach it from all associated programmes</li>
                    <li>Make it unavailable for lecturer assignment</li>
                    <li>Exclude it from student course registration</li>
                    <li>Affect reports and academic tracking linked to this course</li>
                </ul>

                <p class="text-warning small mt-2 mb-0">
                    You can restore this course later from the recycle bin (if enabled).
                </p>

                <p class="text-danger small mt-1 mb-0">
                    Proceed only if you are sure.
                </p>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger">
                        Yes, Delete Course
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endforeach
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if($errors->has('delete_error') && session('delete_course_id'))
        new bootstrap.Modal(
            document.getElementById('deleteCourse{{ session('delete_course_id') }}')
        ).show();
    @endif

});
</script>
