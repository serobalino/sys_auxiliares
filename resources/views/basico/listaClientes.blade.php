@extends('basico.base')
@section('titulo') Lista de Clientes @endsection
@section('cuerpo')
    <div class="jumbotron text-center" style="background-color: #e3f2fd;">
        <h1 class="display-3">Clientes</h1>
        <p class="lead">Se listan todos los clientes registrados en el sistema.</p>
        <hr class="my-4">
        <p>Puede agregar clientes al sistema</p>
        <p class="lead">
            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#new-cliente" role="button"><span class="fa fa-user-plus"></span> Agregar cliente</button>
        </p>
    </div>
    <dic class="container">
        <table id="table-clientes" class="table table-bordered table-hover" cellspacing="0" style="cursor:pointer">
        </table>
    </dic>
    <div class="modal fade" id="new-cliente" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alerta-cliente">

                    </div>
                    <form id="clientes-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">RUC</label>
                            <input type="text" class="form-control" name="ruc" placeholder="1234567890123" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" placeholder="Robalino Altamirano" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nombres</label>
                            <input type="text" class="form-control" name="nombres" placeholder="Fany Robalino" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Empresa</label>
                            <input type="text" class="form-control" name="rsocial" placeholder="ASECONT-PUYO" autocomplete="off" required>
                        </div>
                        <button type="submit" class="btn btn-success"><span class="fa fa-save"></span> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ asset('/js/alertas.js')}}"></script>
<script src="{{ asset('/js/cargarTablas.js')}}"></script>
<script src="{{ asset('/js/clientes-index.js')}}"></script>
@endsection