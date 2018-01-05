<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class FacturaAuxiliar extends Model{
    protected $table        = 'facturas_auxiliar';
    protected $primaryKey   = ['id_cl','version_pp','detalle_pp','id_tg','id_ad'];
    public $timestamps      = false;
    public $incrementing    = false;
}
