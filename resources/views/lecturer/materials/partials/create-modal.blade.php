<div class="modal fade" id="materialModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">+ Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form action="{{ route('lecturer.materials.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div class="row g-3">
                    {{-- COURSE --}}
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">*</span>Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Select Course --</option>

                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->course_code }} - {{ $c->course_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SESSION (AUTO ASSIGNED BUT STILL VISIBLE FOR DEBUG/CONTROL) --}}
                    <div class="col-md-6">
                        <label class="form-label"><span class="text-danger">*</span>Academic Session</label>

                        <select name="academic_session_id" class="form-select" required>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ $session->is_active ? 'selected' : '' }}>
                                    {{ $session->session_name }}
                                    @if($session->is_active)
                                        (Current)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>



                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span>Title</label>
                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   placeholder="Material Title"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><span class="text-danger">*</span>Type</label>
                            <select name="type" class="form-select" required>
                                <option value="lecture_note">Lecture Note</option>
                                <option value="assignment">Assignment</option>
                                <option value="slide">Slide</option>
                                <option value="reference">Reference</option>
                                <option value="lab_manual">Lab Manual</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                                <label class="form-label">Week</label>
                            <input type="number"
                                   name="week"
                                   class="form-control"
                                   placeholder="Week">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">File</label>
                            <input type="file"
                                   name="file"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">External Link</label>
                            <input type="url"
                                   name="external_link"
                                   class="form-control"
                                   placeholder="External Link (optional)">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      placeholder="Description (optional)"></textarea>
                        </div>

                    </div>

                    <div class="mt-3 text-end">
                        <button class="btn btn-primary">
                            Save Material
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
