<!-- Add Faculty Modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-building-fill-add"></i> Add Faculty
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>


            <form method="POST" action="{{ route('admin.faculties.store') }}">
                @csrf

                <div class="modal-body">

                    <div class="mb-3 w-100">
                        <label class="form-label">Faculty Name</label>
                        <input type="text" name="faculty_name" class="form-control w-100" required>
                    </div>

                    <div class="mb-3 w-100">
                        <label class="form-label">Faculty Code</label>
                        <input type="text" name="faculty_code" class="form-control w-100" required>
                    </div>

                    <div class="mb-3 w-100">
                        <label class="form-label">Dean</label>
                        <input type="text" name="dean" class="form-control w-100">
                    </div>

                    <div class="mb-3 w-100">
                        <label class="form-label">Established Year</label>
                        <input type="number" name="established_year" class="form-control w-100">
                    </div>

                    <div class="mb-3 w-100">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control w-100"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save Faculty</button>
                </div>
            </form>

        </div>
    </div>
</div>
