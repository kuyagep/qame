@extends('layouts.main')

@section('title', 'Participant Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Header Banner -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white shadow-sm border-0">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="font-weight-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="mb-0 opacity-75">Browse available trainings, take evaluations, and complete tests.</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-graduation-cap fa-4x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available & Joined Trainings -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary"> Attended Trainings</h5>
                <button class="btn btn-sm btn-outline-primary" id="btn-refresh"><i class="fas fa-sync-alt mr-1"></i>
                    Refresh</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Training Title</th>
                                <th>Date & Venue</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 320px;"></th>
                            </tr>
                        </thead>
                        <tbody id="participant-trainings-list">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Loading trainings...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            loadParticipantTrainings();

            $('#btn-refresh').click(function() {
                loadParticipantTrainings();
            });

            function loadParticipantTrainings() {
                $.get("{{ route('participant.trainings.data') }}", function(data) {
                    let rows = '';

                    if (!data || data.length === 0) {
                        rows =
                            `<tr><td colspan="6" class="text-center text-muted py-4">No active trainings available at the moment.</td></tr>`;
                    } else {
                        $.each(data, function(index, item) {
                            // Status Badge logic
                            // let statusBadge =
                            // `<span class="badge badge-info">${item.status}</span>`;
                            // if (item.status === 'completed') statusBadge =
                            //     `<span class="badge badge-success">Completed</span>`;
                            // if (item.status === 'ongoing') statusBadge =
                            //     `<span class="badge badge-warning">Ongoing</span>`;

                            let actionContent = '';

                            if (!item.is_joined) {
                                // Participant hasn't joined yet
                                actionContent = `
                        <button class="btn btn-sm btn-primary px-3 btn-join-training" data-id="${item.id}">
                            <i class="fas fa-user-plus mr-1"></i> Join Training
                        </button>
                    `;
                            } else {
                                // --- Construct Posttest Button inside loop ---
                                let posttestButton = '';
                                if (item.posttest_id) {
                                    if (item.is_posttest_open) {
                                        posttestButton = `
                                <button type="button" class="btn btn-outline-warning btn-take-assessment"
                                        data-id="${item.posttest_id}" data-type="Posttest" title="Take Posttest">
                                    <i class="fas fa-file-signature mr-1"></i> Posttest
                                </button>`;
                                    } else {
                                        posttestButton = `
                                <button type="button" class="btn btn-outline-secondary" disabled title="Posttest opens on the end date of training">
                                    <i class="fas fa-file-signature mr-1"></i> Posttest
                                </button>`;
                                    }
                                } else {
                                    posttestButton = `
                            <button type="button" class="btn btn-outline-secondary" disabled title="No Posttest Available">
                                <i class="fas fa-file-signature mr-1"></i> Posttest
                            </button>`;
                                }

                                // --- Action Buttons Markup ---
                                actionContent = `
                        <div class="btn-group btn-group-sm mb-1" role="group">
                            <!-- Pretest -->
                            ${item.pretest_id ? `
                                                <button type="button" class="btn btn-outline-info btn-take-assessment"
                                                        data-id="${item.pretest_id}" data-type="Pretest" title="Take Pretest">
                                                    <i class="fas fa-clipboard-list mr-1"></i> Pretest
                                                </button>
                                            ` : `
                                                <button type="button" class="btn btn-outline-secondary" disabled title="No Pretest Available">
                                                    <i class="fas fa-clipboard-list mr-1"></i> Pretest
                                                </button>
                                            `}

                            <!-- Posttest (Injected from dynamic variable) -->
                            ${posttestButton}
                        </div>

                        <!-- Evaluation Dropdown -->
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-sm btn-success dropdown-toggle shadow-sm" type="button" id="evalDropdown${item.id}" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-star mr-1"></i> Evaluate
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="evalDropdown${item.id}">
                                <h6 class="dropdown-header text-uppercase font-weight-bold">Evaluation Forms</h6>
                                <a class="dropdown-item py-2" href="/trainings/${item.id}/speaker-evaluation" target="_blank">
                                    <i class="fas fa-user-tie text-primary mr-2"></i> Speaker / Facilitator
                                </a>
                                <a class="dropdown-item py-2" href="/trainings/${item.id}/day-evaluation" target="_blank">
                                    <i class="fas fa-calendar-day text-success mr-2"></i> End of the Day
                                </a>
                            </div>
                        </div>
                    `;
                            }

                            rows += `
                    <tr>
                        <td class="align-middle">${index + 1}</td>
                        <td class="align-middle">
                            <strong class="text-dark">${item.title}</strong>
                        </td>
                        <td class="align-middle">
                            <small class="d-block text-muted"><i class="fas fa-calendar-alt mr-1"></i> ${item.date}</small>
                            <small class="d-block text-muted"><i class="fas fa-map-marker-alt mr-1"></i> ${item.venue}</small>
                        </td>

                        <td class="align-middle">
                            ${item.is_joined
                                ? `<span class="badge badge-success-subtle bg-success text-white px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Joined</span>`
                                : `<span class="badge badge-secondary px-2 py-1">Not Joined</span>`}
                        </td>
                        <td class="align-middle text-center">${actionContent}</td>
                    </tr>`;
                        });
                    }

                    $('#participant-trainings-list').html(rows);
                });
            }

            // Handle Join Training AJAX Button Click
            $(document).on('click', '.btn-join-training', function() {
                const trainingId = $(this).data('id');
                const $btn = $(this);

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Joining...');

                $.ajax({
                    url: `/participant/trainings/${trainingId}/join`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'info',
                            title: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadParticipantTrainings();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to join training. Please try again.'
                        });
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-user-plus mr-1"></i> Join Training');
                    }
                });
            });

            $(document).on('click', '.btn-take-assessment', function() {
                const assessmentId = $(this).data('id');
                const type = $(this).data('type');
                const $btn = $(this);
                const originalText = $btn.html();

                // Disable button and show loader
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Checking...');

                // 1. AJAX request to verify completion status
                $.ajax({
                    url: `/participant/assessments/${assessmentId}/status`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalText);

                        if (response.completed) {
                            // Participant already finished this assessment
                            Swal.fire({
                                icon: 'info',
                                title: `${type} Already Completed!`,
                                html: `You scored <strong>${response.score}/${response.max}</strong> (${response.percentage}%).<br>Multiple attempts are not allowed.`,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#3085d6'
                            });
                        } else {
                            // Not completed yet: Prompt confirmation to start
                            Swal.fire({
                                title: `Start ${type}?`,
                                text: "Ensure you are prepared before beginning the assessment.",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, Start Now',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: type === 'Pretest' ? '#17a2b8' :
                                    '#ffc107'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        `/participant/assessments/${assessmentId}/take`;
                                }
                            });
                        }
                    },
                    error: function() {
                        $btn.prop('disabled', false).html(originalText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to check assessment status. Please try again.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
