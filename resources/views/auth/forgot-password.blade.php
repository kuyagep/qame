@extends('layouts.auth-split')

@section('title', 'Forgot Password')

@section('content')

    <div class="w-100 px-lg-5 px-4">

        <div class="mx-auto" style="max-width:420px;">

            <h2 class="fw-bold mb-2">
                Forgot Password
            </h2>

            <p class="text-muted mb-4">
                Enter your email address and we'll send you a password reset link.
            </p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">

                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" required
                        autofocus>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <button type="submit" class="btn btn-primary w-100">

                    <i class="fas fa-paper-plane me-2"></i>

                    Send Password Reset Link

                </button>

            </form>

            <div class="text-center mt-4">

                Remember your password?

                <a href="{{ route('login') }}">
                    Login
                </a>

            </div>

            @if (Route::has('register'))
                <div class="text-center mt-2">

                    Don't have an account?

                    <a href="{{ route('register') }}">
                        Register
                    </a>

                </div>
            @endif

        </div>

    </div>

@endsection
