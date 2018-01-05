<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class Porcentaje extends Model{
    protected $table        = 'porcentajes';
    protected $primaryKey   = 'codigo_po';
    public $timestamps      = false;
    public $incrementing    = false;
}
