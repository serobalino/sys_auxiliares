<?php

namespace App\Http\Controllers;

use App\Catalogos\Claves;
use App\Catalogos\Comprobante;
use Illuminate\Http\Request;

class CatalogosController extends Controller
{
    public function indexComprobantes(){
        return Comprobante::all();
    }

    public function indexClave(){
        return Claves::all();
    }
}
