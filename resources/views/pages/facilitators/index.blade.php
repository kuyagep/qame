@extends('layouts.main')

@section('title', 'Facilitator Management')
@section('content-header', 'Facilitator Management')

@section('content')
    <div class="container-fluid pt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 font-weight-bold text-dark">Facilitator Management</h5>
                <button class="btn btn-primary btn-sm" id="btn-add">
                    <i class="fas fa-plus mr-1"></i> Add New Facilitator
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="facilitators-table">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Office / Division</th>
                                <th class="text-end pe-4" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="facilitator-list">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="facilitatorModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Add Facilitator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="facilitatorForm">
                    @csrf
                    <input type="hidden" id="facilitator_id" name="id">
                    <div class="modal-body py-2">

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g. Jane Doe" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Email Address <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="e.g. jane.doe@deped.gov.ph" required>
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Position / Designation</label>
                            <input type="text" class="form-control" id="position" name="position"
                                placeholder="e.g. Education Program Supervisor">
                            <div class="invalid-feedback" id="error-position"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Office / Division</label>
                            <input type="text" class="form-control" id="office_division" name="office_division"
                                placeholder="e.g. Curriculum Implementation Division">
                            <div class="invalid-feedback" id="error-office_division"></div>
                        </div>

                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Facilitator</button>
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
            const modal = new bootstrap.Modal(document.getElementById('facilitatorModal'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            fetchFacilitators();

            function fetchFacilitators() {
                $.get("{{ route('facilitators.index') }}", function(data) {
                    let rows = '';
                    if (!data || data.length === 0) {
                        rows =
                            `<tr><td colspan="6" class="text-center text-muted py-4">No facilitators found.</td></tr>`;
                    } else {
                        $.each(data, function(index, item) {
                            rows += `
                                <tr id="row-${item.id}">
                                    <td class="align-middle">${index + 1}</td>
                                    <td class="align-middle font-weight-bold">${item.name}</td>
                                    <td class="align-middle">${item.email}</td>
                                    <td class="align-middle">${item.position || 'N/A'}</td>
                                    <td class="align-middle">${item.office_division || 'N/A'}</td>
                                    <td class="align-middle text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${item.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${item.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    $('#facilitator-list').html(rows);
                });
            }

            function clearForm() {
                $('#facilitatorForm')[0].reset();
                $('#facilitator_id').val('');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            $('#btn-add').click(function() {
                clearForm();
                $('#modalTitle').text('Add Facilitator');
                modal.show();
            });

            $(document).on('click', '.btn-edit', function() {
                clearForm();
                const id = $(this).data('id');

                $.get(`/facilitators/${id}/edit`, function(data) {
                    $('#modalTitle').text('Edit Facilitator');
                    $('#facilitator_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#position').val(data.position);
                    $('#office_division').val(data.office_division);
                    modal.show();
                }).fail(function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not fetch facilitator details.'
                    });
                });
            });

            $('#facilitatorForm').submit(function(e) {
                e.preventDefault();

                const id = $('#facilitator_id').val();
                const url = id ? `/facilitators/${id}` : "{{ route('facilitators.store') }}";
                const method = id ? 'PUT' : 'POST';

                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#btn-save').prop('disabled', true).text('Saving...');

                const formData = {
                    _token: csrfToken,
                    name: $('#name').val(),
                    email: $('#email').val(),
                    position: $('#position').val(),
                    office_division: $('#office_division').val()
                };

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        modal.hide();
                        fetchFacilitators();
                        clearForm();

                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Facilitator saved successfully!'
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
                        $('#btn-save').prop('disabled', false).text('Save Facilitator');
                    }
                });
            });

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
                            url: `/facilitators/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                $(`#row-${id}`).fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('#facilitator-list tr').length ===
                                        0) {
                                        fetchFacilitators();
                                    }
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: response.message ||
                                        'Facilitator deleted successfully.'
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to delete facilitator.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
