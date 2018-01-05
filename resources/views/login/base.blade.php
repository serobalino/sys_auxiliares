<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/dist/css/bootstrap-responsive.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/dist/css/asecont-login.css')}}" rel="stylesheet">
    <link href="{{ asset('/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/lib/animate/animate.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/dist/css/asecont.css')}}" rel="stylesheet">
</head>
<body>
@yield('cuerpo')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXphG-N8S7KAOCbfRGPeogIq07yYZCazo"></script>
<script src="{{ asset('/dist/js/jquery.min.js')}}"></script>
<script src="{{ asset('/dist/js/asecont.login.js')}}"></script>
<script src="{{ asset('/lib/jquery/wow.min.js')}}"></script>
<script src="{{ asset('/js/borrar.js')}}"></script>
<script>
    new WOW().init();
</script>
</body>
</html>