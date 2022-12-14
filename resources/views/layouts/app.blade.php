<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <!-- <link href="{{ asset('css/cdncss/bootstrap.min.css') }}"  rel="stylesheet" /> -->
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/all.css') }}"  rel="stylesheet" />
    <link href="{{ asset('css/cdncss/icheck-bootstrap.min.css') }}"  rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}"  rel="stylesheet" />
 <!--    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" /> -->
    @yield('styles')
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    @yield('content')
    @yield('scripts')
  <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
