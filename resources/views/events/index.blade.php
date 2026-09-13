@extends('layouts.main')
@section('title', 'Events')
@section('content-header', 'Events')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0 font-weight-bold text-dark">Event List</h4>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createEventModal">
                <i class="fas fa-plus-circle mr-1"></i> Create Event
            </button>
        </div>

        <div id="alertContainer"></div>

        <div class="row" id="eventsContainer">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4 mb-4" id="event-card-{{ $event->id }}">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 font-weight-bold text-truncate" title="{{ $event->title }}">
                                {{ $event->title }}
                            </h5>
                            <div class="d-flex align-items-center">
                                @can('events.view_participants')
                                    <button class="btn btn-sm btn-light font-weight-bold view-participants mr-2"
                                        data-id="{{ $event->id }}">
                                        <i class="fas fa-users text-primary mr-1"></i>
                                        <span
                                            id="participant-count-{{ $event->id }}">{{ $event->participants_count ?? 0 }}</span>
                                    </button>
                                @endcan

                                @can('events.delete')
                                    <!-- Delete Button for Admin/Owner -->
                                    <button class="btn btn-sm btn-danger delete-event-btn" data-id="{{ $event->id }}"
                                        title="Delete Event">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Location -->
                            <p class="card-text mb-2 text-secondary">
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                <span>{{ $event->location }}</span>
                            </p>

                            <!-- Event Date Range -->
                            <p class="card-text mb-3 text-muted small">
                                <i class="far fa-clock text-danger mr-2"></i>
                                {{ $event->formatted_date_range }}
                            </p>

                            <!-- Join Link Copy Input -->
                            <div class="form-group mb-3 mt-auto">
                                <label class="small text-muted font-weight-bold mb-1">Join Link</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" value="{{ $event->join_url }}" readonly
                                        id="link-{{ $event->id }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary copy-btn" data-id="{{ $event->id }}"
                                            title="Copy Join Link">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Action Button -->
                        <div class="card-footer bg-light border-0 text-right">
                            @php $isJoined = $event->isJoinedBy(auth()->id()); @endphp
                            <button type="button"
                                class="btn btn-sm {{ $isJoined ? 'btn-outline-danger' : 'btn-success' }} join-btn btn-block"
                                data-id="{{ $event->id }}">
                                <i class="fas {{ $isJoined ? 'fa-user-minus' : 'fa-user-plus' }} mr-1"></i>
                                <span class="btn-text">{{ $isJoined ? 'Leave Event' : 'Join Event' }}</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5" id="no-events-msg">
                    <i class="far fa-calendar-times fa-3x mb-3 d-block text-secondary"></i>
                    <h5>No events scheduled.</h5>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus mr-2"></i>Create Event</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form id="createEventForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="small font-weight-bold">Event Title *</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                            <div class="invalid-feedback" id="error-title"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold">Start Date & Time *</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                    required>
                                <div class="invalid-feedback" id="error-start_date"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold">End Date & Time *</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                                <div class="invalid-feedback" id="error-end_date"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Location *</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                            <div class="invalid-feedback" id="error-location"></div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Capacity (Optional)</label>
                            <input type="number" name="capacity" id="capacity" class="form-control"
                                placeholder="Unlimited if blank">
                            <div class="invalid-feedback" id="error-capacity"></div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="btnSaveEvent">
                            <span class="btn-text">Save Event</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Include SweetAlert2 library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = "{{ csrf_token() }}";

            function showAlert(message, type = 'success') {
                document.getElementById("alertContainer").innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                `;
            }

            function clearFormErrors() {
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            // Copy Link Functionality
            document.addEventListener("click", function(e) {
                const copyBtn = e.target.closest(".copy-btn");
                if (!copyBtn) return;

                const eventId = copyBtn.getAttribute("data-id");
                const input = document.getElementById(`link-${eventId}`);

                if (input) {
                    input.select();
                    input.setSelectionRange(0, 99999);
                    navigator.clipboard.writeText(input.value);

                    // Toastr success notification
                    toastr.success("Join link copied to clipboard!");
                }
            });

            // Delete Event Handler using SweetAlert2
            $(document).on('click', '.delete-event-btn', function(e) {
                e.preventDefault();
                let eventId = $(this).data('id');
                let cardElement = $(`#event-card-${eventId}`);

                Swal.fire({
                    title: 'Delete Event?',
                    text: "Are you sure you want to delete this event? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/events/${eventId}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(response) {
                                // Fade out and remove event card
                                cardElement.fadeOut(400, function() {
                                    $(this).remove();

                                    // Check if no events are left
                                    if ($('#eventsContainer').children()
                                        .length === 0) {
                                        $('#eventsContainer').html(`
                                            <div class="col-12 text-center text-muted py-5" id="no-events-msg">
                                                <i class="far fa-calendar-times fa-3x mb-3 d-block text-secondary"></i>
                                                <h5>No events scheduled.</h5>
                                            </div>
                                        `);
                                    }
                                });

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message ||
                                        'The event has been deleted successfully.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message ||
                                        'Failed to delete the event. Please try again.',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Create Event Handler
            const createEventForm = document.getElementById("createEventForm");
            if (createEventForm) {
                createEventForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    clearFormErrors();

                    const submitBtn = document.getElementById("btnSaveEvent");
                    const spinner = submitBtn.querySelector(".spinner-border");
                    const btnText = submitBtn.querySelector(".btn-text");

                    submitBtn.disabled = true;
                    spinner.classList.remove("d-none");
                    btnText.textContent = "Saving...";

                    fetch("{{ route('events.store') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json"
                            },
                            body: new FormData(this)
                        })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) throw {
                                status: res.status,
                                data: data
                            };
                            return data;
                        })
                        .then(data => {
                            $('#createEventModal').modal('hide');
                            createEventForm.reset();
                            $('#no-events-msg').remove();

                            showAlert(data.message || "Event created successfully!");

                            if (data.card_html) {
                                $('#eventsContainer').prepend(data.card_html);
                            } else {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            if (error.status === 422 && error.data.errors) {
                                Object.keys(error.data.errors).forEach(key => {
                                    const input = document.getElementById(key);
                                    const errorDiv = document.getElementById(`error-${key}`);
                                    if (input) input.classList.add('is-invalid');
                                    if (errorDiv) errorDiv.textContent = error.data.errors[key][
                                        0
                                    ];
                                });
                            } else {
                                showAlert("An unexpected error occurred.", "danger");
                            }
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            spinner.classList.add("d-none");
                            btnText.textContent = "Save Event";
                        });
                });
            }
        });

        // Toggle Join/Leave Event Handler
        $(document).ready(function() {
            $(document).on('click', '.join-btn', function(e) {
                e.preventDefault();

                let $btn = $(this);
                let id = $btn.data('id');
                let $text = $btn.find('.btn-text');
                let $icon = $btn.find('i');
                let $spinner = $btn.find('.spinner-border');

                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');

                $.ajax({
                    url: `/events/${id}/join`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.isJoined) {
                            $btn.removeClass('btn-success').addClass('btn-outline-danger');
                            $icon.removeClass('fa-user-plus').addClass('fa-user-minus');
                            $text.text('Leave Event');
                        } else {
                            $btn.removeClass('btn-outline-danger').addClass('btn-success');
                            $icon.removeClass('fa-user-minus').addClass('fa-user-plus');
                            $text.text('Join Event');
                        }

                        $(`#participant-count-${id}`).text(response.total_count);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                            .message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Notice',
                                text: xhr.responseJSON.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again.'
                            });
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
