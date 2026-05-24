<div class="modal fade" id="editMaterialModal{{ $material->id }}" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="{{ route('lecturer.materials.update', $material->id) }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                     <div class="row g-3">

                    {{-- COURSE --}}
                    <div class="col-md-6">
                        <label><span class="text-danger">*</span>Course</label>
                        <select name="course_id" class="form-control" required>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}"
                                    {{ $material->course_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->course_code }} - {{ $c->course_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SESSION --}}
                    <div class="col-md-6">
                        <label><span class="text-danger">*</span>Academic Session</label>
                        <select name="academic_session_id" class="form-control" required>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $material->academic_session_id == $session->id ? 'selected' : '' }}>
                                    {{ $session->session_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                        <div class="col-md-6">
                            <label><span class="text-danger">*</span>Title</label>
                            <input type="text"
                                   name="title"
                                   value="{{ $material->title }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label><span class="text-danger">*</span>Type</label>
                            <select name="type" class="form-control">
                                <option value="lecture_note" {{ $material->type == 'lecture_note' ? 'selected' : '' }}>Lecture Note</option>
                                <option value="assignment" {{ $material->type == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="slide" {{ $material->type == 'slide' ? 'selected' : '' }}>Slide</option>
                                <option value="reference" {{ $material->type == 'reference' ? 'selected' : '' }}>Reference</option>
                                <option value="lab_manual" {{ $material->type == 'lab_manual' ? 'selected' : '' }}>Lab Manual</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Week</label>
                            <input type="number"
                                   name="week"
                                   value="{{ $material->week }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Replace File</label>
                            <input type="file" name="file" class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>External Link</label>
                            <input type="url"
                                   name="external_link"
                                   value="{{ $material->external_link }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea name="description"
                                      class="form-control">{{ $material->description }}</textarea>
                        </div>

                    </div>

                    <div class="mt-3 text-end">
                        <button class="btn btn-primary">
                            Update Material
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
