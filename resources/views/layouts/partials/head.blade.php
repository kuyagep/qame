<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('v1/plugins/fontawesome-free/css/all.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('v1/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('v1/dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('v1/css/app.css') }}">

<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('v1/images/favicon/') }}/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('v1/images/favicon/') }}/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('v1/images/favicon/') }}/favicon-16x16.png">
<link rel="manifest" href="{{ asset('v1/images/favicon/') }}/site.webmanifest">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
