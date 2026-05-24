@extends('student.layouts.app')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="mb-3">
        <h4 class="fw-bold">
            {{ $material->course?->course_code }} - {{ $material->title }}
        </h4>

        <small class="text-muted">
            Session: {{ $material->academicSession?->session_name }} |
            Semester: {{ $material->semester?->semester_name }}
        </small>
    </div>

    {{-- CONTENT --}}
    <div class="card border-0 shadow-sm p-4">

        {{-- META --}}
        <div class="mb-3">

            <span class="badge bg-primary">
                {{ strtoupper($material->type) }}
            </span>

            <span class="badge bg-secondary">
                Week {{ $material->week ?? 'N/A' }}
            </span>

        </div>

        {{-- DESCRIPTION --}}
        <p class="text-muted">
            {{ $material->description ?? 'No description provided.' }}
        </p>

        <hr>
        $progress->update([
            'status' => 'in_progress',
            'last_viewed_at' => now(),
        ]);

        {{-- FILE VIEWER --}}
        @if($material->file_path)

            @php $ext = $material->file_type; @endphp

            {{-- IMAGE --}}
            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                <img src="{{ asset('storage/'.$material->file_path) }}"
                     class="img-fluid rounded border">

            {{-- PDF --}}
            @elseif($ext === 'pdf')
                <iframe src="{{ asset('storage/'.$material->file_path) }}"
                        width="100%" height="700px" class="rounded border"></iframe>

            {{-- VIDEO --}}
            @elseif(in_array($ext, ['mp4','webm']))
                <video width="100%" controls class="rounded border">
                    <source src="{{ asset('storage/'.$material->file_path) }}">
                </video>

            {{-- DOWNLOAD --}}
            @else
                <a href="{{ asset('storage/'.$material->file_path) }}"
                   class="btn btn-success">
                    Download File
                </a>
            @endif

        @endif

        {{-- EXTERNAL --}}
        @if($material->external_link)
            <div class="mt-3">
                <a href="{{ $material->external_link }}"
                   target="_blank"
                   class="btn btn-outline-primary">
                    Open External Resource
                </a>
            </div>
        @endif

    </div>

</div>

@endsection


<script>
let startTime = Date.now();
let sent = false;

function sendProgress() {

    if (sent) return;
    sent = true;

    let timeSpent = Math.floor((Date.now() - startTime) / 1000);

    if (timeSpent < 5) return; // ignore quick opens

    navigator.sendBeacon(
        "{{ route('student.material.track', $material->id) }}",
        JSON.stringify({ time_spent: timeSpent })
    );
}

// send when leaving tab
window.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "hidden") {
        sendProgress();
    }
});

// backup
window.addEventListener("beforeunload", sendProgress);
</script>
