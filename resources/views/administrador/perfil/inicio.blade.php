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
            <div class="sidebar-wrapper animated fadeIn">
                <ul class="nav">
                    <li class="active">
                        <a href="#actual">
                            <i class="material-icons">dashboard</i>
                            <p>Perfil</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="material-icons">call_made</i>
                            <p>Gastos</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="material-icons">call_received</i>
                            <p>Ventas</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="material-icons">credit_card</i>
                            <p>Información</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel" >
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Menu</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{route('adm.inicio')}}"> <u class="text-primary">Clientes</u> </a>
                        <a class="navbar-brand" href="#actual"> {{$razon_cl}} </a>
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
                            <avatar-session url="{{route('session.adm')}}"/>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid animated fadeInDown">
                    <noticias/>
                </div>
            </div>
            <modalsesion url="{{route('session.adm')}}"/>
            <pie/>
        </div>
    </div>
@endsection