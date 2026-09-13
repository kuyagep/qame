@extends('layouts.auth.base')

@section('body')
    <div class="min-vh-100 d-flex flex-column align-items-center justify-content-center bg-brand py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-7">
                    {{-- Branding Logo / Header --}}
                    <div class="text-center mb-4">
                        <a href="/"
                            class="d-inline-flex flex-column align-items-center text-decoration-none text-white fw-bold fs-4">
                            <img src="{{ asset('v1/images/division_logo_white.png') }}" class="img-fluid mb-2" alt="Brand Logo"
                                style="max-height: 100px;">
                            <span>Portal System</span>
                        </a>
                    </div>

                    {{-- Main Form Card Slot --}}
                    @yield('content')

                    {{-- Footer Links --}}
                    <div class="text-center mt-4 text-white-50 small">
                        &copy; {{ date('Y') }} Portal System. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
