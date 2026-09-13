@extends('layouts.main')

@section('title', 'System Announcements')
@section('content-header', 'Announcements')

@section('content')
    <div class="container-fluid">

        {{-- Dynamic JS Alert Container --}}
        <div id="alert-container"></div>

        {{-- Main AdminLTE Card --}}
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title fw-bold">
                    <i class="fas fa-bullhorn text-primary me-2"></i> System Announcements
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#createAnnouncementModal" data-bs-toggle="modal"
                        data-bs-target="#createAnnouncementModal">
                        <i class="fas fa-plus me-1"></i> New Announcement
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="announcementsTable">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 35%;">Title & Content</th>
                                <th style="width: 10%;">Style</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 15%;">Posted By</th>
                                <th style="width: 15%;">Date</th>
                                <th style="width: 10%;" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $announcement)
                                <tr id="announcement-row-{{ $announcement->id }}">
                                    <td>
                                        <strong class="d-block text-dark">{{ $announcement->title }}</strong>
                                        <small
                                            class="text-muted d-block">{{ Str::limit($announcement->content, 90) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match ($announcement->type) {
                                                'danger' => 'badge-danger bg-danger',
                                                'warning' => 'badge-warning bg-warning text-dark',
                                                'success' => 'badge-success bg-success',
                                                default => 'badge-info bg-info text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-toggle-status {{ $announcement->is_active ? 'btn-success' : 'btn-secondary' }}"
                                            data-id="{{ $announcement->id }}">
                                            <i
                                                class="fas {{ $announcement->is_active ? 'fa-check-circle' : 'fa-ban' }} me-1"></i>
                                            <span>{{ $announcement->is_active ? 'Active' : 'Disabled' }}</span>
                                        </button>
                                    </td>
                                    <td>{{ $announcement->creator->name ?? 'System Admin' }}</td>
                                    <td>
                                        <span
                                            class="d-block text-dark">{{ $announcement->created_at->format('M d, Y') }}</span>
                                        <small
                                            class="text-muted extra-small">{{ $announcement->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-announcement"
                                            data-id="{{ $announcement->id }}" title="Delete Announcement">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-bullhorn fa-2x mb-2 text-secondary d-block"></i>
                                        No system announcements found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($announcements->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="float-right ">
                        {{ $announcements->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Create Announcement Modal --}}
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <form id="createAnnouncementForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold" id="createAnnouncementModalLabel">
                            <i class="fas fa-paper-plane me-1"></i> New Announcement
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-alert-container"></div>

                        <div class="form-group mb-3">
                            <label for="title" class="fw-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="e.g. System Maintenance Scheduled" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="fw-bold">Alert Banner Style <span
                                    class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control custom-select form-select" required>
                                <option value="info" selected>Info (Blue)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="danger">Urgent / Important (Red)</option>
                                <option value="success">Success (Green)</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="fw-bold">Announcement Details <span
                                    class="text-danger">*</span></label>
                            <textarea name="content" id="summernote_content" class="form-control" required></textarea>
                        </div>

                        <div class="custom-control custom-switch mt-3">
                            <input type="checkbox" class="custom-control-input form-check-input" name="is_active"
                                id="isActive" value="1" checked>
                            <label class="custom-control-label form-check-label fw-bold" for="isActive">Publish
                                Immediately</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-default btn-secondary" data-dismiss="modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Publish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('styles')
    <!-- Summernote CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endpush
@push('scripts')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $(document).ready(function() {
                $('#summernote_content').summernote({
                    height: 250,
                    placeholder: 'Enter rich text content for the announcement...',
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],

                        // 1. Table Tool
                        ['table', ['table']],

                        // 2. Links, Pictures (Images), & Videos
                        ['insert', ['link', 'picture', 'video']],

                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    // Optional: Keep image size reasonable
                    maximumImageFileSize: 2097152 // 2MB
                });

                // Ensure Summernote HTML is synced back to textarea before AJAX submit
                $('#createAnnouncementForm').on('submit', function(e) {
                    $('#summernote_content').val($('#summernote_content').summernote('code'));
                });

                // Clear Summernote content when modal closes
                $('#createAnnouncementModal').on('hidden.bs.modal', function() {
                    $('#summernote_content').summernote('reset');
                });
            });

            // CRITICAL FOR AJAX: Sync Summernote HTML back to <textarea> before AJAX submit
            $('#createAnnouncementForm').on('submit', function(e) {
                // Ensure Summernote content updates the underlying textarea value
                $('#summernote_content').val($('#summernote_content').summernote('code'));
            });

            // Reset Summernote editor when modal is closed
            $('#createAnnouncementModal').on('hidden.bs.modal', function() {
                $('#summernote_content').summernote('reset');
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // CSRF Token Setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Helper: Show Alert
            function showAlert(message, type = 'success') {
                const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show mb-3" role="alert">
                    <i class="icon fas ${type === 'success' ? 'fa-check' : 'fa-ban'} me-1"></i> ${message}
                    <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
                $('#alert-container').html(alertHtml);
            }

            // 1. Submit New Announcement via AJAX
            $('#createAnnouncementForm').on('submit', function(e) {
                e.preventDefault();

                const $btn = $('#btnSubmit');
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Publishing...');
                $('#modal-alert-container').empty();

                $.ajax({
                    url: "{{ route('announcements.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-paper-plane me-1"></i> Publish');

                        // Close Modal
                        $('#createAnnouncementModal').modal('hide');
                        $('#createAnnouncementForm')[0].reset();

                        showAlert(response.message || 'Announcement published successfully!');

                        // Reload page or dynamically prepend row
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-paper-plane me-1"></i> Publish');

                        let errorsHtml =
                            '<div class="alert alert-danger"><ul class="mb-0 ps-3">';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorsHtml += `<li>${value[0]}</li>`;
                            });
                        } else {
                            errorsHtml +=
                                `<li>An unexpected error occurred. Please try again.</li>`;
                        }
                        errorsHtml += '</ul></div>';
                        $('#modal-alert-container').html(errorsHtml);
                    }
                });
            });

            // 2. Toggle Status via AJAX
            $(document).on('click', '.btn-toggle-status', function() {
                const $btn = $(this);
                const id = $btn.data('id');
                const toggleUrl = "{{ route('announcements.toggle', ':id') }}".replace(':id', id);

                $btn.prop('disabled', true);

                $.ajax({
                    url: toggleUrl,
                    type: "PATCH",
                    success: function(response) {
                        $btn.prop('disabled', false);

                        if (response.is_active) {
                            $btn.removeClass('btn-secondary').addClass('btn-success');
                            $btn.find('i').attr('class', 'fas fa-check-circle me-1');
                            $btn.find('span').text('Active');
                        } else {
                            $btn.removeClass('btn-success').addClass('btn-secondary');
                            $btn.find('i').attr('class', 'fas fa-ban me-1');
                            $btn.find('span').text('Disabled');
                        }

                        showAlert('Announcement status updated!');
                    },
                    error: function() {
                        $btn.prop('disabled', false);
                        showAlert('Failed to update status.', 'danger');
                    }
                });
            });

            $(document).on('click', '.btn-delete-announcement', function() {
                const $btn = $(this);
                const id = $btn.data('id');
                const deleteUrl = "{{ route('announcements.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545', // Danger red
                    cancelButtonColor: '#6c757d', // Secondary grey
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger mr-2',
                        cancelButton: 'btn btn-secondary mr-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Optional: Show loading state inside SweetAlert while deleting
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we process your request.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            success: function(response) {
                                $(`#announcement-row-${id}`).fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('#announcementsTable tbody tr')
                                        .length === 0) {
                                        location.reload();
                                    }
                                });

                                // Success Toast / Popup
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message ||
                                        'Announcement deleted successfully.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                // Error Popup
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Failed to delete announcement. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        });
                    }
                });
            });

        });
    </script>
@endpush
