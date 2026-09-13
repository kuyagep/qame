@extends('layouts.auth-split')

@section('title', 'Login')

@section('content')

    <div class="w-100 px-lg-4 px-2">
        <div class="mx-auto" style="max-width: 420px;">

            <!-- Branding Logo -->
            {{-- <div class="mb-4 text-center text-lg-start">
                <a href="{{ url('/') }}">
                    <img src="{{ !empty($settings['app_logo']) ? route('image.show', 'app_logo') : asset('v1/images/division_logo_blue.png') }}"
                        alt="{{ $settings['app_name'] ?? 'System Logo' }}" class="img-fluid" style="max-height: 80px;">
                </a>
            </div> --}}

            <!-- Header Text -->
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Welcome back</h3>
                <p class="text-muted small mb-0">Please enter your credentials to access your account.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <!-- Username/Email Field -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1" for="email">
                        Email, Username, or Employee ID
                    </label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" required autofocus
                        autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-semibold text-secondary mb-0" for="password">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" required
                            autocomplete="current-password">
                        <span class="input-group-text cursor-pointer" type="button" id="toggle-password-visibility"
                            tabindex="-1" aria-label="Toggle password visibility">
                            <i class="fas fa-eye-slash text-muted" id="password-icon"></i>
                        </span>

                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Security Captcha Check -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1" for="captcha">Security Check</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-7">
                            <input type="text" name="captcha" id="captcha"
                                class="form-control @error('captcha') is-invalid @enderror" placeholder="Enter code"
                                required autocomplete="off">
                        </div>
                        <div class="col-5">
                            <div class="d-flex align-items-center justify-content-between border rounded bg-light p-1">
                                <div id="captcha-img-container" class="w-100 text-center" style="cursor: pointer;"
                                    title="Click to refresh">
                                    <img src="{{ captcha_src('default') }}" alt="Captcha Code" class="img-fluid"
                                        style="max-height: 34px;">
                                </div>
                                <button type="button" class="btn btn-sm btn-light text-secondary border-0 ms-1"
                                    id="reload-captcha" title="Refresh Captcha" aria-label="Refresh Captcha">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('captcha')
                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="remember">Keep me signed in on this device</label>
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold shadow-sm" type="submit" id="btn-login">
                    Sign In
                </button>
            </form>

            <!-- Registration Link -->
            @if (Route::has('register'))
                <div class="text-center mt-4">
                    <span class="small text-muted">Don't have an account yet?</span>
                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none small ms-1">
                        Create an account
                    </a>
                </div>
            @endif

            <!-- Footer -->
            <div class="text-center mt-5 pt-3 border-top">
                <p class="small text-muted mb-0">
                    &copy; {{ date('Y') }} {{ $settings['app_name'] ?? config('app.name') }}. All rights reserved.
                </p>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Visibility Toggle
            const togglePasswordBtn = document.getElementById('toggle-password-visibility');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (togglePasswordBtn && passwordInput && passwordIcon) {
                togglePasswordBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    passwordIcon.classList.toggle('fa-eye-slash', !isPassword);
                    passwordIcon.classList.toggle('fa-eye', isPassword);
                });
            }

            // Captcha Reload Handler
            const reloadBtn = document.getElementById('reload-captcha');
            const container = document.getElementById('captcha-img-container');

            function refreshCaptcha() {
                if (!reloadBtn || !container) return;

                const icon = reloadBtn.querySelector('i');
                if (icon) icon.classList.add('fa-spin');

                fetch('/reload-captcha')
                    .then(response => response.json())
                    .then(data => {
                        container.innerHTML = data.captcha;
                    })
                    .catch(error => {
                        console.error('Captcha reload failed:', error);
                    })
                    .finally(() => {
                        if (icon) icon.classList.remove('fa-spin');
                    });
            }

            if (reloadBtn) reloadBtn.addEventListener('click', refreshCaptcha);
            if (container) container.addEventListener('click', refreshCaptcha);

            // Prevent Multi-Submit
            const loginForm = document.getElementById('login-form');
            const btnLogin = document.getElementById('btn-login');

            if (loginForm && btnLogin) {
                loginForm.addEventListener('submit', function() {
                    btnLogin.disabled = true;
                    btnLogin.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Signing in...';
                });
            }
        });
    </script>
@endpush
