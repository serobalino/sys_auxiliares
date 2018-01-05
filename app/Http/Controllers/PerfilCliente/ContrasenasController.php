<?php

namespace App\Http\Controllers\PerfilCliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContrasenasController extends Controller{
    function index(){
        $a  =   session()->get('cliente');
        return view('basico.AdmCliente.contrasenaCliente',$a);
    }
}
