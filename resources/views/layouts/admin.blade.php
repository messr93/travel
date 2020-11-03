<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>AdminLTE 3 | Dashboard 2</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->

    @if(langDir() == "ltr")
        <link rel="stylesheet" href="{{asset('assets/admin/dist/css/adminlte.min.css')}}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @else
        <link rel="stylesheet" href="{{asset('assets/admin/dist/css/rtl/adminlte.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/dist/css/rtl/bootstrap-rtl.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/dist/css/rtl/custom-style.css')}}">

        <link href="https://fonts.googleapis.com/css2?family=Cairo" rel="stylesheet">
        <style type="text/css">
            html, body{
                font-family: 'Cairo', sans-serif;
            }
        </style>
    @endif

    @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

    @include('backend.includes.header')
    @include('backend.includes.sidebar')

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class=" mb-2">
                        <h1 class="m-0 text-secondary text-center">{{ (!empty($pageTitle)) ?$pageTitle: 'Empty title'  }}</h1>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    @include('backend.includes.footer')
    @include('backend.includes.modals')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

        <!-- jQuery -->
        <script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('assets/admin/dist/js/adminlte.js')}}"></script>

        <!-- OPTIONAL SCRIPTS -->
        {{--<script src="{{asset('assets/admin/dist/js/demo.js')}}"></script>--}}

        <!-- PAGE PLUGINS -->
        <!-- jQuery Mapael -->
        <script src="{{asset('assets/admin/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/raphael/raphael.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
        <!-- ChartJS -->
        <script src="{{asset('assets/admin/plugins/chart.js/Chart.min.js')}}"></script>

        <!-- PAGE SCRIPTS -->
        {{--<script src="{{asset('assets/admin/dist/js/pages/dashboard2.js')}}"></script>--}}

        @stack('scripts')


</body>
</html>
