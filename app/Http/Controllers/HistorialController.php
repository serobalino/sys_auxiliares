<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Historial;
use App\User;
use Illuminate\Http\Request;


class HistorialController extends Controller
{
    /**
     * @param User $usuario
     * @param Cliente $cliente
     * @param string $texto
     * @param Request $solicitud
     */
    public function log(User $usuario,Cliente $cliente,string $texto,Request $solicitud){
        $nuevo                  =   new Historial();
        $nuevo->solicitud_hi    =   $solicitud;
        $nuevo->us_hi           =   $usuario->id;
        $nuevo->cl_hi           =   $cliente->id_cl;
        $nuevo->tipo_hi         =   $texto;
        $nuevo->save();
    }
}
