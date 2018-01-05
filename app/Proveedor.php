<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model{
    protected $table        = 'proveedores';
    protected $primaryKey   = 'ruc_pr';
    public $incrementing    = false;
    public $timestamps      = false;
}
