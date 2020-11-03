<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{langDir()}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}

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
    {{--<style>
        body{
            background-image: url({{url('uploads/bg-profile.jpg')}});
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>--}}
    @stack('css')
</head>
<body>
    <div id="app">
        @include('frontend.includes.navbar')

        <main class="py-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-2">
                        @include('frontend.includes.sidebar-profile')
                    </div>
                    <div class="col-sm-10 mt-2 pl-4">
                            <h2 class="m-0 text-secondary text-center">{{ (!empty($pageTitle)) ?$pageTitle: 'Empty title'  }}</h2>
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

    </div>
    @include('backend.includes.modals')

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('js/app.js') }}" ></script>
    {{--<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
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


     {{--Begin Listen to status changed --}}
    <script>
    $(document).ready(function(){

            Echo.private('App.User.{{auth()->user()->id}}')
                .notification( (notification) => {
                    console.log(notification.type);

                    var notify_count = $('#notify-count');
                    if($(notify_count).is(':empty')){
                        $('#notify-count').text("1");               // add 1 to bell icon badge
                    }else{
                        $('#notify-count').text(parseInt($('#notify-count').text())+1);         // just increase the number
                    }

                    $('#notify-container').prepend("<a href="+notification.url+" class=\"dropdown-item notify-link\" id=\""+notification.id+"\">\n" +
                        "    <div class=\"row  text-truncate notify-title\">\n" +
                        "        <i class=\"fas fa-bell mr-2\"></i>\n" +
                        "        <span class=\"notify-unread text-bold\">"+notification.notification_title+"</span>\n" +
                        "    </div>\n" +
                        "    <div class=\"row overflow-hidden text-truncate notify-time\">\n" +
                        "        <span class=\"float-right text-muted text-sm\">"+notification.time+"</span>\n" +
                        "    </div>\n" +
                        "</a>");
                });


            $(document).on('click', '.notify-link', function(){       //mark notification as read when click it
                var unread = $(this).find('span.notify-unread');
                if(unread.length > 0){
                    var id = $(this).attr('id');
                    $.ajax({
                        url: "{{ route('notifyMarkAsRead') }}",
                        method: "post",
                        data:{
                            "id": id,
                            "_token": "{{csrf_token()}}"
                        },
                        success: function(data){
                            if(data.status == true)
                                console.log('notification marked as read');
                        }
                    });
                }
            });

    });

    </script>

    @stack('scripts')

</body>
</html>


