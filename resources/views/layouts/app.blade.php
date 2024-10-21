<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Laravel | @yield('title')</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/vendors/css/vendors.min.css') }}" />
    @stack('css_vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('template') }}/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('template') }}/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('template') }}/app-assets/vendors/css/extensions/sweetalert2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/css/bootstrap-extended.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/css/colors.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/app-assets/css/components.css') }}" />

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('template/app-assets/css/core/menu/menu-types/vertical-menu.css') }}" />
    @stack('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/css/style.css') }}" />
</head>

<body class="vertical-layout vertical-menu-modern footer-static" data-open="click" data-menu="vertical-menu-modern"
    data-col="">

    </head>
    <!-- END: Head-->

    <!-- BEGIN: Body-->

    <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
        data-menu="vertical-menu-modern" data-col="">

        <!-- BEGIN: Header-->
        @includeIf('layouts.partials.header')
        <!-- END: Header-->


        <!-- BEGIN: Main Menu-->
        @includeIf('layouts.partials.sidebar')
        <!-- END: Main Menu-->

        <!-- BEGIN: Content-->
        <div class="app-content content ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-left mb-0">@yield('title')</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                        </li>
                                        @section('breadcrumb')
                                        @show
                                        {{-- <li class="breadcrumb-item"><a href="#">Forms</a>
                                        </li> --}}

                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    {!! $pageConfigs['toolbar'] ?? '' !!}
                    <div class="row mb-2">
                        <div class="col-lg-12 mb-2">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Content-->
        @includeIf('layouts.partials.footer')
        <!-- END: Footer-->


        <!-- BEGIN: Vendor JS-->
        <script src="{{ asset('template/app-assets/vendors/js/vendors.min.js') }}"></script>
        <script>
            var APP_URL = {!! json_encode(url('/')) !!}
        </script>
        <!-- BEGIN Vendor JS-->

        <!-- BEGIN: Page Vendor JS-->

        <script src="{{ asset('template') }}/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
        <script src="{{ asset('template/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
        <script src="{{ asset('template/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>

        <script src="{{ asset('template') }}/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
        <script src="{{ asset('template') }}/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
        @stack('script_vendor')
        <!-- END: Page Vendor JS-->

        <!-- BEGIN: Theme JS-->
        <script src="{{ asset('template/app-assets/js/core/app-menu.js') }}"></script>
        <script src="{{ asset('template/app-assets/js/core/app.js') }}"></script>
        <script>
            var APP_URL = {!! json_encode(url('/')) !!}
        </script>
        <script src="{{ asset('template/assets/js/custom-js.js') }}"></script>

        <!-- END: Theme JS-->

        <!-- BEGIN: Page JS-->

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script>
            $('.custom-file-input').on('change', function() {
                let filename = $(this).val().split('\\').pop();
                $(this)
                    .next('.custom-file-label')
                    .addClass('selected')
                    .html(filename);
            });

            function preview(target, image) {
                $(target)
                    .attr('src', window.URL.createObjectURL(image))
                    .show();
            }
        </script>
        @stack('script')
        <!-- END: Page JS-->

        <script>
            $(window).on("load", function() {
                if (feather) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                }
            });
        </script>
    </body>
    <!-- END: Body-->

</html>
