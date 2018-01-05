@extends('basico.base')
@section('titulo') {{$apellidos_cl}} {{$nombres_cl}} @endsection
@section('path')
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.inicio')}}">Clientes</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item active">
            <a class="nav-link" href="#">{{$razon_cl}}</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="jumbotron text-center" style="background-color: #e3f2fd;">
        <h1 class="display-3">{{$apellidos_cl}} {{$nombres_cl}}</h1>
        <p class="lead">{{$ruc_cl}}</p>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 bg-faded">
                <br>
                <div class="card">
                    <div class="card-header" role="tab" id="hventas">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#ventas" aria-expanded="false" aria-controls="ventas">Ventas</a>
                        </h5>
                    </div>
                    <div id="ventas" class="collapse" role="tabpanel" aria-labelledby="hventas">
                        <div class="card-block">
                            <a href="#" class="list-group-item list-group-item-action disabled">Comprobantes Electrónicos</a>
                            <a href="#" class="list-group-item list-group-item-action disabled">Comprobantes Físicos</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" role="tab" id="hgastos">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#gastos" aria-expanded="false" aria-controls="gastos">Gastos</a>
                        </h5>
                    </div>
                    <div id="gastos" class="collapse" role="tabpanel" aria-labelledby="hgastos">
                        <div class="card-block">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.sub.ele',$ruc_cl)}}">Comprobantes Electrónicos</a>
                                <a class="list-group-item list-group-item-action disabled" href="#fisicos">Comprobantes Físicos</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header" role="tab" id="nauxiliar">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#auxiliar" aria-expanded="false" aria-controls="auxiliar">Auxiliar</a>
                        </h5>
                    </div>
                    <div id="auxiliar" class="collapse" role="tabpanel" aria-labelledby="nauxiliar">
                        <div class="card-block">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.aux',$ruc_cl)}}">Auxiliar de egresos</a>
                                <a class="list-group-item list-group-item-action disabled" href="#fisicos">Auxiliar de ingresos</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header" role="tab" id="hcliente">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#cliente" aria-expanded="false" aria-controls="cliente">Cliente</a>
                        </h5>
                    </div>
                    <div id="cliente" class="collapse show" role="tabpanel" aria-labelledby="hcliente">
                        <div class="card-block">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per.con',$ruc_cl)}}">Contraseñas</a>
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per.doc',$ruc_cl)}}">Documentos registrados</a>
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per',$ruc_cl)}}">Informacion de contacto</a>
                                <a class="list-group-item list-group-item-action active" href="#cuentasBancarias">Cta Bancarias</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="col-sm-9 col-md-10 card"> Inic</div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{ asset('/js/alertas.js')}}"></script>
@endsection