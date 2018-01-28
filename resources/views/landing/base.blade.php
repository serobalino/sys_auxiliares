<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="ASECONT PUYO - Asesoría Contable Tributaria - Puyo Pastaza Ecuador">
    <meta name="author" content="ASECONT - PUYO">
    <meta name="keywords" content="ASECONT, ASECONT PUYO, SRI PUYO, TRIBUTACIÓN ECUADOR, CONTABILIDAD ECUADOR">
    <meta property="og:url"                content="http://www.asecont-puyo.com" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="ASECONT - PUYO | Asesoría Contable Tributaria" />
    <meta property="og:description"        content="ASECONT - PUYO" />
    <meta property="og:image"              content="{{asset('img/asecont_logo.png',true)}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('css/invitado.css')}}" rel="stylesheet">
</head>
<body class="landing-page">
    @yield('cuerpo')
    <!-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXphG-N8S7KAOCbfRGPeogIq07yYZCazo"></script>-->
    <script src="{{ mix('js/invitado.vendor.js')}}"></script>
    <script src="{{ mix('js/invitado.js')}}"></script>
</body>
</html>