<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class DetalleRetencion extends Model{
    protected $table        = 'impuestos_retenciones';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_tc','codigo_im','codigo_pr','codigo_se'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
