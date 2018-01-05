<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class NcreditoAuxiliar extends Model{
    protected $table        = 'ncredito_auxiliar';
    protected $primaryKey   = ['id_cl','version_dp','detalle_dp','id_tg','id_ad'];
    public $timestamps      = false;
    public $incrementing    = false;
}
