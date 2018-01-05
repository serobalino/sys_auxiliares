@extends('basico.base')
@section('titulo') {{$apellidos_cl}} {{$nombres_cl}} @endsection
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
            <a class="nav-link" href="#">Clasificar comprobantes</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="jumbotron text-center" style="background-color: #e3f2fd;">
        <h1 class="display-3">{{$razon_cl}}</h1>
        <p class="lead">{{$apellidos_cl}} {{$nombres_cl}}<br>{{$ruc_cl}}</p>
    </div>
    <div class="container">
        {{--}}
        <div class="alert alert-warning animated bounceIn collapse show" role="alert" id="producto" aria-expanded="true">
            <h4 class="alert-heading"><span class="fa fa-info-circle"></span> Hay productos que aun no están clasificados para generar el auxiliar ({{$numero}})</h4>
                <div class="row">
                    <div class="col-6">
                        <p>Para <b>{{$razon_cl}}</b> para que auxiliar le sirve el producto de</p>
                        <p>Nombre Comercial: <b>{{$detalle->nombrec_pr}}</b><br>Razón Social: <b>{{$detalle->razons_pr}}</b></p>
                        <ul>
                            <li><strong>{{$detalle->detalle_pp}}</strong> de <span class="badge badge-default" style="font-size: large">{{round($detalle->punitario_pp*$detalle->cantidad_df,2)}}</span></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <p>Impuestos</p>
                        <ul>
                            @foreach ($impuestos as $impuesto)
                                <li><b>{{$impuesto->detalle}} {{(int)$impuesto->porcentaje}}%</b> <span class="badge badge-default" style="font-size: large">{{round($impuesto->valor, 2)}}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {!! Form::open(['action'=> 'ComprobarComprobantesController@guardar', 'method' => 'POST']) !!}
                <p class="text-center">
                    <input type="hidden" name="version" value="{{$detalle->version_pp}}"/>
                    <input type="hidden" name="detalle" value="{{$detalle->detalle_pp}}"/>
                    <input type="hidden" name="tc"      value="{{$detalle->codigo_tc}}"/>
                </p>
                <div class="center-block">
                    <div class="row">
                        <div class="btn-group-vertical col-6" role="group" aria-label="iva">
                            <button type="button" class="btn btn-outline-info btn-lg active" >IVA</button>
                            @foreach ($botones_i as $iva)
                                <button type="submit" class="btn btn-outline-info btn-lg" value="{{$iva->id_tg}}" name="auxiliar">{{$iva->label_tg}}</button>
                            @endforeach
                        </div>
                        <div class="btn-group-vertical col-6" role="group" aria-label="renta">
                            <button type="button" class="btn btn-outline-primary btn-lg active" >RENTA</button>
                            @foreach ($botones_r as $renta)
                                <button type="submit" class="btn btn-outline-primary btn-lg" value="{{$renta->id_tg}}" name="auxiliar">{{$renta->label_tg}}</button>
                            @endforeach
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <button class="btn btn-success btn-lg btn-block" type="button" data-toggle="collapse" data-target="#proveedor" aria-expanded="false" id="regProveedor">Registrar por Proveedor</button>
                        <button class="btn btn-danger btn-lg btn-block" type="submit" name="auxiliar" value="false"><span class="fa fa-times"></span> No registrar</button>
                    </div>
                </div>
                {!! Form::close() !!}
        </div>
        {{--}}

        <div class="alert alert-danger animated bounceIn collapse" role="alert" id="proveedor" aria-expanded="true">
            <h4 class="alert-heading"><span class="fa fa-info-circle"></span> Clasificar por Proveedor</h4>
            <div class="row">
                <p>Para <b>{{$razon_cl}}</b> para que auxiliar le sirve todos los productos de</p>
                <h3>Nombre Comercial: <b>{{$detalle->nombrec_pr}}</b><br>Razón Social: <b>{{$detalle->razons_pr}}</b></h3>
            </div>
            {!! Form::open(['action'=> 'ComprobarComprobantesController@guardarProv', 'method' => 'POST']) !!}
            <p class="text-center">
                <input type="hidden" name="ruc"     value="{{$detalle->ruc_pr}}"/>
            </p>
            <div class="center-block">
                <div class="row">
                    <div class="btn-group-vertical col-6" role="group" aria-label="iva">
                        <button type="button" class="btn btn-outline-info btn-lg active" >IVA</button>
                        @foreach ($botones_i as $iva)
                            <button type="submit" class="btn btn-outline-info btn-lg" value="{{$iva->id_tg}}" name="auxiliar">{{$iva->label_tg}}</button>
                        @endforeach
                    </div>
                    <div class="btn-group-vertical col-6" role="group" aria-label="renta">
                        <button type="button" class="btn btn-outline-primary btn-lg active" >RENTA</button>
                        @foreach ($botones_r as $renta)
                            <button type="submit" class="btn btn-outline-primary btn-lg" value="{{$renta->id_tg}}" name="auxiliar">{{$renta->label_tg}}</button>
                        @endforeach
                    </div>
                </div>
                <br>
                <div class="row">
                    <button class="btn btn-success btn-lg btn-block" type="button" data-toggle="collapse" data-target="#proveedor" aria-expanded="false" id="regProducto">Registrar por Producto</button>
                    <button class="btn btn-danger btn-lg btn-block"><span class="fa fa-times"></span> No registrar a {{$detalle->nombrec_pr}}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('/js/alertas.js')}}"></script>
    <script>
        /*
        $('#regProveedor').click(function(){
            $('#proveedor').collapse('show');
            $('#producto').collapse('hide');
        });
        $('#regProducto').click(function(){
            $('#producto').collapse('show');
            $('#proveedor').collapse('hide');
        });*/
        $('#proveedor').collapse('show');
        $('#producto').collapse('hide');

    </script>
@endsection