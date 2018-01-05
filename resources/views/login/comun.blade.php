<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description" content="AQUA" />
    <meta name="keywords" content="AQUA" />
    <meta name="author" content="SD">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>{{ config('app.name') }} | Autenticación</title>
    <link rel="stylesheet" href="<?= asset('css/login.css')?>">
    <link href="{{ asset('/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/lib/animate/animate.min.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper {{old('usr') == '' ? '' : 'error' }}">
    <div class="container animated rubberBand">
        <h1><span class="fa fa-id-card-o"></span> Ingreso al sistema</h1>
        @if (old('usr'))
            <p class="danger-info animated wobble"><span class="fa fa-exclamation-circle"> {{ trans('auth.failed') }}</span> </p>
        @endif
        <form class="form" method="post" action="{{url('/ingresar')}}">
            {{ csrf_field() }}
            <input type="text" placeholder="usuario" value="{{ old('usr') }}" name="usr">
            <input type="password" placeholder="contraseña" name="psw">
            <button type="submit" id="login-button">Entrar <span class="ion-play"></span></button>
        </form>
    </div>
    <ul class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
<script src="<?= asset('js/jquery.min.js')?>"></script>
<script src="<?= asset('js/login.js')?>"></script>
</body>
</html>