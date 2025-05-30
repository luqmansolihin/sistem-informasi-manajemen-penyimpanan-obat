<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/image/online-pharmacy.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">

    @stack('stylesheet')

    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

</head>
<body class="hold-transition sidebar-mini">
@yield('modal')

<div class="wrapper">
    @yield('app')
</div>

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

@stack('script')

<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
