<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ImpuestoNdebito extends Model{
    protected $table        = 'impuesto_notasd';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_im','codigo_po'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
