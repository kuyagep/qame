@extends('layouts.main')

@section('title', 'Speakers/Facilitators Evaluation')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 col-lg-10 mx-auto">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="m-0 font-weight-bold">Speakers / Facilitators Evaluation</h4>
                <small>{{ $training->title }}</small>
            </div>
            <div class="card-body p-4">
                <form id="speakerEvaluationForm">
                    @csrf

                    <!-- Demographic Profile -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Demographic Profile</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="e.g. Juan Dela Cruz">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Sex <span class="text-danger">*</span></label>
                            <select class="form-control" name="sex" required>
                                <option value="">Select Sex...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">District <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="district" required
                                placeholder="e.g. North District">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Name of School / Office <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="school_office" required
                                placeholder="e.g. Central Elementary School">
                        </div>
                    </div>

                    <!-- Session & Resource Speaker Selection -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mt-4 mb-3">Session & Speaker Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Select Session <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="session_select" name="session_id" required>
                                <option value="">Select a Session...</option>
                                @foreach ($sessions as $session)
                                    <option value="{{ $session->id }}"
                                        data-speaker="{{ $session->facilitator->name ?? 'N/A' }}"
                                        data-day="{{ $session->day ?? 'N/A' }}">
                                        {{ $session->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small text-muted mb-1">Day</label>
                            <input type="text" class="form-control bg-light" id="session_day" name="day" readonly
                                placeholder="Auto-filled">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small text-muted mb-1">Resource Speaker</label>
                            <input type="text" class="form-control bg-light" id="resource_speaker" name="speaker_name"
                                readonly placeholder="Auto-filled">
                        </div>
                    </div>

                    <!-- Rating Criteria Matrix -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mt-4 mb-2">Evaluation Criteria</h5>
                    <p class="text-muted small">Directions: Rate the following statements using the scale below.</p>
                    <div class="alert alert-light border small py-2 mb-3">
                        <strong>SCALE:</strong> (4) Strongly Agree &nbsp;|&nbsp; (3) Agree &nbsp;|&nbsp; (2) Disagree
                        &nbsp;|&nbsp; (1) Strongly Disagree
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 55%;">Statement</th>
                                    <th style="width: 10%;">4<br><small>Strongly Agree</small></th>
                                    <th style="width: 10%;">3<br><small>Agree</small></th>
                                    <th style="width: 10%;">2<br><small>Disagree</small></th>
                                    <th style="width: 10%;">1<br><small>Strongly Disagree</small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $questions = [
                                        'q1_started_on_time' => '1. The session started on time.',
                                        'q2_objectives_explained' =>
                                            '2. The session objectives were explained at the beginning of the session.',
                                        'q3_topics_understandable' =>
                                            '3. The resource speaker explained the topics in an understandable level.',
                                        'q4_time_pace_sufficient' =>
                                            '4. The time and pace allotted for the session was sufficient to absorb inputs or to accomplish outputs.',
                                        'q5_establishes_rapport' =>
                                            '5. The resource speaker establishes rapport with participants.',
                                        'q6_positive_environment' =>
                                            '6. The resource speaker established and maintained a positive/non-threatening and comfortable learning environment.',
                                        'q7_communication_skills' =>
                                            '7. The resource speaker demonstrated good communication skills (verbal and non-verbal).',
                                        'q8_appropriate_technology' =>
                                            '8. The resource speaker used appropriate technology with ease and confidence.',
                                        'q9_synthesized_responses' =>
                                            '9. The resource speaker synthesized the responses of the participants and the activities of the session.',
                                        'q10_flexibility_adaptability' =>
                                            '10. The resource speaker exhibited flexibility and adaptability in the delivery of the session to ensure an appropriate response to unforeseen situations.',
                                        'q11_professional_manner' =>
                                            '11. The resource speaker presented him/herself in a professional manner.',
                                        'q12_ended_on_time' => '12. The session ended on time.',
                                    ];
                                @endphp

                                @foreach ($questions as $field => $questionText)
                                    <tr>
                                        <td>{{ $questionText }}</td>
                                        @for ($rating = 4; $rating >= 1; $rating--)
                                            <td class="text-center">
                                                <input type="radio" name="{{ $field }}" value="{{ $rating }}"
                                                    required>
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Qualitative Feedback -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mt-4 mb-3">Feedback & Insights</h5>
                    <div class="form-group mb-3">
                        <label class="form-label small text-muted mb-1">What key insights did you gain from the
                            session?</label>
                        <textarea class="form-control" name="key_insights" rows="3" placeholder="Write your response..."></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label small text-muted mb-1">Do you have any suggestions to improve future
                            sessions?</label>
                        <textarea class="form-control" name="suggestions" rows="3" placeholder="Write your response..."></textarea>
                    </div>

                    <!-- Data Privacy Statement -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-dark">PRIVACY STATEMENT</h6>
                            <p class="text-muted small mb-3" style="text-align: justify; line-height: 1.5;">
                                The Department of Education (DepEd) is bound by law under the Data Privacy Act of 2012 (RA
                                10173). Your attendance at this event aims to develop your skills in performing your tasks
                                through your employment or affiliation with DepEd. By selecting the checkbox below this
                                statement and clicking "Submit", you agree to the following:
                                <br>1- You express your consent for and authorize DepEd, through the organizers of this
                                activity, to collect, process, and keep your personal information for lawful purposes
                                related to the conduct of this event.
                                <br>2- DepEd, through the organizers of this activity, cannot disclose your personal
                                information to any third parties without your explicit permission. It can, however, share
                                said information with its bureaus/offices/services and external agencies, affiliates, or
                                partners to fulfill financial, logistic, and other contractual obligations or to comply with
                                law enforcement and legal processes.
                                <br>3- The organizers of this online orientation may record this event for reference and
                                documentation. By attending this event, you give DepEd and the organizers permission to
                                include you and your likeness in or make a subject of any communications media they see fit
                                (photo, video, social media, or print). You certify that you have agreed to the above
                                information and that you are well-informed of the purposes of this endeavor.
                            </p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="privacy_consent"
                                    id="privacy_consent" value="1" required>
                                <label class="form-check-label font-weight-bold text-dark small" for="privacy_consent">
                                    I have read, understood, and accept the Privacy Statement.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4" id="btn-submit-eval">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Evaluation
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
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Auto-fill Day and Speaker when Session is selected
            $('#session_select').change(function() {
                const selectedOption = $(this).find(':selected');
                const speaker = selectedOption.data('speaker') || '';
                const day = selectedOption.data('day') || '';

                $('#resource_speaker').val(speaker);
                $('#session_day').val(day);
            });

            // AJAX Form Submission
            $('#speakerEvaluationForm').submit(function(e) {
                e.preventDefault();

                const $btn = $('#btn-submit-eval');
                $btn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: "{{ route('trainings.speaker-evaluations.store', $training->id) }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Evaluation Submitted!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to submit evaluation. Please check your form.';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Error',
                            text: errorMsg
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-paper-plane mr-1"></i> Submit Evaluation');
                    }
                });
            });
        });
    </script>
@endpush
