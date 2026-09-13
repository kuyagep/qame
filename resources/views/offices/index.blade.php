@extends('layouts.main')

@section('title', 'Offices Management')
@section('content-header', 'Offices Management')

@section('content')
    <div class="container-fluid pt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 font-weight-bold text-dark">Offices Management</h5>
                <button class="btn btn-primary btn-sm" id="btn-add">
                    <i class="fas fa-plus mr-1"></i> Add Office
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="offices-table">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Code</th>
                                <th>Office Name</th>
                                <th>Department</th>
                                <th>Created At</th>
                                <th class="text-end pe-4" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="office-list">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="officeModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Add Office</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="officeForm">
                    @csrf
                    <input type="hidden" id="office_id" name="id">
                    <div class="modal-body py-2">

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Department <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="department_id" name="department_id" required>
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-department_id"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Office Code</label>
                            <input type="text" class="form-control" id="code" name="code"
                                placeholder="e.g. ICTO">
                            <div class="invalid-feedback" id="error-code"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Office Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g. Information & Communications Technology Office" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <!-- Latitude & Longitude Fields -->
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small text-muted mb-1">Latitude</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude"
                                    placeholder="e.g. 6.7450">
                                <div class="invalid-feedback" id="error-latitude"></div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small text-muted mb-1">Longitude</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude"
                                    placeholder="e.g. 125.3520">
                                <div class="invalid-feedback" id="error-longitude"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Office</button>
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
            const modal = new bootstrap.Modal(document.getElementById('officeModal'));

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
            fetchOffices();

            function fetchOffices() {
                $.get("{{ route('offices.index') }}", function(data) {
                    let rows = '';
                    if (data.length === 0) {
                        rows =
                            `<tr><td colspan="6" class="text-center text-muted py-4">No offices found.</td></tr>`;
                    } else {
                        $.each(data, function(index, office) {
                            rows += `
                        <tr id="row-${office.id}">
                            <td class="align-middle">${index + 1}</td>
                            <td class="align-middle"><span class="badge bg-light text-dark border">${office.code ?? 'N/A'}</span></td>
                            <td class="align-middle font-weight-bold">${office.name}</td>
                            <td class="align-middle text-muted">${office.department ? office.department.name : 'N/A'}</td>
                            <td class="align-middle text-muted small">${new Date(office.created_at).toLocaleDateString()}</td>
                            <td class="align-middle text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${office.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${office.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        });
                    }
                    $('#office-list').html(rows);
                });
            }

            // Reset Form Errors & Inputs
            function clearForm() {
                $('#officeForm')[0].reset();
                $('#office_id').val('');
                $('.form-control, .form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            // Open Modal for Create
            $('#btn-add').click(function() {
                clearForm();
                $('#modalTitle').text('Add Office');
                modal.show();
            });

            // Open Modal for Edit
            $(document).on('click', '.btn-edit', function() {
                clearForm();
                const id = $(this).data('id');

                $.get(`/offices/${id}/edit`, function(data) {
                    $('#modalTitle').text('Edit Office');
                    $('#office_id').val(data.id);
                    $('#department_id').val(data.department_id);
                    $('#code').val(data.code);
                    $('#name').val(data.name);
                    $('#latitude').val(data.latitude);
                    $('#longitude').val(data.longitude);
                    modal.show();
                }).fail(function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not fetch office details.'
                    });
                });
            });

            // Handle Form Submit (Store & Update)
            $('#officeForm').submit(function(e) {
                e.preventDefault();

                const id = $('#office_id').val();
                const url = id ? `/offices/${id}` : "{{ route('offices.store') }}";
                const method = id ? 'PUT' : 'POST';

                $('.form-control, .form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btn-save').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: csrfToken,
                        department_id: $('#department_id').val(),
                        code: $('#code').val(),
                        name: $('#name').val(),
                        latitude: $('#latitude').val(),
                        longitude: $('#longitude').val()
                    },
                    success: function(response) {
                        modal.hide();
                        fetchOffices();
                        clearForm();

                        Toast.fire({
                            icon: 'success',
                            title: response.message
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
                        $('#btn-save').prop('disabled', false).text('Save Office');
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
                    confirmButtonColor: '#861408',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/offices/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                $(`#row-${id}`).fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('#office-list tr').length === 0) {
                                        fetchOffices();
                                    }
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to delete office.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
