@extends('layouts.auth-centered')

@section('title', 'Verify Your Email')

@section('content')
    <div class="card my-3 mx-2 bg-white rounded shadow-sm border-0">
        <div class="card-body p-4 p-md-5 mx-auto" style="max-width: 450px; width: 100%;">

            <h3 class="fw-bold text-dark mb-2 text-center fs-4">
                Verify Your Email
            </h3>

            <p class="text-muted mb-4 text-center small">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we
                just emailed to you.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success border p-3 rounded mb-4 small text-start">
                    <div class="d-flex gap-2">
                        <i class="fas fa-check-circle text-success mt-1"></i>
                        <div class="text-success" style="font-size: 0.825rem; line-height: 1.4;">
                            A new verification link has been sent to the email address you provided during registration.
                        </div>
                    </div>
                </div>
            @endif

            <hr class="my-4">

            <div class="alert alert-warning border p-3 rounded mb-4 small text-start">
                <div class="d-flex gap-2">
                    <i class="fas fa-envelope text-warning mt-1"></i>
                    <div>
                        <strong class="text-dark">Didn't receive the email?</strong>
                        <div class="text-muted mt-1" style="font-size: 0.825rem; line-height: 1.4;">
                            Check your spam or junk folder. If it's still missing, you can request another link below.
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-2">
                {{-- Resend Verification Email Form --}}
                <form method="POST" action="{{ route('verification.send') }}" class="d-grid">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm py-2">
                        <i class="fas fa-paper-plane me-2"></i> Resend Verification Email
                    </button>
                </form>

                {{-- Sign Out Form --}}
                <form method="POST" action="{{ route('logout') }}" class="d-grid mt-1">
                    @csrf
                    <button type="submit" class="btn btn-secondary border-2 btn-sm fw-bold py-2 text-white">
                        <i class="fas fa-sign-out-alt me-1"></i> Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
@endpush
