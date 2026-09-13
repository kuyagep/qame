@extends('layouts.auth.base')

@section('body')
    <div class="container-fluid vh-100 p-0 overflow-hidden">
        <div class="row g-0 h-100">
            {{-- LEFT BRANDING PANEL --}}
            <div class="col-md-6 col-lg-7 col-xl-8 d-none d-md-flex auth-brand align-items-center">
                <div class="auth-brand-content w-100 text-center px-5">
                    <img src="{{ asset('v1/images/division_logo_white.png') }}" class="img-fluid mb-4" alt="Brand Logo"
                        style="max-height: 120px;">
                    <div>
                        <span class="d-block fw-bold text-uppercase text-white"
                            style="font-size: 2.75rem; letter-spacing: 0.05em;">
                            Qaulity Assurance
                        </span>
                        <!-- Warm Amber/Gold Accent Text -->
                        <span class="d-block text-brand font-sans fw-bold" style="font-size: 1.85rem;">
                            Monitoring & Evaluation System
                        </span>
                    </div>
                </div>
            </div>

            {{-- RIGHT FORM PANEL --}}
            <div class="col-12 col-md-6 col-lg-5 col-xl-4 auth-form d-flex align-items-center py-4 bg-white">
                <div class="w-100 px-3 px-sm-4 px-md-5 my-auto">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
