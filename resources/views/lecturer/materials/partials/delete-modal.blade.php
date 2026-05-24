<div class="modal fade" id="deleteMaterialModal{{ $material->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="text-white">Delete Material</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>
                    You are about to delete the material:
                    <strong class="text-danger">{{ $material->title }}</strong>
                </p>

                <div class="alert alert-warning small">

                    <strong>Course:</strong> {{ $material->course->course_code ?? 'N/A' }} <br>
                    <strong>Type:</strong> {{ ucwords(str_replace('_',' ', $material->type)) }} <br>
                    <strong>Week:</strong> {{ $material->week ?? 'N/A' }} <br>
                    <strong>Session:</strong> {{ $material->academicSession->session_name ?? 'N/A' }}

                </div>

                <div class="alert alert-danger small mb-0">

                    <strong>⚠ Important:</strong>
                    <ul class="mb-0 mt-2">
                        <li>This action cannot be undone</li>
                        <li>Students will no longer have access to this material</li>
                        <li>Any attached file will be permanently deleted</li>
                    </ul>

                </div>

            </div>

            <div class="modal-footer">

               <form action="{{ route('lecturer.materials.destroy', $material->id) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Yes, Delete
                    </button>
                </form>
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

            </div>

        </div>
    </div>
</div>
