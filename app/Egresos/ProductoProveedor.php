<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model{
    protected $table        = 'productos_proveedores';
    protected $primaryKey   = ['detalle_pp','version_pp'];
    public $timestamps      = false;
    public $incrementing    = false;
}
