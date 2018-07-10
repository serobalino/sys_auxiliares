@extends('plantillas.invitado')
@section('titulo') Recuperar cuenta @endsection
@section('cuerpo')
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--forget-password m-login--2 m-login-2--skin-2" >
            <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
                <div class="m-login__container">
                    @component('componentes.invitado.logo')
                    @endcomponent
                    @component('componentes.invitado.form_cambiar')
                            @slot('token',$token)
                    @endcomponent
                    @component('componentes.invitado.pie')
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection