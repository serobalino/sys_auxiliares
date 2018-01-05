<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model{
    protected $table        = 'egresos_facturas';
    //protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_tc'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;

    //relacion una a varios

}
