<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class PorcentajeRetencion extends Model{
    protected $table        = 'porcentaje_retencion';
    protected $primaryKey   = 'codigo_pr';
    public $timestamps      = false;
    //public $incrementing    = true;
}
