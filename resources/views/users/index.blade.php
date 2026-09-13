@extends('layouts.main')

@section('title', 'User Management')
@section('content-header', 'User Management')

@section('header-action')
    <button type="button" class="btn btn-success " id="btn-add-user">
        <i class="fas fa-plus me-1"></i> Add New User
    </button>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card border-1 border-light">
            <div class="card-header">
                {{-- <h3 class="card-title text-secondary font-weight-bold mb-0">Registered Users</h3> --}}
                <h3 class="card-title mt-1">
                    <i class="fas fa-users mr-1"></i> Registered Users
                </h3>


            </div>

            <div class="card-body">
                <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <!-- Left side: Bulk Actions -->
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <select id="bulk-action-select" class="form-control form-control-sm mr-2" style="width: 170px;"
                            disabled>
                            <option value="">Bulk Actions</option>
                            <option value="activate">Set Active</option>
                            <option value="suspend">Suspend Selected</option>
                            <option value="delete">Delete Selected</option>
                            <option value="export">Export Selected</option>
                        </select>
                        <button id="btn-apply-bulk" class="btn btn-sm btn-secondary" disabled>
                            Apply (<span id="selected-count">0</span>)
                        </button>
                    </div>

                    <!-- Right side: Search Input, Status Filter & Export All -->
                    <div class="d-flex align-items-center flex-wrap gap-2">

                        {{-- Search Input Bar (Sized to match filter elements) --}}
                        <div class="input-group input-group-sm mr-1" style="width: 240px;">
                            <input type="text" id="user-search-input" class="form-control"
                                placeholder="Search name or email...">
                            <div class="input-group-append">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-sm text-muted"></i>
                                </span>
                            </div>

                        </div>

                        {{-- Status Filter Dropdown --}}
                        <div class="input-group input-group-sm mr-1" style="width: 210px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-filter me-1"></i> Status</span>
                            </div>
                            <select id="status-filter" class="form-control form-control-sm">
                                <option value="all">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="pending">Awaiting Approval</option>
                                <option value="inactive">Suspended / Inactive</option>
                            </select>
                        </div>

                        {{-- Export All Button --}}
                        <a href="{{ route('users.export.all') }}" class="btn btn-sm btn-outline-success text-nowrap">
                            <i class="fas fa-file-excel me-1"></i> Export All
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover  align-middle mb-0" id="users-table">
                        <thead>
                            <tr>
                                <th width="40" class="text-center">
                                    <input type="checkbox" id="select-all-users">
                                </th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Assigned Roles</th>
                                <th>Status</th>
                                <th style="width: 100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr id="user-row-{{ $user->id }}" data-status="{{ strtolower($user->status) }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                                    </td>
                                    <td class="user-name"><strong>{{ $user->name }}</strong></td>
                                    <td class="user-username">{{ $user->username ?? 'N/A' }}</td>
                                    <td class="user-email">{{ $user->email }}</td>
                                    <td>
                                        @forelse($user->roles as $role)
                                            <span class="badge badge-info px-2 py-1 mr-1 text-xs">{{ $role->name }}</span>
                                        @empty
                                            <span class="badge badge-secondary px-2 py-1 text-xs">User</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        @if ($user->status === 'active')
                                            <span class="badge badge-success px-2 py-1">Active</span>
                                        @elseif($user->status === 'pending')
                                            <span class="badge badge-warning px-2 py-1 animate-pulse">Awaiting
                                                Approval</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-inline-flex align-items-center gap-1">

                                            {{-- Status Action Button --}}
                                            @if ($user->status === 'pending')
                                                <button
                                                    class="btn btn-sm btn-outline-success border-0 bg-success bg-opacity-10 text-success fw-semibold btn-toggle-status px-2 py-1 mr-1"
                                                    data-id="{{ $user->id }}" data-status="active" title="Approve User">
                                                    <i class="fas fa-user-check me-1"></i>
                                                </button>
                                            @elseif ($user->status === 'active')
                                                <button
                                                    class="btn btn-sm btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger fw-semibold btn-toggle-status px-2 py-1 mr-1"
                                                    data-id="{{ $user->id }}" data-status="suspended"
                                                    title="Suspend User">
                                                    <i class="fas fa-user-slash me-1"></i>
                                                </button>
                                            @elseif ($user->status === 'suspended' || $user->status === 'inactive')
                                                <button
                                                    class="btn btn-sm btn-outline-warning border-0 bg-warning bg-opacity-10 text-warning fw-semibold btn-toggle-status px-2 py-1 mr-1"
                                                    data-id="{{ $user->id }}" data-status="active"
                                                    title="Reactivate User">
                                                    <i class="fas fa-user-shield me-1"></i>
                                                </button>
                                            @endif

                                            {{-- Roles Button --}}
                                            <button
                                                class="btn btn-sm btn-primary border-0 bg-opacity-10 fw-semibold btn-assign-role px-2 py-1 mr-1"
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                data-roles="{{ json_encode($user->roles->pluck('name')) }}"
                                                title="Manage Roles">
                                                <i class="fas fa-user-shield me-1"></i>
                                            </button>

                                            {{-- View Button --}}
                                            <button class="btn btn-sm btn-info border-0 btn-view px-2 py-1 mr-1"
                                                data-id="{{ $user->id }}" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            {{-- Edit Button --}}
                                            <button
                                                class="btn btn-sm btn-warning border-0 text-white btn-edit px-2 py-1 mr-1"
                                                data-id="{{ $user->id }}" title="Edit User">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <button class="btn btn-sm btn-danger border-0 btn-delete px-2 py-1 mr-1"
                                                data-id="{{ $user->id }}"
                                                data-url="{{ route('users.destroy', $user) }}" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i><br>
                                        <span class="text-secondary font-weight-bold">No records found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <!-- Displaying the count breakdown -->
                        <div class="text-muted small">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </div>

                        <!-- Bootstrap 4 pagination links -->
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- NEW: READ-ONLY SHOW DETAILS MODAL        -->
    <!-- ========================================== -->
    <div class="modal fade" id="showUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-card mr-2"></i>User Profile Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <!-- Avatar block wrapper -->
                    <div class="mb-3">
                        <img id="show-avatar" src="" class="img-circle elevation-2" alt="User Image"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <h4 id="show-name" class="font-weight-bold mb-1"></h4>
                    <p id="show-username" class="text-muted mb-3"></p>
                    <hr>

                    <!-- Quick facts definition table layout -->
                    <table class="table table-sm table-borderless text-left mb-0">

                        <tr>
                            <th>Employee ID:</th>
                            <td id="show-employee-id"></td>
                        </tr>
                        <tr>
                            <th>Email Address:</th>
                            <td id="show-email"></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td id="show-phone"></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td id="show-status"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default w-100" data-dismiss="modal">Close Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ADMINLTE COMPATIBLE SHARED FORM MODAL (CREATE / EDIT) -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="userForm">
                    <input type="hidden" id="user_id" name="user_id">

                    {{-- Dynamic theme headers popular in AdminLTE panels --}}
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalTitle">Add User</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Inline Server Validation Error Alert panel matching AdminLTE callouts -->
                        <div class="alert alert-danger d-none" id="form-errors"></div>

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="johndoe" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="johndoe@company.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password <span class="text-muted small id-helper">(Leave blank if
                                    updating)</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="form-group">
                            <label for="status">System Status</label>
                            <select class="form-control custom-select" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
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

    <!-- SINGLE GLOBAL ROLE SELECTION CONTROL MODAL INTERACTION CANVAS -->
    <div class="modal fade" id="modal-role-assignment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-shield-alt mr-2"></i> Alter Security Access
                        Enclosures</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-role-assignment">
                    @method('PUT')
                    <div class="modal-body">
                        <p class="text-muted text-sm mb-3">Adjust authorization clearance boundaries for: <strong
                                id="display-user-name" class="text-dark"></strong></p>

                        <div class="form-group">
                            <label class="font-weight-bold text-secondary mb-2">Available Operational Role Profiles</label>
                            <div class="role-checkbox-container p-3 border rounded bg-light"
                                style="max-height: 250px; overflow-y: auto;">
                                @foreach ($roles as $role)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input class="custom-control-input role-checkbox" type="checkbox" name="roles[]"
                                            id="role-chk-{{ $role->id }}" value="{{ $role->name }}">
                                        <label
                                            class="custom-control-label font-weight-bold text-dark text-sm cursor-pointer"
                                            for="role-chk-{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light text-right">
                        <button type="button" class="btn btn-default font-weight-bold btn-sm"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success font-weight-bold btn-sm shadow-sm"
                            id="btn-save-roles">
                            <i class="fas fa-check mr-1"></i> Apply Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            // Automatically link header tokens to all underlying jQuery requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.btn-toggle-status', function() {
                const userId = $(this).data('id');
                const targetStatus = $(this).data('status');

                // Configure dynamic text, button colors, and icons based on target status
                let titleText = 'Confirm Action';
                let bodyText = `Are you sure you want to change this user's status to ${targetStatus}?`;
                let confirmBtnText = 'Yes, proceed!';
                let confirmBtnColor = '#3085d6';
                let iconType = 'question';

                if (targetStatus === 'suspended') {
                    titleText = 'Suspend User Account?';
                    bodyText = 'This user will immediately lose access to the system.';
                    confirmBtnText = 'Yes, suspend user';
                    confirmBtnColor = '#d33';
                    iconType = 'warning';
                } else if (targetStatus === 'active') {
                    titleText = 'Activate User Account?';
                    bodyText = 'This user will be granted system access based on their assigned role.';
                    confirmBtnText = 'Yes, activate user';
                    confirmBtnColor = '#28a745';
                    iconType = 'question';
                }

                // Trigger SweetAlert2 Confirmation Modal
                Swal.fire({
                    title: titleText,
                    text: bodyText,
                    icon: iconType,
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Show loading state while processing request
                        Swal.showLoading();

                        $.ajax({
                            url: `/users/${userId}/status`,
                            type: "PATCH",
                            data: {
                                _token: "{{ csrf_token() }}",
                                status: targetStatus
                            },
                            success: function(res) {
                                if (typeof SystemAlert !== 'undefined' && SystemAlert
                                    .toast) {
                                    SystemAlert.toast('success', res.message);
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            },
                            error: function(err) {
                                const errorMsg = err.responseJSON?.message ||
                                    'An error occurred while updating status.';

                                if (typeof SystemAlert !== 'undefined' && SystemAlert
                                    .toast) {
                                    SystemAlert.toast('error', errorMsg);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: errorMsg
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // --- NEW: TRIGGER VIEW / SHOW PROFILE MODAL VIA AJAX ---
            $(document).on('click', '.btn-view', function() {
                let userId = $(this).data('id');

                $.get(`/users/${userId}`, function(user) {
                    // Populate read-only elements
                    $('#show-id').text(user.id);
                    $('#show-name').text(user.name);
                    $('#show-username').text('@' + user.username);
                    $('#show-email').text(user.email);
                    $('#show-employee-id').text(user.employee_id || 'N/A');
                    $('#show-phone').text(user.phone || 'N/A');

                    // Dynamic AdminLTE Badge handling inside details view
                    let badgeClass = user.status === 'active' ? 'badge-success' : 'badge-secondary';
                    $('#show-status').html(
                        `<span class="badge ${badgeClass}">${user.status.toUpperCase()}</span>`);

                    // Fallback dummy profile illustration handling if avatar field value isn't loaded
                    let avatarUrl = user.avatar ? `/storage/${user.avatar}` :
                        'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg';
                    $('#show-avatar').attr('src', avatarUrl);

                    $('#showUserModal').modal('show');
                });
            });

            // --- TRIGGER CREATE MODAL ---
            $('#btn-add-user').click(function() {
                $('#userForm')[0].reset();
                $('#user_id').val('');
                $('#modalTitle').text('Add New User');
                $('.id-helper').addClass('d-none');
                $('#password').attr('required', true);
                $('#form-errors').addClass('d-none').html('');
                $('#userModal').modal('show'); // Bootstrap 4 Modal implementation
            });

            // --- TRIGGER EDIT MODAL ---
            $(document).on('click', '.btn-edit', function() {
                let userId = $(this).data('id');
                $('#form-errors').addClass('d-none').html('');

                $.get(`/users/${userId}`, function(user) {
                    $('#user_id').val(user.id);
                    $('#name').val(user.name);
                    $('#username').val(user.username);
                    $('#email').val(user.email);
                    $('#status').val(user.status);

                    $('#modalTitle').text('Edit User Data');
                    $('.id-helper').removeClass('d-none');
                    $('#password').attr('required', false).val('');
                    $('#userModal').modal('show');
                });
            });

            // --- PROCESS FORM SUBMISSION (CREATE & UPDATE) ---
            $('#userForm').submit(function(e) {
                e.preventDefault();
                $('#btn-save').prop('disabled', true).text('Processing...');
                $('#form-errors').addClass('d-none').html('');

                let id = $('#user_id').val();
                let url = id ? `/users/${id}` : '/users';
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        $('#userModal').modal('hide');
                        $('#btn-save').prop('disabled', false).text('Save Changes');

                        let user = response.user;

                        // 1. Build Status Badge
                        let badgeClass = 'badge-secondary';
                        let statusText = 'Suspended';
                        if (user.status === 'active') {
                            badgeClass = 'badge-success';
                            statusText = 'Active';
                        } else if (user.status === 'pending') {
                            badgeClass = 'badge-warning animate-pulse';
                            statusText = 'Awaiting Approval';
                        }

                        // 2. Build Roles Badges
                        let rolesHtml = '';
                        if (user.roles && user.roles.length > 0) {
                            user.roles.forEach(function(role) {
                                rolesHtml +=
                                    `<span class="badge badge-info px-2 py-1 mr-1 text-xs">${role.name}</span>`;
                            });
                        } else {
                            rolesHtml =
                                `<span class="badge badge-secondary px-2 py-1 text-xs">User</span>`;
                        }

                        // 3. Extract Role Names Array for the data-roles Attribute
                        let roleNames = user.roles ? user.roles.map(r => r.name) : [];
                        let jsonRoles = JSON.stringify(roleNames).replace(/"/g, '&quot;');
                        let deleteUrl = "{{ route('users.destroy', ':id') }}".replace(':id',
                            user.id);

                        // 4. Build Dynamic Status Action Button
                        let statusBtnHtml = '';
                        if (user.status === 'pending') {
                            statusBtnHtml = `
                    <button class="btn btn-sm btn-outline-success border-0 bg-success bg-opacity-10 text-success fw-semibold btn-toggle-status px-2 py-1 mr-1"
                        data-id="${user.id}" data-status="active" title="Approve User">
                        <i class="fas fa-user-check me-1"></i>
                    </button>`;
                        } else if (user.status === 'active') {
                            statusBtnHtml = `
                    <button class="btn btn-sm btn-outline-danger border-0 bg-danger bg-opacity-10 text-danger fw-semibold btn-toggle-status px-2 py-1 mr-1"
                        data-id="${user.id}" data-status="suspended" title="Suspend User">
                        <i class="fas fa-user-slash me-1"></i>
                    </button>`;
                        } else if (user.status === 'suspended') {
                            statusBtnHtml = `
                    <button class="btn btn-sm btn-outline-warning border-0 bg-warning bg-opacity-10 text-warning fw-semibold btn-toggle-status px-2 py-1 mr-1"
                        data-id="${user.id}" data-status="active" title="Reactivate User">
                        <i class="fas fa-user-shield me-1"></i>
                    </button>`;
                        }

                        // 5. Build Complete Row HTML
                        let rowHtml = `
                <tr id="user-row-${user.id}" data-status="${user.status}">
                    <td class="text-center align-middle">
                        <input type="checkbox" class="user-checkbox" value="${user.id}">
                    </td>
                    <td class="user-name align-middle"><strong>${user.name}</strong></td>
                    <td class="user-username align-middle">${user.username || 'N/A'}</td>
                    <td class="user-email align-middle">${user.email}</td>
                    <td class="user-roles align-middle">${rolesHtml}</td>
                    <td class="user-status align-middle">
                        <span class="badge ${badgeClass} px-2 py-1">${statusText}</span>
                    </td>
                    <td class="align-middle text-end">
                        <div class="d-inline-flex align-items-center gap-1">
                            ${statusBtnHtml}

                            <button class="btn btn-sm btn-primary border-0 bg-opacity-10 fw-semibold btn-assign-role px-2 py-1 mr-1"
                                data-id="${user.id}"
                                data-name="${user.name}"
                                data-roles="${jsonRoles}"
                                title="Manage Roles">
                                <i class="fas fa-user-shield me-1"></i>
                            </button>

                            <button class="btn btn-sm btn-info border-0 btn-view px-2 py-1 mr-1"
                                data-id="${user.id}"
                                title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button class="btn btn-sm btn-warning border-0 text-white btn-edit px-2 py-1 mr-1"
                                data-id="${user.id}"
                                title="Edit User">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <button class="btn btn-sm btn-danger border-0 btn-delete px-2 py-1 mr-1"
                                data-id="${user.id}"
                                data-url="${deleteUrl}"
                                title="Delete User">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;

                        if (id) {
                            $(`#user-row-${id}`).replaceWith(rowHtml);
                        } else {
                            $('#empty-row').remove();
                            $('#users-table tbody').prepend(rowHtml);
                        }

                        SystemAlert.toast('success', 'User saved successfully.');
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
                                text: 'Something unexpected failed.'
                            });
                        }
                    }
                });
            });

            // --- REMOVE USER ROW (DELETE) ---
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let button = $(this);
                let userId = button.data('id');
                let url = button.data('url');
                let row = $('#user-row-' + userId);

                Swal.fire({
                    title: 'Delete User?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            success: function(response) {
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('#users-table tbody tr').length ===
                                        0) {
                                        $('#users-table tbody').append(`
                                        <tr id="empty-row">
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i><br>No users found.
                                            </td>
                                        </tr>`);
                                    }
                                });
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Access denied.'
                                });
                            }
                        });
                    }
                });
            });

            let currentUserId = null;

            // Trigger Intercept Event Layer: Open Role Dialog and Pre-Check active roles
            $('.btn-assign-role').on('click', function() {
                currentUserId = $(this).data('id');
                const userName = $(this).data('name');
                const assignedRolesArray = $(this).data('roles'); // Plucked collection parsing

                // Update textual headers inside modal wrapper layout frame context
                $('#display-user-name').text(userName);

                // Reset all internal checkboxes before applying target profile array filters
                $('.role-checkbox').prop('checked', false);

                // Loop checkboxes and cross reference checked arrays matching roles
                $('.role-checkbox').each(function() {
                    if (assignedRolesArray.includes($(this).val())) {
                        $(this).prop('checked', true);
                    }
                });

                // Fire Modal Popup
                $('#modal-role-assignment').modal('show');
            });

            // Form Event Listener Submission Pipeline
            $('#form-role-assignment').submit(function(e) {
                e.preventDefault();

                if (!currentUserId) return;

                $('#btn-save-roles').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Synchronizing Framework...');

                $.ajax({
                    url: `/users/${currentUserId}/assign-roles`,
                    type: "POST", // Managed via Blade @method('PUT') parser spoofing tags safely
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        $('#modal-role-assignment').modal('hide');
                        $('#btn-save-roles').prop('disabled', false).html(
                            '<i class="fas fa-check mr-1"></i> Apply Changes');

                        // Fire dynamic notification confirmation via Toastr system layer
                        SystemAlert.toast('success', response.message);

                        // Refresh UI layout to print newly applied badge changes out
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#btn-save-roles').prop('disabled', false).html(
                            '<i class="fas fa-check mr-1"></i> Apply Changes');
                        SystemAlert.toast('error',
                            'Role matrix sync transaction failure encountered.');
                    }
                });
            });

            // Instant User Matrix Row Filtering Engine
            $('#user-search-input').on('keyup', function() {
                let value = $(this).val().toLowerCase().trim();

                // Target table rows inside the user table body
                $('#users-table tbody tr').filter(function() {
                    // Check text content inside the specific row match
                    let toggle = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(toggle);
                });

                // Optional: Display a fallback row if the entire table is hidden
                toggleNoResultsRow(value);
            });

            function toggleNoResultsRow(searchVal) {
                let visibleRows = $('#users-table tbody tr:visible').not('#no-results-row').length;

                // Remove existing fallback if any
                $('#no-results-row').remove();

                if (visibleRows === 0 && searchVal !== '') {
                    $('#users-table tbody').append(`
                <tr id="no-results-row">
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-search-minus fa-2x mb-2 d-block text-gray"></i>
                        No users found matching operational parameter "<strong>${searchVal}</strong>"
                    </td>
                </tr>
            `);
                }
            }
        });


        // --- 1. TOGGLE ALL CHECKBOXES ---
        $(document).on('change', '#select-all-users', function() {
            $('.user-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkControls();
        });

        // --- 2. INDIVIDUAL CHECKBOX CHANGE ---
        $(document).on('change', '.user-checkbox', function() {
            let total = $('.user-checkbox').length;
            let checked = $('.user-checkbox:checked').length;

            $('#select-all-users').prop('checked', total === checked);
            updateBulkControls();
        });

        // --- 3. ENABLE/DISABLE BULK CONTROLS ---
        function updateBulkControls() {
            let selectedCount = $('.user-checkbox:checked').length;
            $('#selected-count').text(selectedCount);

            if (selectedCount > 0) {
                $('#bulk-action-select, #btn-apply-bulk').prop('disabled', false).removeClass('btn-secondary').addClass(
                    'btn-default');
            } else {
                $('#bulk-action-select, #btn-apply-bulk').prop('disabled', true).removeClass('btn-default').addClass(
                    'btn-secondary');
                $('#bulk-action-select').val('');
            }
        }

        // --- 4. EXECUTE BULK ACTION ---
        $('#btn-apply-bulk').click(function() {
            let action = $('#bulk-action-select').val();
            let selectedIds = $('.user-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (!action) {
                Swal.fire('Warning', 'Please select an action to perform.', 'warning');
                return;
            }

            // EXPORT ACTION (Direct file download via Form GET)
            if (action === 'export') {
                let exportUrl = `/users/export-selected?ids=${selectedIds.join(',')}`;
                window.location.href = exportUrl;
                return;
            }

            // CONFIRMATION FOR DELETE / SUSPEND / ACTIVATE
            let actionText = action === 'delete' ? 'delete' : `change status to ${action} for`;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${actionText} ${selectedIds.length} user(s).`,
                icon: action === 'delete' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/bulk-action',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            ids: selectedIds,
                            action: action
                        },
                        success: function(response) {
                            SystemAlert.toast('success', response.message);

                            if (action === 'delete') {
                                // Remove deleted rows from DOM
                                selectedIds.forEach(id => $(`#user-row-${id}`).remove());
                            } else {
                                // Reload page or dynamically update status badges
                                location.reload();
                            }

                            $('#select-all-users').prop('checked', false);
                            updateBulkControls();
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to process bulk action.', 'error');
                        }
                    });
                }
            });
        });

        // --- DYNAMIC STATUS FILTER ---
        $(document).on('change', '#status-filter', function() {
            let selectedStatus = $(this).val();

            $('#users-table tbody tr').each(function() {
                // Skip empty placeholder row if present
                if ($(this).attr('id') === 'empty-row') return;

                let rowStatus = $(this).attr('data-status');

                if (selectedStatus === 'all' || rowStatus === selectedStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                    // Uncheck hidden checkboxes so they aren't accidentally included in bulk operations
                    $(this).find('.user-checkbox').prop('checked', false);
                }
            });

            // Reset "Select All" and update bulk action buttons
            $('#select-all-users').prop('checked', false);
            if (typeof updateBulkControls === 'function') {
                updateBulkControls();
            }
        });
    </script>
@endpush
