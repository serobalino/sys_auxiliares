@extends('welcome')
@section('css')
    <link href="{{ asset('/lib/dropzone/css/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('cuerpo')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-3">Subir facturas</h1>
            <p class="lead">Examine las facturas del contribuyente en xml</p>
            <hr class="my-4">
            <p>Recuerde que debe ser en formato XML</p>
            <p class="lead">
            {!! Form::open(['action'=> 'ArchivosController@subirArchivos', 'method' => 'POST', 'files'=>'true', 'id' => 'my-dropzone' , 'class' => 'dropzone']) !!}
            <div class="dz-message" style="height:200px;">
                Da clic o arrastra los archivos aki
            </div>
            <div class="dropzone-previews"></div>
            <button type="submit" class="btn btn-success" id="submit">Guardar</button>
            {!! Form::close() !!}

            </p>
        </div>
    </div>
@endsection
@section('scripts')
    {!! Html::script('/lib/dropzone/dropzone.min.js'); !!}
    <script>
            Dropzone.options.myDropzone = {
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: 1000,
            addRemoveLinks:true,
            acceptedFiles: ".xml",

            init: function() {
                var submitBtn = document.querySelector("#submit");
                myDropzone = this;
                submitBtn.addEventListener("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });
                this.on("complete", function(file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        completado();
                    }
                });
                this.on("success",
                    myDropzone.processQueue.bind(myDropzone)
                );
            }
        };
            function completado(){
                console.log("Completo");
            }
    </script>
@endsection