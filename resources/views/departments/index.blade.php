@extends('layouts.main')

@section('title', 'Departments Management')
@section('content-header', 'Departments Management')

@section('content')
    <div class="container-fluid pt-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 font-weight-bold text-dark">Departments Management</h5>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#departmentModal">
                    <i class="fas fa-plus mr-1"></i> Add Department
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="departments-table">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 80px;">#</th>
                                <th>Department Name</th>
                                <th>Created At</th>
                                <th class="text-end pe-4" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="department-list">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="departmentModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="modalTitle">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="departmentForm">
                    @csrf
                    <input type="hidden" id="department_id" name="id">
                    <div class="modal-body py-2">
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Department Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g. Human Resources" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Department</button>
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
            const modal = new bootstrap.Modal(document.getElementById('departmentModal'));

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
            fetchDepartments();

            function fetchDepartments() {
                $.get("{{ route('departments.index') }}", function(data) {
                    let rows = '';
                    if (data.length === 0) {
                        rows =
                            `<tr><td colspan="4" class="text-center text-muted py-4">No departments found.</td></tr>`;
                    } else {
                        $.each(data, function(index, dept) {
                            rows += `
                        <tr id="row-${dept.id}">
                            <td class="align-middle">${index + 1}</td>
                            <td class="align-middle font-weight-bold">${dept.name}</td>
                            <td class="align-middle text-muted small">${new Date(dept.created_at).toLocaleDateString()}</td>
                            <td class="align-middle text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${dept.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${dept.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        });
                    }
                    $('#department-list').html(rows);
                });
            }

            // Reset Form Errors
            function clearForm() {
                $('#departmentForm')[0].reset();
                $('#department_id').val('');
                $('#name').removeClass('is-invalid');
                $('#error-name').text('');
            }

            // Open Modal for Create
            $('#btn-add').click(function() {
                clearForm();
                $('#modalTitle').text('Add Department');
                modal.show();
            });

            // Open Modal for Edit
            $(document).on('click', '.btn-edit', function() {
                clearForm();
                const id = $(this).data('id');

                $.get(`/departments/${id}/edit`, function(data) {
                    $('#modalTitle').text('Edit Department');
                    $('#department_id').val(data.id);
                    $('#name').val(data.name);
                    modal.show();
                }).fail(function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not fetch department details.'
                    });
                });
            });

            // Handle Form Submit (Store & Update)
            $('#departmentForm').submit(function(e) {
                e.preventDefault();

                const id = $('#department_id').val();
                const url = id ? `/departments/${id}` : "{{ route('departments.store') }}";
                const method = id ? 'PUT' : 'POST';

                $('#btn-save').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: csrfToken,
                        name: $('#name').val()
                    },
                    success: function(response) {
                        modal.hide();
                        fetchDepartments();
                        clearForm();

                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $('#name').addClass('is-invalid');
                                $('#error-name').text(errors.name[0]);
                            }
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'An unexpected error occurred.'
                            });
                        }
                    },
                    complete: function() {
                        $('#btn-save').prop('disabled', false).text('Save Department');
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
                    confirmButtonColor: '#861408', // Matches primary brand color
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/departments/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                $(`#row-${id}`).fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('#department-list tr').length === 0) {
                                        fetchDepartments();
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
                                    title: 'Failed to delete department.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
