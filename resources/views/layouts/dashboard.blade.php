<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conplasa</title>

    <!-- Fontfaces CSS-->
    <link href="{!! asset('theme/css/font-face.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/font-awesome-4.7/css/font-awesome.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/font-awesome-5/css/fontawesome-all.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/mdi-font/css/material-design-iconic-font.min.css') !!}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{!! asset('theme/vendor/bootstrap-4.1/bootstrap.min.css') !!}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{!! asset('theme/vendor/animsition/animsition.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/wow/animate.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/css-hamburgers/hamburgers.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/slick/slick.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/select2/select2.min.css') !!}" rel="stylesheet" media="all">
    <link href="{!! asset('theme/vendor/perfect-scrollbar/perfect-scrollbar.css') !!}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{!! asset('theme/css/theme.css') !!}" rel="stylesheet" media="all">

</head>
<body class="animsition">
    <div class="page-wrapper">
        @yield('page')

       
        <!-- END MENU SIDEBAR-->
        @include('layouts.partials.sidebar')
        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            @include('layouts.partials.header-desktop')
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>
    <!-- Jquery JS-->
    <script src="{!! asset('theme/vendor/jquery-3.2.1.min.js') !!}"></script>
    <!-- Bootstrap JS-->
    <script src="{!! asset('theme/vendor/bootstrap-4.1/popper.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/bootstrap-4.1/bootstrap.min.js') !!}"></script>
    <!-- Vendor JS       -->
    <script src="{!! asset('theme/vendor/slick/slick.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/wow/wow.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/animsition/animsition.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/counter-up/jquery.waypoints.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/counter-up/jquery.counterup.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/circle-progress/circle-progress.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/perfect-scrollbar/perfect-scrollbar.js') !!}"></script>
    <script src="{!! asset('theme/vendor/chartjs/Chart.bundle.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/select2/select2.min.js') !!}"></script>

    <!-- Main JS-->
    <script src="{!! asset('theme/js/main.js') !!}"></script>
</body>
</html>