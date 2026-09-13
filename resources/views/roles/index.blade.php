@extends('layouts.main')

@section('title', 'Role Management')
@section('content-header', 'System Roles & Permissions')

@section('header-action')
    <button type="button" class="btn btn-success btn-sm" id="btn-add-role">
        <i class="fas fa-plus-circle mr-1"></i> Add New Role
    </button>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card card-outline card-purple shadow-sm">
            <div class="card-header">
                <h3 class="card-title mt-1">
                    <i class="fas fa-user-shield mr-1"></i> System Roles & Permissions
                </h3>
                <div class="card-tools">

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0" id="roles-table">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th style="width: 320px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr id="role-row-{{ $role->id }}">
                                    <td class="role-name"><strong>{{ $role->name }}</strong></td>
                                    <td class="role-description text-muted">
                                        {{ $role->description ?? 'No description provided.' }}</td>
                                    <td class="role-date">{{ $role->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-warning btn-sm btn-permissions text-dark"
                                                data-id="{{ $role->id }}">
                                                <i class="fas fa-key mr-1"></i>
                                            </button>
                                            <button class="btn btn-primary btn-sm btn-view" data-id="{{ $role->id }}">
                                                <i class="fas fa-eye mr-1"></i>
                                            </button>
                                            <button class="btn btn-info btn-sm btn-edit text-white"
                                                data-id="{{ $role->id }}">
                                                <i class="fas fa-pencil-alt mr-1"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $role->id }}"
                                                data-url="{{ route('roles.destroy', $role->id) }}">
                                                <i class="fas fa-trash mr-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fas fa-user-shield fa-3x text-muted mb-3"></i><br>
                                        <span class="text-secondary font-weight-bold">No roles found.</span>
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
    <div class="modal fade" id="showRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-shield-alt mr-2"></i>Role Metadata Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-user-shield text-info fa-4x"></i>
                    </div>
                    <h4 id="show-name" class="font-weight-bold text-uppercase mb-2"></h4>
                    <p id="show-description" class="text-muted px-3"></p>
                    <hr>
                    <table class="table table-sm table-borderless text-left mb-0">
                        <tr>
                            <th style="width: 35%">Internal ID:</th>
                            <td id="show-id" class="text-secondary small font-weight-bold"></td>
                        </tr>
                        <tr>
                            <th>Guard Configuration:</th>
                            <td><span class="badge badge-light" id="show-guard"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MUTABLE FORM MODAL (CREATE / EDIT) -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="roleForm">
                    <input type="hidden" id="role_id" name="role_id">
                    <div class="modal-header bg-purple text-white">
                        <h5 class="modal-title" id="modalTitle">Add Role</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="form-errors"></div>

                        <div class="form-group">
                            <label for="name">Role Identifier / Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="e.g., manager, content-editor" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Functional Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Describe scope or clearance authorization..."></textarea>
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

    <!-- NEW: ASSIGN PERMISSIONS TO ROLE MODAL -->
    <div class="modal fade" id="rolePermissionsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="rolePermissionsForm">
                    <input type="hidden" id="permission_role_id">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-key mr-2"></i>Edit Role Authorization
                            Matrix</h5>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="permission-form-errors"></div>

                        <div class="mb-3 pb-2 border-bottom">
                            <span class="text-muted small uppercase font-weight-bold">Target Dynamic Role:</span>
                            <h4 id="permission-role-name" class="font-weight-bold text-purple mb-0"></h4>
                        </div>

                        <label class="mb-3 font-weight-bold text-dark">Check Capability Permissions to Attach:</label>

                        <div class="row px-2">
                            {{-- Safely retrieve permission keys dynamically from the template scope variable --}}
                            @php $allPermissions = \Spatie\Permission\Models\Permission::all(); @endphp

                            @forelse($allPermissions as $perm)
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <div
                                        class="custom-control custom-checkbox p-2 border rounded bg-light checkbox-card transition-all">
                                        <input type="checkbox" class="custom-control-input perm-checkbox"
                                            id="perm-{{ $perm->id }}" name="permissions[]"
                                            value="{{ $perm->name }}">
                                        <label
                                            class="custom-control-label font-weight-bold style-pointer text-sm text-dark w-100"
                                            for="perm-{{ $perm->id }}">
                                            <code>{{ $perm->name }}</code>
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-3 text-muted">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> No individual system permission
                                    capabilities configured yet. Create permissions first!
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning font-weight-bold text-dark"
                            id="btn-save-permissions">Save Permission Matrix</button>
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

            // ==========================================
            // NEW: ASSIGN PERMISSIONS TO ROLE LOGIC
            // ==========================================

            // 1. OPEN PERMISSIONS CHECKLIST MODAL & FETCH CURRENT DATA
            $(document).on('click', '.btn-permissions', function() {
                let roleId = $(this).data('id');
                $('#permission-form-errors').addClass('d-none').html('');
                $('.perm-checkbox').prop('checked', false); // Clean reset layout checkboxes

                $.get(`/role-permissions/${roleId}/edit`, function(data) {
                    $('#permission_role_id').val(data.role.id);
                    $('#permission-role-name').text(data.role.name);

                    // Check boxes belonging to this role currently
                    $.each(data.assigned_permissions, function(index, permName) {
                        $(`input[value="${permName}"]`).prop('checked', true);
                    });

                    $('#rolePermissionsModal').modal('show');
                });
            });

            // 2. SUBMIT AJAX SYNC FORM
            $('#rolePermissionsForm').submit(function(e) {
                e.preventDefault();
                $('#btn-save-permissions').prop('disabled', true).text('Updating system matrix...');
                let roleId = $('#permission_role_id').val();

                $.ajax({
                    url: `/role-permissions/${roleId}`,
                    type: 'PUT',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#rolePermissionsModal').modal('hide');
                        $('#btn-save-permissions').prop('disabled', false).text(
                            'Save Permission Matrix');

                        Swal.fire({
                            icon: 'success',
                            title: 'Capabilities Aligned',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        $('#btn-save-permissions').prop('disabled', false).text(
                            'Save Permission Matrix');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorList = '<ul class="mb-0">';
                            $.each(errors, function(key, value) {
                                errorList += `<li>${value[0]}</li>`;
                            });
                            errorList += '</ul>';
                            $('#permission-form-errors').removeClass('d-none').html(errorList);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Transaction Refused',
                                text: 'Backend structural error during validation processing.'
                            });
                        }
                    }
                });
            });

            // --- FETCH & SHOW METADATA PROFILE ---
            $(document).on('click', '.btn-view', function() {
                let roleId = $(this).data('id');

                $.get(`/roles/${roleId}`, function(role) {
                    $('#show-id').text(role.id);
                    $('#show-name').text(role.name);
                    $('#show-description').text(role.description ||
                        'No specialized description mapped to this record key.');
                    $('#show-guard').text(role.guard_name || 'web');
                    $('#showRoleModal').modal('show');
                });
            });

            // --- TRIGGER ADD NEW STRUCTURAL ROLE MODAL ---
            $('#btn-add-role').click(function() {
                $('#roleForm')[0].reset();
                $('#role_id').val('');
                $('#modalTitle').text('Create System Role');
                $('#form-errors').addClass('d-none').html('');
                $('#roleModal').modal('show');
            });

            // --- TRIGGER PRE-POPULATED EDIT MODAL ---
            $(document).on('click', '.btn-edit', function() {
                let roleId = $(this).data('id');
                $('#form-errors').addClass('d-none').html('');

                $.get(`/roles/${roleId}`, function(role) {
                    $('#role_id').val(role.id);
                    $('#name').val(role.name);
                    $('#description').val(role.description);

                    $('#modalTitle').text('Edit Role Boundaries');
                    $('#roleModal').modal('show');
                });
            });

            // --- PROCESS PERSISTENCE PROCESSING (CREATE & UPDATE) ---
            $('#roleForm').submit(function(e) {
                e.preventDefault();
                $('#btn-save').prop('disabled', true).text('Processing Request...');
                $('#form-errors').addClass('d-none').html('');

                let id = $('#role_id').val();
                let url = id ? `/roles/${id}` : '/roles';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#roleModal').modal('hide');
                        $('#btn-save').prop('disabled', false).text('Save Changes');

                        let descDisplay = response.role.description ? response.role
                            .description : 'No description provided.';

                        // Format current date matching PHP timestamp tracking
                        let formattedDate = new Date().toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });

                        let rowHtml = `
                        <tr id="role-row-${response.role.id}">
                            <td class="role-name"><strong>${response.role.name}</strong></td>
                            <td class="role-description text-muted">${descDisplay}</td>
                            <td class="role-date">${formattedDate}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning btn-sm btn-permissions text-dark font-weight-bold" data-id="${response.role.id}">
                                            <i class="fas fa-key mr-1"></i>
                                        </button>
                                    <button class="btn btn-primary btn-sm btn-view" data-id="${response.role.id}"><i class="fas fa-eye mr-1"></i> </button>
                                    <button class="btn btn-info btn-sm btn-edit text-white" data-id="${response.role.id}"><i class="fas fa-pencil-alt mr-1"></i> </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="${response.role.id}" data-url="/roles/${response.role.id}"><i class="fas fa-trash mr-1"></i> </button>
                                </div>
                            </td>
                        </tr>`;

                        if (id) {
                            $(`#role-row-${id}`).replaceWith(rowHtml);
                        } else {
                            $('#empty-row').remove();
                            $('#roles-table tbody').prepend(rowHtml);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
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
                                title: 'Error!',
                                text: 'Internal database communication breakdown.'
                            });
                        }
                    }
                });
            });

            // --- TEARDOWN REMOVA_ ENGINE (DELETE) ---
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let button = $(this);
                let roleId = button.data('id');
                let url = button.data('url');
                let row = $('#role-row-' + roleId);

                Swal.fire({
                    title: 'Drop System Role?',
                    text: 'This safety clearance mapping will completely break dependencies associated to assigned members.',
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
                                    if ($('#roles-table tbody tr').length ===
                                        0) {
                                        $('#roles-table tbody').append(`
                                        <tr id="empty-row">
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-user-shield fa-3x text-muted mb-3"></i><br>No roles found.
                                            </td>
                                        </tr>`);
                                    }
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Purged!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Action Aborted',
                                    text: xhr.responseJSON?.message ||
                                        'Access Denied.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    <style>
        .style-pointer {
            cursor: pointer;
        }

        .transition-all {
            transition: all 0.15s ease-in-out;
        }

        .checkbox-card:hover {
            border-color: #ffc107 !important;
            background-color: #fff !important;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }
    </style>
@endpush
