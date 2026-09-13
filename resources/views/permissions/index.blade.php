@extends('layouts.main')
@section('title', 'Permission Management')

@section('content-header')
    <h1>Permission Management</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- AdminLTE Card Component with Teal Accent Theme -->
        <div class="card card-outline card-teal shadow-sm">
            <div class="card-header">
                <h3 class="card-title mt-1">
                    <i class="fas fa-key mr-1"></i> Granular Action Capabilities / Permissions
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="btn-add-permission">
                        <i class="fas fa-plus-circle "></i> Add Permission
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0" id="permissions-table">
                        <thead>
                            <tr>
                                <th>Permission Key</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th style="width: 100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr id="permission-row-{{ $permission->id }}">
                                    <td class="permission-name">
                                        <code class="text-teal font-weight-bold">{{ $permission->name }}</code>
                                    </td>
                                    <td class="permission-description text-muted">
                                        {{ $permission->description ?? 'No functional baseline description provided.' }}
                                    </td>
                                    <td class="permission-date">{{ $permission->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm btn-view" data-id="{{ $permission->id }}">
                                                <i class="fas fa-eye mr-1"></i>
                                            </button>
                                            <button class="btn btn-info btn-sm btn-edit text-white"
                                                data-id="{{ $permission->id }}">
                                                <i class="fas fa-pencil-alt "></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $permission->id }}"
                                                data-url="{{ route('permissions.destroy', $permission->id) }}">
                                                <i class="fas fa-trash "></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fas fa-key fa-3x text-muted mb-3"></i><br>
                                        <span class="text-secondary font-weight-bold">No system permissions logged
                                            yet.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- READ-ONLY SHOW MODAL -->
    <div class="modal fade" id="showPermissionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-fingerprint mr-2"></i>Capability Key Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-unlock-alt text-teal fa-4x"></i>
                    </div>
                    <h4 class="mb-2"><code id="show-name" class="text-teal font-weight-bold"></code></h4>
                    <p id="show-description" class="text-muted px-3"></p>
                    <hr>
                    <table class="table table-sm table-borderless text-left mb-0">
                        <tr>
                            <th style="width: 35%">Permission ID:</th>
                            <td id="show-id" class="text-secondary small font-weight-bold"></td>
                        </tr>
                        <tr>
                            <th>Assigned Guard:</th>
                            <td><span class="badge badge-teal" id="show-guard"
                                    style="color: #fff; background-color: #20c997;"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">Close View</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MUTABLE FORM MODAL (CREATE / EDIT) -->
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="permissionForm">
                    <input type="hidden" id="permission_id" name="permission_id">
                    <div class="modal-header bg-teal text-white">
                        <h5 class="modal-title" id="modalTitle">Add Permission</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="form-errors"></div>

                        <div class="form-group">
                            <label for="name">Permission System Slug</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g., users.create, invoices.edit" required>
                            <small class="form-text text-muted">Use dot notation or hyphens to keep slugs
                                predictable.</small>
                        </div>
                        <div class="form-group">
                            <label for="description">Clearance Context / Purpose</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Explain what specific operations this capability key grants access to..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- FETCH & SHOW PERMISSION META PROFILE ---
            $(document).on('click', '.btn-view', function() {
                let id = $(this).data('id');
                $.get(`/permissions/${id}`, function(permission) {
                    $('#show-id').text(permission.id);
                    $('#show-name').text(permission.name);
                    $('#show-description').text(permission.description ||
                        'No specialized description mapped to this clearance key.');
                    $('#show-guard').text(permission.guard_name || 'web');
                    $('#showPermissionModal').modal('show');
                });
            });

            // --- TRIGGER NEW INITIALIZATION OVERLAY ---
            $('#btn-add-permission').click(function() {
                $('#permissionForm')[0].reset();
                $('#permission_id').val('');
                $('#modalTitle').text('Create Authorization Key');
                $('#form-errors').addClass('d-none').html('');
                $('#permissionModal').modal('show');
            });

            // --- TRIGGER EDIT DATA FETCHING OVERLAY ---
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $('#form-errors').addClass('d-none').html('');

                $.get(`/permissions/${id}`, function(permission) {
                    $('#permission_id').val(permission.id);
                    $('#name').val(permission.name);
                    $('#description').val(permission.description);

                    $('#modalTitle').text('Edit Capability Bounds');
                    $('#permissionModal').modal('show');
                });
            });

            // --- INTERCEPT PERSISTENCE SAVING LOOP ---
            $('#permissionForm').submit(function(e) {
                e.preventDefault();
                $('#btn-save').prop('disabled', true).text('Updating rules engine...');
                $('#form-errors').addClass('d-none').html('');

                let id = $('#permission_id').val();
                let url = id ? `/permissions/${id}` : '/permissions';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#permissionModal').modal('hide');
                        $('#btn-save').prop('disabled', false).text('Save Changes');

                        let descDisplay = response.permission.description ? response.permission
                            .description : 'No functional baseline description provided.';
                        let formattedDate = new Date().toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });

                        let rowHtml = `
                        <tr id="permission-row-${response.permission.id}">
                            <td class="permission-name"><code class="text-teal font-weight-bold">${response.permission.name}</code></td>
                            <td class="permission-description text-muted">${descDisplay}</td>
                            <td class="permission-date">${formattedDate}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm btn-view" data-id="${response.permission.id}"><i class="fas fa-eye "></i> </button>
                                    <button class="btn btn-info btn-sm btn-edit text-white" data-id="${response.permission.id}"><i class="fas fa-pencil-alt "></i> </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="${response.permission.id}" data-url="/permissions/${response.permission.id}"><i class="fas fa-trash "></i> </button>
                                </div>
                            </td>
                        </tr>`;

                        if (id) {
                            $(`#permission-row-${id}`).replaceWith(rowHtml);
                        } else {
                            $('#empty-row').remove();
                            $('#permissions-table tbody').prepend(rowHtml);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Rules Saved',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        $('#btn-save').prop('disabled', false).text('Save Changes');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorList = '<ul class="mb-0">';
                            $.each(errors, function(key, value) {
                                errorList += `<li>${value[0]}</li>`;
                            });
                            errorList += '</ul>';
                            $('#form-errors').removeClass('d-none').html(errorList);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Execution Aborted',
                                text: 'System layer rejected modification parameters.'
                            });
                        }
                    }
                });
            });

            // --- HARD DELETION REMOVAL DRIVER ---
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.data('id');
                let url = button.data('url');
                let row = $('#permission-row-' + id);

                Swal.fire({
                    title: 'Revoke Authorization Globally?',
                    text: 'Dropping this restriction gate directly affects middleware rules and role structures mapping to it.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Confirm Drop'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('#permissions-table tbody tr')
                                        .length === 0) {
                                        $('#permissions-table tbody').append(`
                                        <tr id="empty-row">
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-key fa-3x text-muted mb-3"></i><br>No system permissions logged yet.
                                            </td>
                                        </tr>`);
                                    }
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Purged',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Action Denied',
                                    text: xhr.responseJSON?.message ||
                                        'Access constraints encountered.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
