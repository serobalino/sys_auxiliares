<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model{
    protected $table        = 'sucursales';
    protected $primaryKey   = 'codigo_su';
    public $incrementing    = false;
    public $timestamps      = false;
}
