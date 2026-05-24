<div class="modal fade" id="viewMaterialModal{{ $material->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white ">
                <h5 class="modal-title"> {{ $material->course?->course_code ?? '' }} - {{ $material->title }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>
                    <strong>Course:</strong><br>
                    {{ $material->course?->course_code ?? '' }} -
                    {{ $material->course?->course_title ?? '' }}
                </p>

                {{-- ACADEMIC SESSION ADDED --}}
                <p>
                    <strong>Academic Session:</strong><br>
                    {{ $material->academicSession?->session_name ?? 'N/A' }}
                </p>

                         <p>
                    <strong>Semester:</strong><br>
                    {{ $material->semester?->semester_name ?? 'N/A' }}
                </p>


                <p>
                    <strong>Type:</strong>
                    {{ ucwords(str_replace('_', ' ', $material->type)) }}<br>

                    <strong>Week:</strong>
                    {{ $material->week ?? 'N/A' }}
                </p>

                <div class="mb-3">
                    <strong>Description:</strong>
                    <p class="text-muted">
                        {{ $material->description ?? 'No description provided.' }}
                    </p>
                </div>

                {{-- FILE --}}
                @if($material->file_path)

                    @if(in_array($material->file_type, ['jpg','jpeg','png','gif']))
                        <img src="{{ asset('storage/' . $material->file_path) }}"
                             class="img-fluid rounded mb-2">

                    @elseif($material->file_type === 'pdf')
                        <iframe src="{{ asset('storage/' . $material->file_path) }}"
                                width="100%" height="400px"></iframe>

                    @else
                        <a href="{{ asset('storage/' . $material->file_path) }}"
                           target="_blank"
                           class="btn btn-success btn-sm">
                            Download File
                        </a>
                    @endif

                @endif

                {{-- LINK --}}
                @if($material->external_link)
                    <div class="mt-3">
                        <a href="{{ $material->external_link }}"
                           target="_blank"
                           class="btn btn-primary btn-sm">
                            Open External Link
                        </a>
                    </div>
                @endif

                <hr>

                <small class="text-muted">
                    Uploaded: {{ $material->created_at->format('d M Y, h:i A') }}
                </small>
                <br>
                <small class="text-muted">
                    Last Updated: {{ $material->updated_at->format('d M Y, h:i A') }}
                </small>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button class="btn btn-primary px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
