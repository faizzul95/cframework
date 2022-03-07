<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <base href="{{ base_url }}">
    <title> {{ $title }} | {{ $_ENV['APP_NAME'] }} </title>
    <meta charset="utf-8" />
    <meta name="description" content="Cathink Framework by Canthink Solution" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('framework/favicon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="base_url" content="{{ base_url }}" />

    <!-- START : THIS NEED TO REPLACE FROM TEMPLATE ASSET -->

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <!-- datatable 5 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- sweatAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

    <!-- toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>

    <style>
        body {
            background-size: contain;
            background-attachment: fixed;
            background-position-y: bottom;
            background-repeat: no-repeat;
            font-family: 'Quicksand', sans-serif !important;
            line-height: 1.5;
            letter-spacing: 0.0312rem !important;
        }
    </style>

    <!-- END : THIS NEED TO REPLACE FROM TEMPLATE ASSET -->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('framework/css/pre.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('framework/css/jquery.skeleton.css') }}" rel="stylesheet" type="text/css"/>

    <!--end::Global Stylesheets Bundle-->

    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('framework/js/axios.min.js') }}"></script>
    <script src="{{ asset('framework/js/common.js') }}"></script>
    <script src="{{ asset('framework/js/jquery.scheletrone.js') }}"></script>
    <!--end::Global Javascript Bundle-->


</head>
<!--end::Head-->

<!--begin::Body-->

<body style="background-image: url({{ asset('img/bg4.png') }});">
    <div class="container">
        @yield('content')
    </div>
</body>
<!--end::Body-->

</html>

@include('app.views.modals._modalLogout')
@include('app.views.modals._modalGeneral')
@include('public.framework.php.general')