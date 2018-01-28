<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller{
    public function index(){
        return view('landing.index');
    }

    public function enviarMail(Request $datos){
        session(['email' => $datos]);
        return $datos;
    }

    public function verificar(){
        $aux    =   session(['intentos' => $a]);
        return ([])
    }
}
