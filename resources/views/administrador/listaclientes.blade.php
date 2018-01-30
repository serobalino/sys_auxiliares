@extends('administrador.base')
@section('body','landing-page')
@section('cuerpo')
<div class="wrapper">
    <div class="sidebar" data-color="blue" data-image="{{asset('img/bg3.jpeg')}}">
        <div class="logo">
            <a href="{{route('index')}}" class="simple-text">
                {{ config('app.name') }}
            </a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li>
                    <a href="#">
                        <i class="material-icons">dashboard</i>
                        <p>Inicio</p>
                    </a>
                </li>
                <li class="active">
                    <a href="#actual">
                        <i class="material-icons">content_paste</i>
                        <p>Clientes</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"> Clientes </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">notifications</i>
                                <span class="notification">1</span>
                                <p class="hidden-lg hidden-md">Notificaciones</p>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">Notificacion 1</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">person</i>
                                <p class="hidden-lg hidden-md">Perfil</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content" id="adm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" data-background-color="blue">
                                <h4 class="title">Clientes Registrados</h4>
                                <div class="category">
                                    Para agregar un nuevo cliente <button class="btn btn-primary btn-sm btn-round" onclick="$('#nuevo').modal('show')" ><span class="fa fa-plus"></span> Agregar</button>
                                </div>
                            </div>
                            <div class="card-content table-responsive">
                                <tabla-clientes/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modal-registro/>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; {{date('Y')}}, made with <i class="fa fa-heart heart"></i> by GoldenBytes
                </p>
            </div>
        </footer>
    </div>
</div>
@endsection