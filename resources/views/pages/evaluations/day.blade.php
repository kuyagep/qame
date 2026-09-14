@extends('layouts.main')

@section('title', 'End of the Day Evaluation')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 col-lg-10 mx-auto">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="m-0 font-weight-bold">End of the Day Evaluation</h4>
                <small>{{ $training->title }}</small>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small border-bottom pb-3 mb-4">
                    Please take a moment to give us feedback about the content and process of the program on this evaluation
                    form. Rest assured that responses will remain anonymous and will be used for the improvement of the
                    program.
                </p>

                <form id="dayEvaluationForm">
                    @csrf

                    <!-- Demographic Profile -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3">Participant Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Name <span
                                    class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" name="name"
                                placeholder="Leave blank for anonymous">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Sex</label>
                            <select class="form-control" name="sex">
                                <option value="">Select Sex...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Name of School / Office</label>
                            <input type="text" class="form-control" name="school_office"
                                placeholder="e.g. Central Elementary School">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-muted mb-1">Day <span class="text-danger">*</span></label>
                            <select class="form-control" name="day" required>
                                <option value="">Select Training Day...</option>
                                @for ($d = 1; $d <= ($training->number_of_days ?? 5); $d++)
                                    <option value="Day {{ $d }}">Day {{ $d }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Program Management Section -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mt-4 mb-2">Program Management</h5>
                    <p class="text-muted small">Directions: Rate the following statements using the scale below.</p>
                    <div class="alert alert-light border small py-2 mb-3">
                        <strong>SCALE:</strong> (4) Strongly Agree &nbsp;|&nbsp; (3) Agree &nbsp;|&nbsp; (2) Disagree
                        &nbsp;|&nbsp; (1) Strongly Disagree
                    </div>

                    <div class="table-responsive mb-4">
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
                                    $pmQuestions = [
                                        'pm1_time_management' => '1. The program started and ended on time.',
                                        'pm2_clear_instructions' =>
                                            '2. The information and instruction given throughout the program were clear and easy to follow.',
                                        'pm3_logical_organization' => '3. The organization of the program was logical.',
                                        'pm4_time_allotted' => '4. The time allotted for the session was sufficient.',
                                        'pm5_adequate_breaks' =>
                                            '5. Adequate session breaks (mid-morning, lunch and mid-afternoon).',
                                        'pm6_proper_structure' => '6. The program was structured properly.',
                                        'pm7_inclusive_language' =>
                                            '7. Socially-inclusive, gender-sensitive, and non-discriminatory stereotypical language was always used.',
                                        'pm8_efficient_management' => '8. The program was managed efficiently.',
                                        'pm9_pmt_responsiveness' =>
                                            '9. The PMT is responsive to the needs of the participants.',
                                    ];
                                @endphp

                                @foreach ($pmQuestions as $field => $questionText)
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

                    <!-- Training Venue Section -->
                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mt-4 mb-2">Training Venue</h5>
                    <div class="table-responsive mb-4">
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
                                    $venueQuestions = [
                                        'tv1_lighting_ventilation' =>
                                            '1. The venue is well-lighted and well-ventilated.',
                                        'tv2_space' => '2. The venue has sufficient space for program activities.',
                                        'tv3_soundproofing' => '3. The venue has an adequate soundproofing.',
                                        'tv4_cleanliness_restrooms' =>
                                            '4. The venue is clean and has accessible comfort rooms.',
                                        'tv5_internet_connection' =>
                                            '5. The venue has a strong and reliable internet connection working during the program.',
                                        'tv6_meal_quality' => '6. Meals were of satisfactory quality and varied.',
                                        'tv7_meal_nutrition' => '7. Meals were nutritious.',
                                    ];
                                @endphp

                                @foreach ($venueQuestions as $field => $questionText)
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
                        <label class="form-label small text-muted mb-1">What important insights have you learned
                            today?</label>
                        <textarea class="form-control" name="important_insights" rows="3" placeholder="Write your response..."></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label small text-muted mb-1">What could have been done better to improve the
                            conduct of this program?</label>
                        <textarea class="form-control" name="suggestions" rows="3" placeholder="Write your response..."></textarea>
                    </div>

                    <!-- Privacy Statement -->
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
                                <input class="form-check-input" type="checkbox" name="privacy_consent" id="privacy_consent"
                                    value="1" required>
                                <label class="form-check-label font-weight-bold text-dark small" for="privacy_consent">
                                    I have read, understood, and accept the Privacy Statement.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4" id="btn-submit-day-eval">
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
            $('#dayEvaluationForm').submit(function(e) {
                e.preventDefault();

                const $btn = $('#btn-submit-day-eval');
                $btn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: "{{ route('trainings.day-evaluations.store', $training->id) }}",
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
