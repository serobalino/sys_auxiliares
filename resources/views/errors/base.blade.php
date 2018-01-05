<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name') }} | Error</title>
    <link href="{{ asset('/error/css/estilos.css')}}" rel="stylesheet">
    <link href="{{ asset('/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="content">
    <div class="browser-bar">
        <span class="close button"></span>
        <span class="min button"></span>
        <span class="max button"></span>
    </div>
    <div class="text"></div>
</div>
<script src="{{ asset('/lib/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('/error/js/error.js')}}"></script>
</body>
</html>