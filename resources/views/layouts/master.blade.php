<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/brand/favicon.ico" />

    <!-- TITLE -->
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/skin-modes.css') }}" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ asset('assets/colors/color1.css') }}" />
    <style>
        #basic-datatable thead tr {
            background: #ccc;
        }
    </style>
    <style>
        body {
            font-family: Arial, Sans;
            margin: 0;
        }

        .wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            text-align: center;
            transform: translateX(-50%);
        }

        .spanner {
            position: absolute;
            top: 50%;
            left: 0;
            background: #2a2a2a55;
            width: 100%;
            display: block;
            text-align: center;
            height: 300px;
            color: #FFF;
            transform: translateY(-50%);
            z-index: 1000;
            visibility: hidden;
        }

        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            visibility: hidden;
        }

        .loader,
        .loader:before,
        .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation: load7 1.8s infinite ease-in-out;
            animation: load7 1.8s infinite ease-in-out;
        }

        .loader {
            color: #ffffff;
            font-size: 10px;
            margin: 80px auto;
            position: relative;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }

        .loader:before,
        .loader:after {
            content: '';
            position: absolute;
            top: 0;
        }

        .loader:before {
            left: -3.5em;
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }

        .loader:after {
            left: 3.5em;
        }

        @-webkit-keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        @keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        .show {
            visibility: visible;
        }

        .spanner,
        .overlay {
            opacity: 0;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .spanner.show,
        .overlay.show {
            opacity: 1
        }
    </style>
    @stack('css')
</head>

<div class="overlay" style="z-index: 998;"></div>
<div class="spanner" style="background: inherit !important;">
    <div class="loader"></div>
    <p>Please be patient.</p>
</div>


<body class="app sidebar-mini ltr">
    @include('partials.loader')

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            @include('partials.header')

            @include('partials.sidebar')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    @include('partials.errors')

                    @yield('content')
                </div>
            </div>
            <!--app-content closed-->
        </div>

        @include('partials.sidebar-right')

        <!-- @include('partials.modal') -->

        @include('partials.footer')
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- CHARTJS CHART JS-->
    <script src="{{ asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart/utils.js') }}"></script>

    <!-- C3 CHART JS -->
    <script src="{{ asset('assets/plugins/charts-c3/d3.v5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/charts-c3/c3-chart.js') }}"></script>

    <script src="{{ asset('assets/plugins/summernote/summernote1.js') }}"></script>

    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

    <!-- SIDE-MENU JS-->
    <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>

    <!-- SIDEBAR JS -->
    <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>

    <!-- Perfect SCROLLBAR JS-->
    <!-- <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/plugins/p-scroll/pscroll.js') }}"></script> -->
    <!-- <script src="{{ asset('assets/plugins/p-scroll/pscroll-1.js') }}"></script> -->

    <!-- INTERNAL SELECT2 JS -->
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <!-- Color Theme js -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>

    <!-- Sticky js -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        var timeOut = "{{env('SESSION_LIFETIME')}}" * 60000;

        var idleTimer;
        var idleTimeout = "{{env('SESSION_LIFETIME')}}" * 60 * 1000; // 15 minutes in milliseconds

        function resetIdleTimer() {
            clearTimeout(idleTimer);
            idleTimer = setTimeout(function() {
                window.location.href = '/check-session-status';
                // document.getElementById('logout').submit();
            }, idleTimeout);
        }

        $(document).ready(function() {
            // Start idle timer
            resetIdleTimer();

            // Listen for user activity events
            $(document).on('mousemove keydown click touchstart scroll', function() {
                resetIdleTimer();
            });
        });

        function reloadPage() {
            location.reload(true); // Reload the page
        }

        $.ajax({
            type: "GET",
            url: "{{ route('user.session.id') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {},
        });
    </script>

    @stack('js')
</body>

</html>