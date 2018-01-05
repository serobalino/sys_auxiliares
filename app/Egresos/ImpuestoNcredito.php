<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ImpuestoNcredito extends Model{
    protected $table        = 'impuesto_notasc';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_im','codigo_po'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
