<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ImpuestoCredito extends Model{
    protected $table        = 'impuestos_descuentos_pr';
    protected $primaryKey   = ['codigo_im','detalle_dp','version_pd','codigo_po'];
    public $timestamps      = false;
    public $incrementing    = false;
}
