@extends('basico.base')
@section('titulo') {{$cliente->apellidos_cl}} {{$cliente->nombres_cl}} @endsection
@section('css')
    <link href="{{ asset('/css/daterangepicker.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('path')
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.inicio')}}">Clientes</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.cli.ruc',$cliente->ruc_cl)}}">{{$cliente->razon_cl}}</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item active">
            <a class="nav-link" href="#">Clasificar comprobantes</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="jumbotron text-center" style="background-color: #e3f2fd;">
        <h1 class="display-3">{{$cliente->razon_cl}}</h1>
        <p class="lead">{{$cliente->apellidos_cl}} {{$cliente->nombres_cl}}<br>{{$cliente->ruc_cl}}</p>
    </div>
    <div class="container">
        <div class="card">
            <div id="alerta"></div>
            <div class="card-block mx-auto">
                <form class="form-inline">
                    <label for="basic-url"> Fecha </label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-calendar" ></span>
                        <input type="text" class="form-control" id="rangoFechas" placeholder="Elija fechas">
                        {{ csrf_field() }}
                    </div>
                    <div class="form-group">
                        <label for="tauxiliar"> Tipo de auxiliar </label>
                        <select class="form-control" id="auxiliar">
                            <option value="R">Renta</option>
                            <option value="I">IVA</option>
                            @foreach ($aux as $i)
                                <option value="{{$i->id_tg}}">{{$i->impuesto_im}} - {{$i->label_tg}}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="input-group">
                    <div class="mx-auto">
                        <br>
                        <button class="btn btn-primary btn-lg" id="boton"><span class="fa fa-send"></span> Generar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="btn-group" id="botones">
                        <button class="btn btn-success" id="excel"><span class="fa fa-file-excel-o"></span> Excel</button>
                    </div>
                </div>
                <table class="table table-sm" id="comprobantes">
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('/js/moment.min.js')}}"></script>
    <script src="{{ asset('/js/daterangepicker.js')}}"></script>
    <script src="{{ asset('/js/alertas.js')}}"></script>
    <script src="{{ asset('/js/fechaAuxiliar.js')}}"></script>
    <script>
        ponerUrl('{{route('adm.cli.fecha',$cliente->ruc_cl)}}','{{route('adm.cli.aux.excel',$cliente->ruc_cl)}}');
    </script>
@endsection