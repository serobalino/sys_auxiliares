<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class Ncredito extends Model{
    protected $table        = 'egresos_notasc';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;

    //relacion una a varios
    public function detalles(){
        return $this->hasMany('App\Egresos\DetallesNcredito');
    }
}
