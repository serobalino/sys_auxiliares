<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class CreditoProveedor extends Model{
    protected $table        = 'descuentos_proveedores';
    protected $primaryKey   = ['detalle_dp','version_dp'];
    public $timestamps      = false;
    public $incrementing    = false;
}
