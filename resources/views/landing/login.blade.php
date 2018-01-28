@extends('landing.base')
@section('body','signup-page')
@section('cuerpo')
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">{{config('app.name')}}</a>
            </div>
            <div class="collapse navbar-collapse" id="navigation-example">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="https://www.facebook.com/ASECONT.PUYO/" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper" id="invitado">
        <div class="header header-filter" style="background-image: url('https://source.unsplash.com/random'); background-size: cover; background-position: top center;">
            <div class="container">
                <div class="row">
                    <login-comun url="{{route('comun.login.submit')}}"/>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <div class="copyright pull-right">
                        &copy; {{date('Y')}}, made with <i class="fa fa-heart heart"></i> by GoldenBytes
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection