<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model{
    protected $table        = 'detalle_facturas';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_pp','version_pp'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
