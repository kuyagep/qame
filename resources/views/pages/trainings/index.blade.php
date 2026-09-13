@extends('layouts.main')

@section('title', 'Training Management')
@section('content-header', 'Training Management')

@section('content')
    <div class="container-fluid pt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 font-weight-bold text-dark">Training Management</h5>
                <button class="btn btn-primary btn-sm" id="btn-add">
                    <i class="fas fa-plus mr-1"></i> Add New Training
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="trainings-table">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Title</th>
                                <th>Schedule</th>
                                <th>Venue</th>
                                <th>Accommodation</th>
                                <th>Status</th>
                                <th class="text-end pe-4" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="training-list">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="trainingModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Add Training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="trainingForm">
                    @csrf
                    <input type="hidden" id="training_id" name="id">
                    <div class="modal-body py-2">

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Training Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="e.g. Division Capability Building Workshop" required>
                            <div class="invalid-feedback" id="error-title"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Provide overview or objectives of the training..."></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted mb-1">Start Date / Schedule Text</label>
                                <input type="text" class="form-control" id="date" name="date"
                                    placeholder="e.g. October 12, 2026 or Q4 2026">
                                <div class="invalid-feedback" id="error-date"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small text-muted mb-1">Number of Days</label>
                                <input type="number" class="form-control" id="number_of_days" name="number_of_days"
                                    min="1" placeholder="e.g. 3">
                                <div class="invalid-feedback" id="error-number_of_days"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small text-muted mb-1">End of Training</label>
                                <input type="date" class="form-control" id="end_of_training" name="end_of_training">
                                <div class="invalid-feedback" id="error-end_of_training"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted mb-1">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue"
                                    placeholder="e.g. Division Conference Hall">
                                <div class="invalid-feedback" id="error-venue"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted mb-1">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                </select>
                                <div class="invalid-feedback" id="error-status"></div>
                            </div>
                        </div>

                        <div class="form-group form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="with_accommodation"
                                name="with_accommodation" value="1">
                            <label class="form-check-label font-weight-normal small text-dark" for="with_accommodation">
                                Inclusive of Accommodation
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Training</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const modal = new bootstrap.Modal(document.getElementById('trainingModal'));

            // SweetAlert2 Toast Configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Fetch and render list
            fetchTrainings();

            function fetchTrainings() {
                $.get("{{ route('trainings.index') }}", function(data) {
                    let rows = '';
                    if (!data || data.length === 0) {
                        rows =
                            `<tr><td colspan="7" class="text-center text-muted py-4">No trainings found.</td></tr>`;
                    } else {
                        $.each(data, function(index, training) {
                            const accommodationBadge = training.with_accommodation ?
                                `<span class="badge badge-success">Yes</span>` :
                                `<span class="badge badge-secondary">No</span>`;

                            let statusBadge =
                                `<span class="badge badge-info">${training.status}</span>`;
                            if (training.status === 'completed') statusBadge =
                                `<span class="badge badge-success">Completed</span>`;
                            if (training.status === 'draft') statusBadge =
                                `<span class="badge badge-secondary">Draft</span>`;
                            if (training.status === 'ongoing') statusBadge =
                                `<span class="badge badge-warning">Ongoing</span>`;

                            rows += `
                    <tr id="row-${training.id}">
                        <td class="align-middle">${index + 1}</td>
                        <td class="align-middle">
                            <a href="/trainings/${training.id}/sessions" class="font-weight-bold text-primary">${training.title}</a>
                        </td>
                        <td class="align-middle">${training.date || 'N/A'}</td>
                        <td class="align-middle">${training.venue || 'N/A'}</td>
                        <td class="align-middle">${accommodationBadge}</td>
                        <td class="align-middle">${statusBadge}</td>
                        <td class="align-middle text-end pe-4">
                            <!-- Add Session / Manage Sessions Button -->
                            <a href="/trainings/${training.id}/sessions" class="btn btn-sm btn-outline-info" title="Manage Sessions & Topics">
                                <i class="fas fa-list-alt mr-1"></i> Sessions
                            </a>
                            <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${training.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${training.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                        });
                    }
                    $('#training-list').html(rows);
                });
            }

            // Reset Form Errors & Inputs
            function clearForm() {
                $('#trainingForm')[0].reset();
                $('#training_id').val('');
                $('#with_accommodation').prop('checked', false);
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            // Open Modal for Create
            $('#btn-add').click(function() {
                clearForm();
                $('#modalTitle').text('Add Training');
                modal.show();
            });

            // Open Modal for Edit
            $(document).on('click', '.btn-edit', function() {
                clearForm();
                const id = $(this).data('id');

                $.get(`/trainings/${id}/edit`, function(data) {
                    $('#modalTitle').text('Edit Training');
                    $('#training_id').val(data.id);
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#date').val(data.date);
                    $('#number_of_days').val(data.number_of_days);
                    $('#end_of_training').val(data.end_of_training);
                    $('#venue').val(data.venue);
                    $('#status').val(data.status);
                    $('#with_accommodation').prop('checked', Boolean(data.with_accommodation));
                    modal.show();
                }).fail(function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not fetch training details.'
                    });
                });
            });

            // Handle Form Submit (Store & Update)
            $('#trainingForm').submit(function(e) {
                e.preventDefault();

                const id = $('#training_id').val();
                const url = id ? `/trainings/${id}` : "{{ route('trainings.store') }}";
                const method = id ? 'PUT' : 'POST';

                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btn-save').prop('disabled', true).text('Saving...');

                const formData = {
                    _token: csrfToken,
                    title: $('#title').val(),
                    description: $('#description').val(),
                    date: $('#date').val(),
                    number_of_days: $('#number_of_days').val(),
                    end_of_training: $('#end_of_training').val(),
                    venue: $('#venue').val(),
                    status: $('#status').val(),
                    with_accommodation: $('#with_accommodation').is(':checked') ? 1 : 0
                };

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        modal.hide();
                        fetchTrainings();
                        clearForm();

                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Training saved successfully!'
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $(`#${field}`).addClass('is-invalid');
                                $(`#error-${field}`).text(messages[0]);
                            });
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'An unexpected error occurred.'
                            });
                        }
                    },
                    complete: function() {
                        $('#btn-save').prop('disabled', false).text('Save Training');
                    }
                });
            });

            // Handle Delete with SweetAlert2 Confirmation
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2F4F4F',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/trainings/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                $(`#row-${id}`).fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('#training-list tr').length === 0) {
                                        fetchTrainings();
                                    }
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: response.message ||
                                        'Training deleted successfully.'
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to delete training.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
