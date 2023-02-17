<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets-purple/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-purple/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets-purple/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets-purple/images/favicon.ico') }}" />
</head>

<body>
    @include('layouts.admin.navheader')

    @include('layouts.admin.sidebar')

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    <!-- partial -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets-purple/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets-purple/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets-purple/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets-purple/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets-purple/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets-purple/js/misc.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('assets-purple/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets-purple/js/todolist.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('myscript')
    <!-- End custom js for this page -->
</body>

</html>
