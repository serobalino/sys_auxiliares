@extends('basico.base')
@section('titulo') Procesadas @endsection
@section('css')
   <link href="{{ asset('/lib/dropzone/css/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('path')
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.inicio')}}">Clientes</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.cli.ruc',$ruc_cl)}}">{{$razon_cl}}</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item active">
            <a class="nav-link" href="#">Procesadas</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-3">Procesando los comprobantes</h1>
            <p class="lead">Espere un momento</p>
            <hr class="my-4">
            <h2>{{$razon_cl}}</h2>
            {{ csrf_field() }}
            <input type="hidden" name="_url" value="{{route('adm.cli.proajax.ele',$ruc_cl)}}">
            <div id="alerta">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="list-group" id="lista">
            <div class="flex-column align-items-start">
                <div class="text-center text-primary">
                    <br>
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    <br>
                </div>
            </div>
        </div>
        <br>
        <nav id="botones" class="hidden hidden-xs-up">
            <ul class="pagination pagination-lg justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="{{route('adm.cli.sub.ele',$ruc_cl)}}">Regresar</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{route('adm.cli.ruc',$ruc_cl)}}">Siguiente</a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
@section('scripts')
<script src="{{ asset('/js/alertas.js')}}"></script>
<script src="{{ asset('/js/comprobantes_procesar.js')}}"></script>
@endsection