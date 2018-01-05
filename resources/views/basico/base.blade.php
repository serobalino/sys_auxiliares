<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name') }} | @yield('titulo')</title>
    <link href="{{ asset('/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/lib/datatables/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/animate.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/asecont-custom.css')}}" rel="stylesheet" type="text/css">
    @yield('css')
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-toggleable-md navbar-light">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('/favicon.ico')}}" width="30" height="30" class="d-inline-block align-top" alt="">
                {{ config('app.name') }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                @yield('path')
                </ul>
                <div class="my-2 my-lg-0 dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" href="#salir" aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-user"></span> {{ Auth::guard('adm')->user()->nombres_ad }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        {!! Form::open(['route'=> 'logout', 'method' => 'DELETE']) !!}
                        <button class="dropdown-item" type="submit" style="cursor:pointer">Salir</button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </nav>
    </div>
    @yield('cuerpo')
<script src="{{ asset('/lib/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('/lib/tether/tether.min.js')}}"></script>
<script src="{{ asset('/lib/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('/lib/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('/lib/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('/lib/datatables/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/lib/datatables/js/dataTables.select.min.js')}}"></script>
@yield('scripts')
</body>
</html>