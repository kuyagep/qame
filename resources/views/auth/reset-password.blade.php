@extends('layouts.auth-split')

@section('title', 'Reset Password')

@section('content')

    <div class="w-100 px-lg-5 px-4">

        <div class="mx-auto" style="max-width:420px;">

            <h2 class="fw-bold mb-2">
                Reset Password
            </h2>

            <p class="text-muted mb-4">
                Enter your new password below.
            </p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                {{-- @method('PUT') --}}

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div class="mb-3">

                    <input type="email" name="email" value="{{ old('email', $request->email) }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Email Address" required>

                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Password --}}
                <div class="mb-3">

                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="New Password" autofocus
                        required>

                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">

                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        placeholder="Confirm Password" required>

                </div>

                <div class="form-check mb-4">

                    <input class="form-check-input" type="checkbox" id="togglePassword">

                    <label class="form-check-label" for="togglePassword">

                        Show Passwords

                    </label>

                </div>

                <button type="submit" class="btn btn-primary w-100">

                    <i class="fas fa-key me-2"></i>

                    Reset Password

                </button>

            </form>

            <div class="text-center mt-4">

                <a href="{{ route('login') }}">
                    Back to Login
                </a>

            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('change', function() {

            const type = this.checked ? 'text' : 'password';

            document.getElementById('password').type = type;
            document.getElementById('password_confirmation').type = type;

        });
    </script>
@endpush
