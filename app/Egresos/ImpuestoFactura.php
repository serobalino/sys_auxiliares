<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ImpuestoFactura extends Model{
    protected $table        = 'impuesto_facturas';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','id_cl','codigo_im','codigo_po'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
