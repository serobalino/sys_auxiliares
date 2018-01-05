<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class Ndebito extends Model{
    protected $table        = 'egresos_notasd';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr'];
    protected $hidden       = ['codigo_tc'];
    public $timestamps      = false;
    public $incrementing    = false;

    public function detalles(){
        return $this->hasMany('App\Egresos\DetallesNdebito');
    }
}
