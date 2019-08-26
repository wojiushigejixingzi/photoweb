<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>

    <script src="//vuejs.org/js/vue.min.js"></script>
    <script src="//unpkg.com/iview/dist/iview.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="//unpkg.com/iview/dist/styles/iview.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/information.css') }}" type="text/css" rel="stylesheet" />
    @yield('css')
</head>
<body>
@yield('body')
@yield('js')
</body>
</html>