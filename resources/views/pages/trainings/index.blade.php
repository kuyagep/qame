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
                                <th class="text-right pr-4" style="width: 280px;">Actions</th>
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

    <!-- Training Form Modal -->
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

                        <div class="form-group mb-3">
                            <label class="form-label small text-muted mb-1">Training Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="e.g. Division Capability Building Workshop" required>
                            <div class="invalid-feedback" id="error-title"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label small text-muted mb-1">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Provide overview or objectives of the training..."></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label small text-muted mb-1">Start Date / Schedule Text</label>
                                <input type="text" class="form-control" id="date" name="date"
                                    placeholder="e.g. October 12, 2026 or Q4 2026">
                                <div class="invalid-feedback" id="error-date"></div>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label small text-muted mb-1">Number of Days</label>
                                <input type="number" class="form-control" id="number_of_days" name="number_of_days"
                                    min="1" placeholder="e.g. 3">
                                <div class="invalid-feedback" id="error-number_of_days"></div>
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label class="form-label small text-muted mb-1">End of Training</label>
                                <input type="date" class="form-control" id="end_of_training" name="end_of_training">
                                <div class="invalid-feedback" id="error-end_of_training"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label small text-muted mb-1">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue"
                                    placeholder="e.g. Division Conference Hall">
                                <div class="invalid-feedback" id="error-venue"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
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

    <!-- Assessment & Question Config Modal -->
    <div class="modal fade" id="assessmentModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold">Configure Assessment & Questions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-2">

                    <!-- Header Form for Pretest/Posttest Config -->
                    <form id="testConfigForm">
                        @csrf
                        <input type="hidden" id="assessment_training_id" name="training_id">
                        <input type="hidden" id="assessment_id" name="assessment_id">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small text-muted mb-1">Assessment Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="test_type" name="type" required>
                                    <option value="pretest">Pre-Test</option>
                                    <option value="posttest">Post-Test</option>
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label small text-muted mb-1">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="test_title" name="title"
                                    placeholder="e.g. Pre-Training Evaluation" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small text-muted mb-1">Passing Score (%)</label>
                                <input type="number" class="form-control" id="test_passing_score" name="passing_score"
                                    min="0" max="100" value="75" required>
                            </div>
                        </div>
                        <div class="text-right mb-3">
                            <button type="submit" class="btn btn-sm btn-primary" id="btn-save-assessment">
                                <i class="fas fa-save mr-1"></i> Save Config & Unlock Questions
                            </button>
                        </div>
                    </form>

                    <hr>

                    <!-- Question Builder Section -->
                    <div id="question-builder-section" style="display: none;">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-question-circle mr-1"></i> Add
                            Question</h6>

                        <form id="questionForm">
                            <div class="row">
                                <div class="col-md-8 mb-2">
                                    <label class="form-label small text-muted mb-1">Question Text <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm" id="question_text" rows="2"
                                        placeholder="Write question here..." required></textarea>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small text-muted mb-1">Question Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="q_type" name="type" required>
                                        <option value="multiple_choice">Multiple Choice</option>
                                        <option value="true_false">True / False</option>
                                        <option value="open_text">Open Text (Identification)</option>
                                    </select>

                                    <label class="form-label small text-muted mb-1 mt-2">Points</label>
                                    <input type="number" class="form-control form-control-sm" id="q_points"
                                        min="1" value="1">
                                </div>
                            </div>

                            <!-- Dynamic Inputs -->
                            <div id="type-specific-inputs" class="mb-3">

                                <!-- Multiple Choice Inputs -->
                                <div id="mcq-container">
                                    <div class="row mb-2">
                                        <div class="col-md-6 mb-1">
                                            <input type="text" class="form-control form-control-sm option-input"
                                                placeholder="Option A">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <input type="text" class="form-control form-control-sm option-input"
                                                placeholder="Option B">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <input type="text" class="form-control form-control-sm option-input"
                                                placeholder="Option C">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <input type="text" class="form-control form-control-sm option-input"
                                                placeholder="Option D">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label small text-muted mb-1">Correct Choice</label>
                                        <select class="form-control form-control-sm" id="mcq_correct_answer">
                                            <option value="">Select Correct Choice...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- True / False Inputs -->
                                <div id="tf-container" style="display: none;">
                                    <label class="form-label small text-muted mb-1">Correct Answer</label>
                                    <select class="form-control form-control-sm" id="tf_correct_answer">
                                        <option value="True">True</option>
                                        <option value="False">False</option>
                                    </select>
                                </div>

                                <!-- Open Text Inputs -->
                                <div id="text-container" style="display: none;">
                                    <label class="form-label small text-muted mb-1">Expected Correct Answer
                                        (Optional)</label>
                                    <input type="text" class="form-control form-control-sm" id="open_correct_answer"
                                        placeholder="e.g. DepEd Memorandum No. 12">
                                </div>
                            </div>

                            <div class="text-right mb-3">
                                <button type="submit" class="btn btn-sm btn-success" id="btn-add-question">
                                    <i class="fas fa-plus mr-1"></i> Add Question
                                </button>
                            </div>
                        </form>

                        <!-- Added Questions Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Type</th>
                                        <th>Question</th>
                                        <th>Answer / Key</th>
                                        <th style="width: 50px;">Pts</th>
                                        <th style="width: 50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="question-list">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No questions added yet.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // SweetAlert2 Toast Setup
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

            // Initial Load
            fetchTrainings();

            // Fetch Training List
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
                                <td class="align-middle text-right pr-4">
                                    <a href="/trainings/${training.id}/sessions" class="btn btn-sm btn-outline-info" title="Manage Sessions & Topics">
                                        <i class="fas fa-list-alt"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-info mr-1 btn-manage-test" data-training-id="${training.id}" data-type="pretest" title="Pretest">
                                        <i class="fas fa-clipboard-list"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning mr-1 btn-manage-test" data-training-id="${training.id}" data-type="posttest" title="Posttest">
                                        <i class="fas fa-file-signature"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${training.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${training.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    }
                    $('#training-list').html(rows);
                });
            }

            // Fetch Assessment Questions Function
            function fetchQuestions(assessmentId) {
                $.get(`/assessments/${assessmentId}/questions`, function(questions) {
                    let rows = '';
                    if (!questions || questions.length === 0) {
                        rows =
                            '<tr><td colspan="6" class="text-center text-muted">No questions added yet.</td></tr>';
                    } else {
                        $.each(questions, function(index, q) {
                            rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td><span class="badge badge-light">${q.type}</span></td>
                                <td>${q.question_text}</td>
                                <td>${q.correct_answer || 'N/A'}</td>
                                <td>${q.points}</td>
                                <td>
                                    <button class="btn btn-xs btn-outline-danger btn-delete-question" data-id="${q.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });
                    }
                    $('#question-list').html(rows);
                });
            }

            // Clear Training Form
            function clearForm() {
                $('#trainingForm')[0].reset();
                $('#training_id').val('');
                $('#with_accommodation').prop('checked', false);
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            // Open Add Training Modal
            $('#btn-add').click(function() {
                clearForm();
                $('#modalTitle').text('Add Training');
                $('#trainingModal').modal('show');
            });

            // Edit Training Modal
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
                    $('#trainingModal').modal('show');
                }).fail(function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Could not fetch training details.'
                    });
                });
            });

            // Save Training AJAX
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
                        $('#trainingModal').modal('hide');
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

            // Open Assessment Modal from List
            $(document).on('click', '.btn-manage-test', function() {
                const trainingId = $(this).data('training-id');
                const testType = $(this).data('type');

                $('#testConfigForm')[0].reset();
                $('#questionForm')[0].reset();
                $('#assessment_training_id').val(trainingId);
                $('#test_type').val(testType);
                $('#assessment_id').val('');
                $('#question-builder-section').hide();
                $('#question-list').html(
                    '<tr><td colspan="6" class="text-center text-muted">No questions added yet.</td></tr>'
                    );

                $('#assessmentModal').modal('show');
            });

            // Toggle Input Panels by Question Type
            $('#q_type').change(function() {
                const type = $(this).val();
                $('#mcq-container, #tf-container, #text-container').hide();

                if (type === 'multiple_choice') {
                    $('#mcq-container').show();
                } else if (type === 'true_false') {
                    $('#tf-container').show();
                } else if (type === 'open_text') {
                    $('#text-container').show();
                }
            });

            // Dynamic Option Dropdown Populate for MCQ
            $(document).on('input', '.option-input', function() {
                let select = $('#mcq_correct_answer');
                select.empty().append('<option value="">Select Correct Choice...</option>');

                $('.option-input').each(function() {
                    let val = $(this).val().trim();
                    if (val !== '') {
                        select.append(`<option value="${val}">${val}</option>`);
                    }
                });
            });

            // Save Assessment Header (Pretest/Posttest)
            $('#testConfigForm').submit(function(e) {
                e.preventDefault();

                const trainingId = $('#assessment_training_id').val();
                const $btn = $('#btn-save-assessment');
                $btn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: `/trainings/${trainingId}/assessment`,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        type: $('#test_type').val(),
                        title: $('#test_title').val(),
                        passing_score: $('#test_passing_score').val()
                    },
                    success: function(res) {
                        $('#assessment_id').val(res.data.id);
                        $('#question-builder-section').slideDown();
                        fetchQuestions(res.data.id);

                        Toast.fire({
                            icon: 'success',
                            title: 'Assessment header saved! You can now add questions.'
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error saving assessment header.'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Save Config & Unlock Questions'
                            );
                    }
                });
            });

            // Add Question
            $('#questionForm').submit(function(e) {
                e.preventDefault();

                const assessmentId = $('#assessment_id').val();
                const type = $('#q_type').val();
                let options = [];
                let correctAnswer = '';

                if (type === 'multiple_choice') {
                    $('.option-input').each(function() {
                        if ($(this).val().trim() !== '') {
                            options.push($(this).val().trim());
                        }
                    });
                    correctAnswer = $('#mcq_correct_answer').val();
                } else if (type === 'true_false') {
                    correctAnswer = $('#tf_correct_answer').val();
                } else if (type === 'open_text') {
                    correctAnswer = $('#open_correct_answer').val();
                }

                $.ajax({
                    url: `/assessments/${assessmentId}/questions`,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        question_text: $('#question_text').val(),
                        type: type,
                        options: options,
                        correct_answer: correctAnswer,
                        points: $('#q_points').val() || 1
                    },
                    success: function() {
                        $('#questionForm')[0].reset();
                        $('#mcq_correct_answer').empty().append(
                            '<option value="">Select Correct Choice...</option>');
                        $('#q_type').trigger('change');
                        fetchQuestions(assessmentId);

                        Toast.fire({
                            icon: 'success',
                            title: 'Question added successfully!'
                        });
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to add question.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
