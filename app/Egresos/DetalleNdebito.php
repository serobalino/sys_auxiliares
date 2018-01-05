<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class DetalleNdebito extends Model{
    protected $table        = 'motivos_nota_debito';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','descripcion_mc','version_mc'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
