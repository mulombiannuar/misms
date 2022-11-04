<!--
Author : Anuary Mulombi - 2022
Author EMAIL: mulombiannuar@gmail.com
Author PHONE : (254) 0703539028
License: Mulan Technologies
License URL: https://mulan.co.ke
-->

<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app.incls.metas')
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/custom.css') }}">
</head>

<body class="hold-transition login-page register-page">
    <div class="login-box register-box">
        <div class="login-logo">
            <img src="{{ asset('assets/dist/img/mulan-logo-new.png') }}" alt="Mulan logo" width="200px" srcset="">
        </div>
        @include('layouts.auth.incls.alerts')
        @yield('content')
    </div>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }} "></script>
    @stack('scripts')
</body>

</html>
