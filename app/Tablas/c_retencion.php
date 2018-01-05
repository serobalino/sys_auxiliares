<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class c_retencion extends Model{
    protected $table        = 'renta';
    protected $primaryKey   = 'codigo_re';
    public $timestamps      = false;
    public $incrementing    = false;
}
