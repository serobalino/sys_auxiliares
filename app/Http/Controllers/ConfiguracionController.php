<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ConfiguracionController extends Controller
{
    public function index(){
        $valores    =   [
            'nombre'    =>  config('app.name'),
            'sistema'   => $this->estadoBase(),
            'menu'      => $this->menu(),
            'servicios' => $this->servicios(),
        ];
        return($valores);
    }

    private function estadoBase(){
        try {
            DB::connection()->getPdo();
            $a=true;
        } catch (\Exception $e) {
            $a=false;
        }
        return($a);
    }

    private function menu(){
        $menu   =   Lang::get('system.menu');

        $order   = array("\r\n","\n","\r"," ","#");
        $replace = '';

        foreach ($menu as &$item) {
            $completo[]=[
                'titulo'    =>  $item,
                'link'      =>  strtolower(str_replace($order, $replace,$item)),
            ];
        }
        return ($completo);
    }

    private function servicios(){
        $a          =   $this->menu();
        $item[]     =   [
            'icon'  =>  'fa fa-bath',
            'title' =>  'Servicios bla',
            'des'   =>  'Descripcion asdasdasdasd'
        ];
        $item[]     =   [
            'icon'  =>  'fa fa-bath',
            'title' =>  'Servicios bla 2',
            'des'   =>  'Descripcion asdasdasdasd'
        ];
        $item[]     =   [
            'icon'  =>  'fa fa-bath',
            'title' =>  'Servicios bla 3',
            'des'   =>  'Descripcion asdasdasdasd'
        ];
        $servicios[]=[
            'items' =>  $item,
            'des'   =>  'Descripcion de servicios',
        ];

        return($servicios);
    }
}
