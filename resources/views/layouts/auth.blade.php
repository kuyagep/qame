<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- FontAwesome --}}
    <link href="{{ asset('v1/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- Toastr.js CSS --}}
    <link rel="stylesheet" href="{{ asset('v1/plugins/toastr/toastr.min.css') }}">

    {{-- Custom Auth Engine styles --}}
    <link rel="stylesheet" href="{{ asset('v1/css/auth.css') }}">

    @stack('styles')
</head>

<body>

    <div class="container-fluid vh-100">
        <div class="row h-100">

            {{-- LEFT PANEL: Fluidly collapses on tablet sizes to hand over real estate to the form --}}
            <div class="col-md-6 col-lg-7 col-xl-8 d-none d-md-flex auth-brand align-items-center">
                <div class="auth-brand-content w-100 text-center px-5">
                    <img src="{{ asset('v1/images/unnamed.png') }}" class="img-fluid" alt="Brand Logo">
                    <h1>{{ config('app.name') }}</h1>
                    <p>Secure. Fast. Reliable.</p>
                </div>
            </div>

            {{-- RIGHT PANEL: Adapts down to full width on mobile devices, stays roomy on standard displays --}}
            <div class="col-12 col-md-6 col-lg-5 col-xl-4 auth-form d-flex align-items-center py-4">
                <div class="w-100 px-3 px-sm-4 px-md-5 my-auto">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- Bootstrap 5 Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    {{-- Toastr.js Plugin --}}
    <script src="{{ asset('v1/plugins/toastr/toastr.min.js') }}"></script>

    {{-- SystemAlert Session Bridge Hooks --}}
    <script>
        window.SystemAlert = {
            toast: function(icon, message) {
                if (icon === 'danger') icon = 'error';

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "4000"
                };

                if (typeof toastr[icon] === 'function') {
                    toastr[icon](message);
                } else {
                    toastr.info(message);
                }
            }
        };

        @if (session('success'))
            SystemAlert.toast('success', "{{ session('success') }}");
        @endif
        @if (session('error'))
            SystemAlert.toast('error', "{{ session('error') }}");
        @endif
    </script>

    @stack('scripts')

</body>

</html>
