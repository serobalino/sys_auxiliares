@extends('basico.base')
@section('titulo') {{$usuario->apellidos_cl}} {{$usuario->nombres_cl}} @endsection
@section('path')
    <ul class="navbar-nav mr-auto">
        <span class="navbar-text">/</span>
        <li class="nav-item">
            <a class="nav-link" href="{{route('adm.inicio')}}">Clientes</a>
        </li>
        <span class="navbar-text">/</span>
        <li class="nav-item active">
            <a class="nav-link" href="#">{{$usuario->razon_cl}}</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="jumbotron text-center" style="background-color: #e3f2fd;">
        <h1 class="display-3">{{$usuario->apellidos_cl}} {{$usuario->nombres_cl}}</h1>
        <p class="lead">{{$usuario->ruc_cl}}</p>
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
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.sub.ele',$usuario->ruc_cl)}}">Comprobantes Electrónicos</a>
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
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.aux',$usuario->ruc_cl)}}">Auxiliar de egresos</a>
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
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per.con',$usuario->ruc_cl)}}">Contraseñas</a>
                                <a class="list-group-item list-group-item-action active" href="#documentos">Documentos registrados</a>
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per',$usuario->ruc_cl)}}">Informacion de contacto</a>
                                <a class="list-group-item list-group-item-action" href="{{route('adm.cli.per.ban',$usuario->ruc_cl)}}">Cta Bancarias</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="col-sm-9 col-md-10 card">
                <div class="col-md-6 mx-auto">
                    <div class="jumbotron jumbotron-fluid">
                        <div class="container">
                            <h1 class="display-3">Documentos registrados</h1>
                            <p class="lead">Solo se admitiran comprobantes que cosnten en esta lista</p>
                        </div>
                    </div>
                <div class="row">
                    <form class="form-inline" action="{{route('adm.cli.per.doc.post',$usuario->ruc_cl)}}" method="post">
                        {{ csrf_field() }}
                        <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="codigo">
                            <option selected disabled >Elija </option>
                            @foreach($puede as $lis)
                                <option value="{{$lis->codigo_td}}">{{$lis->descripcion_td}}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="comp" placeholder="16000164578">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                <div class="row">
                    <div class="list-group">
                        @foreach($tiene as $tie)
                            <a href="#" class="list-group-item list-group-item-action"><b>{{$tie->descripcion_td}}</b> {{$tie->num_dc}}</a>
                        @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{ asset('/js/alertas.js')}}"></script>
@endsection