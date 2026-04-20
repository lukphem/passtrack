<!-- Import Students Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="importStudentsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- wider for table -->
    <form method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data">
      @csrf

      <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header">
          <h5 class="modal-title" id="importStudentsLabel">Import Students</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- Download Template -->
          <div class="mb-3">
            <a href="{{ route('admin.students.template') }}" class="btn btn-outline-success btn-sm">
              ⬇ Download Excel Template
            </a>
          </div>

          <!-- File Upload -->
          <div class="mb-2">
            <input type="file" name="file" class="form-control" required>
            @error('file')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>

          <small class="text-muted d-block mb-3">
            Upload only <strong>.xlsx</strong> or <strong>.csv</strong> files using the template provided.
          </small>

          <!-- IMPORT ERRORS (ONLY ONE BLOCK - FIXED) -->
          @if(session('import_errors'))
          <div class="alert alert-danger">
            <strong>Import Errors:</strong>

            <div class="table-responsive mt-2">
              <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th>Row</th>
                    <th>Column</th>
                    <th>Invalid Value</th>
                    <th>Error</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(session('import_errors') as $error)
                    <tr>
                      <td>{{ $error['row'] }}</td>
                      <td>{{ $error['column'] }}</td>
                      <td>{{ $error['value'] ?? '-' }}</td>
                      <td class="text-danger">{{ $error['message'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

          </div>
          @endif

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            Upload Students
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
        </div>

      </div>
    </form>
  </div>
</div>


@if(session('import_errors'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('bulkUploadModal'));
        modal.show();
    });
</script>
@endif
