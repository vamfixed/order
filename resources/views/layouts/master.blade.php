<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ordering System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        @include('layouts.partials.navbar')

        @include('layouts.partials.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper mt-3">
            {{-- @yield('content') --}}
            <div class="container-fluid">
                <router-view></router-view>
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.partials.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>

</html>
