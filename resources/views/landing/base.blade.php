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
    <meta property="og:image"              content="img/logos/asecont_logo.png" />
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/lib/animate/animate.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/asecont.css')}}" rel="stylesheet">
</head>
<body>
    @yield('cuerpo')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXphG-N8S7KAOCbfRGPeogIq07yYZCazo"></script>
    <script src="{{ asset('/lib/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('/lib/tether/tether.min.js')}}"></script>
    <script src="{{ asset('/lib/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/lib/jquery/jquery-easing.js')}}"></script>
    <script src="{{ asset('/lib/jquery/wow.min.js')}}"></script>
    <script src="{{ asset('/js/validation.js')}}"></script>
    <script src="{{ asset('/js/contactos.js')}}"></script>
    <script src="{{ asset('/js/asecont.min.js')}}"></script>
    <script>
        new WOW().init();
    </script>
</body>
</html>