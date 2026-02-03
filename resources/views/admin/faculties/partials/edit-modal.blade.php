<div class="modal fade" id="editFaculty{{ $faculty->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Faculty</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('admin.faculties.update', $faculty) }}">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Faculty Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $faculty->name) }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Faculty Code</label>
                            <input type="text"
                                   name="code"
                                   class="form-control"
                                   value="{{ old('code', $faculty->code) }}"
                                   required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dean</label>
                            <input type="text"
                                   name="dean"
                                   class="form-control"
                                   value="{{ old('dean', $faculty->dean) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Established Year</label>
                            <input type="number"
                                   name="established_year"
                                   class="form-control"
                                   value="{{ old('established_year', $faculty->established_year) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      rows="3"
                                      class="form-control">{{ old('description', $faculty->description) }}</textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Update Faculty</button>
                </div>
            </form>

        </div>
    </div>
</div>
