<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model{
    protected $table        = 'impuestos';
    protected $primaryKey   = 'codigo_im';
    public $timestamps      = false;
    public $incrementing    = false;
}
