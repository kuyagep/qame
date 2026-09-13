<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.partials.head')
    @include('layouts.partials.styles')
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed ">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('v1/') }}/dist/img/AdminLTELogo.png" alt="AdminLTELogo"
                height="60" width="60">
        </div> --}}

        <!-- Navbar -->
        @include('layouts.partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-light elevation-1">
            <!-- Brand Logo -->
            @include('layouts.partials.brand')

            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h4 class="m-0">@yield('content-header')</h4>
                        </div>
                        <div class="col-sm-6 text-right">
                            @yield('header-action')
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <!-- Main Footer -->
        @include('layouts.partials.footer')
    </div>
    <!-- ./wrapper -->
    @include('layouts.partials.scripts')

    @stack('scripts')
</body>

</html>
