<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FontAwesome --}}
    <link href="{{ asset('v1/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    {{-- Toastr.js CSS --}}
    <link rel="stylesheet" href="{{ asset('v1/plugins/toastr/toastr.min.css') }}">

    {{-- Favicons --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('v1/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('v1/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('v1/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('v1/images/favicon/site.webmanifest') }}">

    {{-- Custom Auth Engine Styles --}}
    <link rel="stylesheet" href="{{ asset('v1/css/auth.css') }}">

    @stack('styles')
</head>

<body>

    @yield('body')

    {{-- jQuery (Required for Toastr.js) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- Bootstrap 5 Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

        // Catch backend system notifications instantly
        @if (session('success'))
            SystemAlert.toast('success', @json(session('success')));
        @endif
        @if (session('error'))
            SystemAlert.toast('error', @json(session('error')));
        @endif
    </script>

    @stack('scripts')
</body>

</html>
