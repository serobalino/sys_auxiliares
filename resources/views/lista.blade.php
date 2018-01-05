@extends('welcome')
@section('css')
    <link href="{{ asset('/css/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('cuerpo')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-3">Lista de Facturas Subidas</h1>
            <hr class="my-4">
            <p>Recuerde que debe ser en formato XML</p>
            <p class="lead">
                Se listan todas las facturas del cliente Bla que se subieron
            </p>
        </div>
            <div class="list-group facturas" >
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small>3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small>Donec id elit non mi porta.</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small class="text-muted">Donec id elit non mi porta.</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small class="text-muted">Donec id elit non mi porta.</small>
                </a>
            </div>
    </div>
@endsection
@section('scripts')
    <script>
        function funcioncargar(){
            $.ajax({
                url: "./api/facturas",
                method:"GET",
                beforeSend: function(xhr) {
                    $('.facturas').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>');
                },
                success: function(datos) {
                    alert( "Funcionó" );
                },
                error: function() {
                    alert( "Ha ocurrido un error" );
                }
            }).done(function(datos) {

            }).fail(function(data) {
                $('.facturas').html('<div class="text-center">Error al cargar</div>');
            });
        }
    </script>
@endsection