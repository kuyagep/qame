@extends('layouts.main')

@section('title', $assessment->title)

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white p-3">
                        <span class="badge bg-light text-primary text-uppercase me-2">
                            {{ $assessment->type }}
                        </span>
                        <h4 class="mb-0 mt-1 font-weight-bold">{{ $assessment->training->title }}</h4>
                        <small class="text-white-50">Session: {{ $assessment->title ?? 'General' }}</small>
                    </div>

                    <div class="card-body p-4">
                        <form id="assessmentForm">
                            @csrf
                            <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

                            @foreach ($assessment->questions as $index => $question)
                                <div class="card mb-3 border">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold mb-3">
                                            {{ $index + 1 }}. {{ $question->question_text }}
                                            <span class="badge bg-secondary ms-1">{{ $question->points }} pt(s)</span>
                                        </h6>

                                        @php
                                            // Standardize options handling (JSON array or string list)
                                            $options = is_array($question->options)
                                                ? $question->options
                                                : json_decode($question->options, true) ?? [];
                                        @endphp

                                        @if ($question->type === 'multiple_choice' || $question->type === 'true_false')
                                            @foreach ($options as $optKey => $option)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="answers[{{ $question->id }}]"
                                                        id="q_{{ $question->id }}_opt_{{ $optKey }}"
                                                        value="{{ $option }}" required>
                                                    <label class="form-check-label"
                                                        for="q_{{ $question->id }}_opt_{{ $optKey }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Open-Ended / Short Answer -->
                                            <textarea class="form-control" name="answers[{{ $question->id }}]" rows="3"
                                                placeholder="Type your answer here..." required></textarea>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="btn-submit-assessment">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Assessment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#assessmentForm').submit(function(e) {
                e.preventDefault();

                const $btn = $('#btn-submit-assessment');
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');

                $.ajax({
                    url: "{{ route('participant.assessments.submit') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        Swal.fire({
                            icon: res.is_passed ? 'success' : 'info',
                            title: res.is_passed ? 'Passed!' : 'Assessment Submitted',
                            html: `Your Score: <strong>${res.total_score}</strong> (${res.percentage}%)`,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = res.redirect_url || '/dashboard';
                        });
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-paper-plane me-1"></i> Submit Assessment');

                        let message = 'Failed to submit assessment.';
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Error',
                            html: message
                        });
                    }
                });
            });
        });
    </script>
@endpush
