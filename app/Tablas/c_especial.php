<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class c_especial extends Model{
    protected $table        = 'resolucion_contribuyente_especial';
    protected $primaryKey   = 'codigo_rc';
    public $timestamps      = false;
    public $incrementing    = false;
}
