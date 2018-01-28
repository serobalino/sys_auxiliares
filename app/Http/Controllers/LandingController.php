<?php

namespace App\Http\Controllers;

use App\Mail\MensajeInvitado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller{
    public function index(){
        return view('landing.index');
    }

    public function enviarMail(Request $datos){

        Mail::to('odm_sd@hotmail.com')->send(new MensajeInvitado([
            'nombre'    =>  $datos->nombre,
            'correo'    =>  $datos->correo,
            'mensaje'   =>  $datos->mensaje
        ]));
        session(['email' => true]);
        return (['val'=>true]);
    }

    public function verificar(){
        $val    =   false;
        if(session()->has('email')){
            $val    =   true;
        }
        return (['val'=>$val]);
    }
}
