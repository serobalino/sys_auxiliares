@extends('basico.base')
@section('titulo') Subir comprobantes @endsection
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
            <a class="nav-link" href="#">Subir Comprobantes</a>
        </li>
    </ul>
@endsection
@section('cuerpo')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-3">Subir comprobantes de {{$razon_cl}}</h1>
            <p class="lead">Examine los comprobantes del contribuyente en xml</p>
            <hr class="my-4">
            <p>Recuerde que debe ser en formato XML</p>
            <div id="alerta"></div>
            {!! Form::open(['action'=> 'Electronicas\SubirController@store', 'method' => 'POST', 'files'=>'true', 'id' => 'my-dropzone' , 'class' => 'dropzone']) !!}

            <button type="submit" class="btn btn-success" id="submit" style="cursor:pointer;"><span class="fa fa-save"></span> Guardar</button>
            {!! Form::close() !!}
        </div>
        <div class="hidden-xl-down">
            <div class="list-group" class="files" id="previews">

                <div id="template" class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1" data-dz-name></h5>
                        <div class="dz-success-mark"><span></span></div>
                        <div class="dz-error-mark"><span></span></div>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                        <small class="text-muted" data-dz-size></small>
                    </div>
                    <p class="mb-1" >
                    <div class="progress" style="width:100%">
                        <div class="progress-bar" role="progressbar" style="height: 1px;"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>

                    </p>
                    <small>
                        <button data-dz-remove class="btn btn-danger delete btn-sm" style="cursor:pointer;">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </button>
                    </small>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {!! Html::script('/lib/dropzone/js/dropzone.min.js'); !!}
    <script>
            Dropzone.options.myDropzone = {
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: 1000,
            acceptedFiles: ".xml",
            dictDefaultMessage:'<span class="btn-link"> Examinar</span>',
            previewTemplate: document.getElementById('previews').innerHTML,

            init: function() {
                var submitBtn = document.querySelector("#submit");
                myDropzone = this;
                submitBtn.addEventListener("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });
                var a=0;
                this.on("complete", function(file) {
                    a++;
                    if(a!=0)
                        $("#alerta").html('<div class="alert alert-info" role="alert"> <h4 class="alert-heading">Procesando '+a+' archivos</h4> <div class="progress"> <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div> </div></div>');
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        b=JSON.parse(file.xhr.response);
                        completado(a,b);
                    }
                });
                this.on("success",
                    myDropzone.processQueue.bind(myDropzone)
                );
                this.on("addedfile", function(file) {
                    //agregar archivo
                });
            }
        };
            function completado(a,b){
                $("#alerta").html('<div class="alert alert-success" role="alert"> <h4 class="alert-heading">Se ha guardadó '+a+' archivos </h4></div>');
                console.log(b);
                if(b.return){
                    window.location=b.url;
                }
            }
    </script>
@endsection