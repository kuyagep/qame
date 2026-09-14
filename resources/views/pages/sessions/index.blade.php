@extends('layouts.main')

@section('title', 'Training Sessions & Topics')
@section('content-header', 'Sessions & Topics - ' . $training->title)

@section('content')
    <div class="container-fluid pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('trainings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Trainings
            </a>
            <button class="btn btn-primary btn-sm" id="btn-add-session">
                <i class="fas fa-plus mr-1"></i> Add Session
            </button>
        </div>

        <div id="session-container">
            <!-- Loaded via AJAX -->
        </div>
    </div>

    <!-- Session Modal -->
    <div class="modal fade" id="sessionModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="sessionModalTitle">Add Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="sessionForm">
                    @csrf
                    <input type="hidden" id="session_id" name="id">
                    <div class="modal-body py-2">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small text-muted mb-1">Day <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="session_day" name="day" min="1"
                                    value="1" required>
                                <div class="invalid-feedback" id="error-day"></div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label small text-muted mb-1">Session Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="session_title" name="title"
                                    placeholder="e.g. Knowledge Management" required>
                                <div class="invalid-feedback" id="error-title"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Facilitator</label>
                            <select class="form-control" id="facilitator_id" name="facilitator_id">
                                <option value="">-- Select Facilitator --</option>
                                @foreach ($facilitators as $facilitator)
                                    <option value="{{ $facilitator->id }}">{{ $facilitator->name }}
                                        ({{ $facilitator->office_division ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-facilitator_id"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted mb-1">Start Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                                    required>
                                <div class="invalid-feedback" id="error-start_time"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted mb-1">End Time <span
                                        class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                                <div class="invalid-feedback" id="error-end_time"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save-session">Save Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Topic Modal -->
    <div class="modal fade" id="topicModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="topicModalTitle">Add Topic</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="topicForm">
                    @csrf
                    <input type="hidden" id="topic_session_id" name="session_id">
                    <input type="hidden" id="topic_id" name="id">
                    <div class="modal-body py-2">
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label class="form-label small text-muted mb-1">Topic Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="topic_title" name="title"
                                    placeholder="e.g. Introduction to KM" required>
                                <div class="invalid-feedback" id="error-topic_title"></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label small text-muted mb-1">Order</label>
                                <input type="number" class="form-control" id="topic_order" name="order"
                                    value="1" min="1">
                                <div class="invalid-feedback" id="error-topic_order"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Learning Objectives</label>
                            <textarea class="form-control" id="learning_objectives" name="learning_objectives" rows="3"
                                placeholder="State objectives..."></textarea>
                            <div class="invalid-feedback" id="error-learning_objectives"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-save-topic">Save Topic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Test Management Modal (Pretest / Posttest) -->
    <div class="modal fade" id="testModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title font-weight-bold" id="testModalTitle">Manage Assessment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <form id="testConfigForm" class="mb-4 p-3 bg-light rounded border">
                        @csrf
                        <input type="hidden" id="test_session_id">
                        <input type="hidden" id="test_type">
                        <input type="hidden" id="test_id">
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted mb-1">Test Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="test_title" required
                                    placeholder="e.g. Pretest: Knowledge Management">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small text-muted mb-1">Passing Score (%)</label>
                                <input type="number" class="form-control form-control-sm" id="test_passing_score"
                                    value="70" min="1" max="100">
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-sm btn-primary w-100"
                                    id="btn-save-test-config">Save Title</button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <div id="question-management-section" style="display: none;">
                        <h6 class="font-weight-bold mb-3"><i class="fas fa-question-circle mr-1 text-primary"></i>
                            Assessment Questions</h6>

                        <!-- Form to Add Question -->
                        <form id="questionForm" class="card card-body bg-white border shadow-sm mb-4">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Question Text <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="question_text" rows="2" placeholder="Enter question..." required></textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Question Type</label>
                                    <select class="form-control" id="question_type">
                                        <option value="multiple_choice">Multiple Choice</option>
                                        <option value="true_false">True / False</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Points</label>
                                    <input type="number" class="form-control" id="question_points" value="1"
                                        min="1">
                                </div>
                            </div>

                            <!-- Choices Section -->
                            <div class="mb-3" id="options-container">
                                <label class="form-label small text-muted mb-1">Options & Answer Key <span
                                        class="text-danger">*</span></label>
                                <div id="options-list">
                                    <!-- Dynamic options inserted here -->
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-success" id="btn-add-question">
                                    <i class="fas fa-plus-circle mr-1"></i> Add Question to Test
                                </button>
                            </div>
                        </form>

                        <!-- Existing Questions List -->
                        <h6 class="font-weight-bold mb-2">Existing Questions (<span id="question-count">0</span>)</h6>
                        <div id="questions-list-container">
                            <!-- Question cards appended via JS -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const trainingId = "{{ $training->id }}";
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            const sessionModal = new bootstrap.Modal(document.getElementById('sessionModal'));
            const topicModal = new bootstrap.Modal(document.getElementById('topicModal'));
            const testModal = new bootstrap.Modal(document.getElementById('testModal'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Helper function for user-friendly display
            function formatDisplayTime(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString; // Return original if parsing fails

                return new Intl.DateTimeFormat('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }).format(date);
            }


            fetchSessions();

            function fetchSessions() {
                $.get(`/trainings/${trainingId}/sessions`, function(data) {
                    let html = '';
                    if (!data || data.length === 0) {
                        html =
                            `<div class="card p-4 text-center text-muted">No sessions created yet for this training.</div>`;
                    } else {
                        $.each(data, function(index, session) {
                            let topicsHtml = '';
                            if (session.topics && session.topics.length > 0) {
                                $.each(session.topics, function(tIdx, topic) {
                                    topicsHtml += `
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${topic.order ? topic.order + '.' : ''} ${topic.title}</strong>
                                                ${topic.learning_objectives ? `<p class="mb-0 text-muted small"><i class="fas fa-bullseye mr-1 text-secondary"></i>${topic.learning_objectives}</p>` : ''}
                                            </div>
                                            <div>
                                                <button class="btn btn-xs btn-outline-primary btn-edit-topic" data-id="${topic.id}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-xs btn-outline-danger btn-delete-topic" data-id="${topic.id}"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </li>`;
                                });
                            } else {
                                topicsHtml =
                                    `<li class="list-group-item text-muted small py-3 text-center">No topics added yet.</li>`;
                            }

                            const facilitatorName = session.facilitator ? session.facilitator.name :
                                'Unassigned';

                            const formattedStart = formatDisplayTime(session.start_time);
                            const formattedEnd = formatDisplayTime(session.end_time);

                            const timeDisplay = session.time_range ?
                                session.time_range :
                                (formattedStart && formattedEnd ?
                                    `${formattedStart} - ${formattedEnd}` : (formattedStart ||
                                        formattedEnd || 'N/A'));

                            html += `
                                <div class="card shadow-sm border-0 mb-4" id="session-card-${session.id}">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <span class="badge badge-primary mr-2">Day ${session.day || 1}</span>
                                            <strong class="h6 text-dark mb-0">${session.title}</strong>
                                            <div class="text-muted small mt-1">
                                                <span class="mr-3"><i class="fas fa-user-tie mr-1 text-primary"></i> ${facilitatorName}</span>
                                                <span><i class="fas fa-clock mr-1 text-info"></i> ${timeDisplay}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-success mr-1 btn-add-topic" data-session-id="${session.id}">
                                                <i class="fas fa-plus mr-1"></i> Add Topic
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary mr-1 btn-edit-session" data-id="${session.id}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-delete-session" data-id="${session.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="px-3 py-2 bg-light border-bottom text-uppercase small font-weight-bold text-muted">
                                            <i class="fas fa-book-open mr-1"></i> Topics & Learning Objectives
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            ${topicsHtml}
                                        </ul>
                                    </div>


                                </div>`;
                        });
                    }
                    $('#session-container').html(html);
                });
            }

            // --- SESSION CRUD LOGIC ---
            function clearSessionForm() {
                $('#sessionForm')[0].reset();
                $('#session_id').val('');
                $('#session_day').val('1');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            $('#btn-add-session').click(function() {
                clearSessionForm();
                $('#sessionModalTitle').text('Add Session');
                sessionModal.show();
            });

            $(document).on('click', '.btn-edit-session', function() {
                clearSessionForm();
                const id = $(this).data('id');
                $.get(`/sessions/${id}/edit`, function(data) {
                    $('#sessionModalTitle').text('Edit Session');
                    $('#session_id').val(data.id);
                    $('#session_day').val(data.day);
                    $('#session_title').val(data.title);

                    // Helper function to format date string to YYYY-MM-DDTHH:mm
                    function formatDateTimeLocal(dateTimeStr) {
                        if (!dateTimeStr) return '';
                        // Replaces "2026-10-12 08:30:00" -> "2026-10-12T08:30"
                        return dateTimeStr.replace(' ', 'T').substring(0, 16);
                    }

                    $('#facilitator_id').val(data.facilitator_id);
                    // Set the formatted values to the datetime-local inputs
                    $('#start_time').val(formatDateTimeLocal(data.start_time));
                    $('#end_time').val(formatDateTimeLocal(data.end_time));

                    sessionModal.show();
                });
            });

            $('#sessionForm').submit(function(e) {
                e.preventDefault();
                const id = $('#session_id').val();
                const url = id ? `/sessions/${id}` : `/trainings/${trainingId}/sessions`;
                const method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: csrfToken,
                        day: $('#session_day').val(),
                        title: $('#session_title').val(),
                        facilitator_id: $('#facilitator_id').val(),
                        start_time: $('#start_time').val(),
                        end_time: $('#end_time').val()
                    },
                    success: function(res) {
                        sessionModal.hide();
                        fetchSessions();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }
                });
            });

            $(document).on('click', '.btn-delete-session', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Session?',
                    text: "All topics and tests will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/sessions/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            success: function(res) {
                                fetchSessions();
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            }
                        });
                    }
                });
            });

            // --- TOPIC CRUD LOGIC ---
            function clearTopicForm() {
                $('#topicForm')[0].reset();
                $('#topic_id').val('');
                $('#topic_session_id').val('');
                $('#topic_order').val('1');
            }

            $(document).on('click', '.btn-add-topic', function() {
                clearTopicForm();
                $('#topic_session_id').val($(this).data('session-id'));
                $('#topicModalTitle').text('Add Topic');
                topicModal.show();
            });

            $(document).on('click', '.btn-edit-topic', function() {
                clearTopicForm();
                const id = $(this).data('id');
                $.get(`/topics/${id}/edit`, function(data) {
                    $('#topicModalTitle').text('Edit Topic');
                    $('#topic_id').val(data.id);
                    $('#topic_session_id').val(data.session_id);
                    $('#topic_title').val(data.title);
                    $('#learning_objectives').val(data.learning_objectives);
                    $('#topic_order').val(data.order);
                    topicModal.show();
                });
            });

            $('#topicForm').submit(function(e) {
                e.preventDefault();
                const topicId = $('#topic_id').val();
                const sessionId = $('#topic_session_id').val();
                const url = topicId ? `/topics/${topicId}` : `/sessions/${sessionId}/topics`;
                const method = topicId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: csrfToken,
                        title: $('#topic_title').val(),
                        learning_objectives: $('#learning_objectives').val(),
                        order: $('#topic_order').val()
                    },
                    success: function(res) {
                        topicModal.hide();
                        fetchSessions();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }
                });
            });

            $(document).on('click', '.btn-delete-topic', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `/topics/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(res) {
                        fetchSessions();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                    }
                });
            });

            // --- PRETEST / POSTTEST QUESTION ENGINE ---
            let currentTestQuestions = [];

            function renderOptionsInputs(type) {
                let html = '';
                if (type === 'multiple_choice') {
                    html = `
                        <div class="mb-2 input-group input-group-sm">
                            <div class="input-group-text"><input type="radio" name="correct_option" value="0" checked></div>
                            <input type="text" class="form-control option-input" placeholder="Option A" required>
                        </div>
                        <div class="mb-2 input-group input-group-sm">
                            <div class="input-group-text"><input type="radio" name="correct_option" value="1"></div>
                            <input type="text" class="form-control option-input" placeholder="Option B" required>
                        </div>
                        <div class="mb-2 input-group input-group-sm">
                            <div class="input-group-text"><input type="radio" name="correct_option" value="2"></div>
                            <input type="text" class="form-control option-input" placeholder="Option C">
                        </div>
                        <div class="mb-2 input-group input-group-sm">
                            <div class="input-group-text"><input type="radio" name="correct_option" value="3"></div>
                            <input type="text" class="form-control option-input" placeholder="Option D">
                        </div>`;
                } else {
                    html = `
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="correct_tf" id="tf_true" value="True" checked>
                            <label class="form-check-label" for="tf_true">True</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="correct_tf" id="tf_false" value="False">
                            <label class="form-check-label" for="tf_false">False</label>
                        </div>`;
                }
                $('#options-list').html(html);
            }

            $('#question_type').change(function() {
                renderOptionsInputs($(this).val());
            });

            // Open Test Manager
            $(document).on('click', '.btn-manage-test', function() {
                const sessionId = $(this).data('session-id');
                const testType = $(this).data('type');

                $('#test_session_id').val(sessionId);
                $('#test_type').val(testType);
                $('#testModalTitle').text(`Manage ${testType.toUpperCase()}`);

                renderOptionsInputs('multiple_choice');
                $('#questionForm')[0].reset();
                $('#options-container').show();

                loadTestData(sessionId, testType);
                testModal.show();
            });

            function loadTestData(sessionId, type) {
                $.get(`/sessions/${sessionId}/assessment`, function(assessments) {
                    const test = assessments.find(t => t.type === type);
                    if (test) {
                        $('#test_id').val(test.id);
                        $('#test_title').val(test.title);
                        $('#test_passing_score').val(test.passing_score);
                        $('#question-management-section').show();

                        currentTestQuestions = test.questions || [];
                        renderQuestionsList();
                    } else {
                        $('#test_id').val('');
                        $('#test_title').val(`${type.toUpperCase()} Assessment`);
                        $('#test_passing_score').val(70);
                        $('#question-management-section').hide();
                        $('#questions-list-container').html('');
                        $('#question-count').text(0);
                    }
                });
            }

            function renderQuestionsList() {
                $('#question-count').text(currentTestQuestions.length);
                let html = '';
                if (currentTestQuestions.length === 0) {
                    html = `<p class="text-muted small italic">No questions added to this test yet.</p>`;
                } else {
                    $.each(currentTestQuestions, function(idx, q) {
                        let opts = Array.isArray(q.options) ? q.options : JSON.parse(q.options || '[]');
                        let optsBadge = opts.map(o => o === q.correct_answer ?
                            `<span class="badge badge-success mr-1">${o} ✓</span>` :
                            `<span class="badge badge-light border mr-1">${o}</span>`).join(' ');

                        html += `
                            <div class="card mb-2 border-sm">
                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="font-weight-bold">${idx + 1}. ${q.question_text} <span class="badge badge-secondary ml-2">${q.points} pt(s)</span></div>
                                        <div class="mt-1">${optsBadge}</div>
                                    </div>
                                    <button class="btn btn-xs btn-outline-danger btn-delete-question" data-id="${q.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>`;
                    });
                }
                $('#questions-list-container').html(html);
            }

            // Save Test Config (Title/Passing Score)
            $('#testConfigForm').submit(function(e) {
                e.preventDefault();

                const sessionId = $('#test_session_id').val();
                const testType = $('#test_type').val();
                const testTitle = $('#test_title').val();
                const passingScore = $('#test_passing_score').val();

                // Guard check: Ensure required inputs exist and have values before sending
                if (!sessionId || !testTitle) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Session ID or Test Title is missing. Please check your form fields.'
                    });
                    return;
                }

                // Disable button to prevent double-submission
                const $btn = $('#btn-save-test-config');
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: `/sessions/${sessionId}/assessment`, // Ensure this matches your routes/web.php or routes/api.php
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        type: testType,
                        title: testTitle,
                        passing_score: passingScore
                    },
                    dataType: 'json',
                    success: function(res) {
                        // Check response payload structure safely
                        const testId = res.data ? res.data.id : res.id;
                        $('#test_id').val(testId);
                        $('#question-management-section').show();

                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Assessment saved successfully!'
                        });

                        if (typeof fetchSessions === 'function') {
                            fetchSessions();
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error Response:', xhr);

                        let errorMessage = 'An unexpected error occurred while saving.';

                        if (xhr.status === 422) {
                            // Laravel Validation Errors
                            const errors = xhr.responseJSON.errors;
                            if (errors) {
                                errorMessage = Object.values(errors).flat().join('<br>');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        } else if (xhr.status === 404) {
                            errorMessage =
                                `Endpoint missing! Verify route '/sessions/${sessionId}/assessment' in routes/web.php.`;
                        } else if (xhr.status === 419) {
                            errorMessage =
                                'CSRF token mismatch/session expired. Please refresh the page.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: `Error (${xhr.status})`,
                            html: errorMessage
                        });
                    },
                    complete: function() {
                        // Re-enable button
                        $btn.prop('disabled', false).text('Save Title');
                    }
                });
            });

            // Save Question to Test
            $('#questionForm').submit(function(e) {
                e.preventDefault();
                const testId = $('#test_id').val();
                const type = $('#question_type').val();

                let options = [];
                let correctAnswer = '';

                if (type === 'multiple_choice') {
                    $('.option-input').each(function() {
                        if ($(this).val().trim() !== '') options.push($(this).val().trim());
                    });
                    const selectedIdx = $('input[name="correct_option"]:checked').val();
                    correctAnswer = options[selectedIdx] || options[0];
                } else {
                    options = ['True', 'False'];
                    correctAnswer = $('input[name="correct_tf"]:checked').val();
                }

                $.ajax({
                    url: `/assessment/${testId}/questions`,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        question_text: $('#question_text').val(),
                        question_type: type,
                        options: options,
                        correct_answer: correctAnswer,
                        points: $('#question_points').val()
                    },
                    success: function(res) {
                        currentTestQuestions.push(res.data);
                        renderQuestionsList();
                        $('#question_text').val('');
                        renderOptionsInputs(type);
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                        fetchSessions();
                    }
                });
            });

            // Delete Question
            $(document).on('click', '.btn-delete-question', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `/questions/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(res) {
                        currentTestQuestions = currentTestQuestions.filter(q => q.id !== id);
                        renderQuestionsList();
                        Toast.fire({
                            icon: 'success',
                            title: res.message
                        });
                        fetchSessions();
                    }
                });
            });
        });
    </script>
@endpush
