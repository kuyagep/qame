@extends('layouts.auth-centered') {{-- Unified with your previous layout --}}

@section('title', 'Registration Review Pending')

@section('content')
    <div class="card my-3 mx-2 bg-white rounded shadow-sm border-0">
        <div class="card-body p-4 p-md-5 mx-auto" style="max-width: 450px; width: 100%;">

            <h3 class="fw-bold text-dark mb-2 text-center fs-4">
                Account Review Pending
            </h3>

            <p class="text-muted mb-4 text-center small">
                Thank you for registering! Your credentials have been successfully received.
            </p>

            <hr class="my-4">

            <div class="alert alert-warning border p-3 rounded mb-4 small text-start ">
                <div class="d-flex gap-2">
                    <i class="fas fa-shield-alt text-warning mt-1"></i>
                    <div>
                        <strong class="text-dark">Security Notice:</strong>
                        <div class="text-muted mt-1" style="font-size: 0.825rem; line-height: 1.4;">
                            To safeguard system data, all new user profiles require human verification by a systems
                            administrator before access is unlocked.
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted text-center mb-4 small">
                You will receive a confirmation email once your account verification is finalized.
            </p>

            <div class="d-grid mt-2">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm fw-bold shadow-sm py-2">
                    <i class="fas fa-arrow-left me-2"></i> Return to Login
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
@endpush
