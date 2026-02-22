<div class="modal fade" id="editSessionModal{{ $session->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i> Edit Academic Session
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            {{-- Form --}}
            <form method="POST" action="{{ route('admin.academic-sessions.update', $session->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-body px-4 pt-0">
                    <p class="text-muted small mb-4">
                        Update the academic session details. Changes will reflect immediately across the system.
                    </p>

                    <div class="row g-3">

                        {{-- 1. Session Name --}}
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label fw-semibold text-uppercase text-muted"
                                   style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                Session Name
                            </label>
                            <input type="text"
                                   name="session_name"
                                   class="form-control"
                                   value="{{ old('session_name', $session->session_name) }}"
                                   placeholder="e.g. 2025/2026"
                                   style="border-radius: 10px; padding: 0.6rem 1rem;"
                                   required>
                        </div>

                        {{-- 2. Status Toggle --}}
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label fw-semibold text-uppercase text-muted"
                                   style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                Session Status
                            </label>
                            <div class="form-control d-flex align-items-center justify-content-between"
                                 style="border-radius: 10px; padding: 0.6rem 1rem; background-color: #f8f9fa;">
                                <span class="small text-dark">Set as Active</span>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_active"
                                           value="1"
                                           id="activeSwitch{{ $session->id }}"
                                           {{ old('is_active', $session->is_active) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Start Date --}}
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label fw-semibold text-uppercase text-muted"
                                   style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                Start Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"
                                      style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </span>
                                <input type="date"
                                       name="start_date"
                                       class="form-control border-start-0"
                                       value="{{ old('start_date', $session->start_date->format('Y-m-d')) }}"
                                       style="border-radius: 0 10px 10px 0; padding: 0.6rem 1rem;"
                                       required>
                            </div>
                        </div>

                        {{-- 4. End Date --}}
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label fw-semibold text-uppercase text-muted"
                                   style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                End Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"
                                      style="border-radius: 10px 0 0 10px;">
                                    <i class="bi bi-calendar-check text-success"></i>
                                </span>
                                <input type="date"
                                       name="end_date"
                                       class="form-control border-start-0"
                                       value="{{ old('end_date', $session->end_date->format('Y-m-d')) }}"
                                       style="border-radius: 0 10px 10px 0; padding: 0.6rem 1rem;"
                                       required>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-top-0 pb-4 px-4 gap-2">
                    <button type="button"
                            class="btn btn-link text-muted fw-semibold text-decoration-none"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        Update Session
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
