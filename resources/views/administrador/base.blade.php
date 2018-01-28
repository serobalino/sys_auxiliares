<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="ASECONT PUYO - Asesoría Contable Tributaria - Puyo Pastaza Ecuador">
    <meta property="og:image"              content="{{asset('img/asecont_logo.png',true)}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('css/usuario.css')}}" rel="stylesheet">
</head>
<body class="@yield('body')">
    @yield('cuerpo')
    <script src="{{ mix('js/usuario.vendor.js')}}"></script>
    <script src="{{ mix('js/usuario.js')}}"></script>
</body>
</html>