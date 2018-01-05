<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class ImpuestoProducto extends Model{
    protected $table        = 'impuestos_productos_pr';
    protected $primaryKey   = ['codigo_im','detalle_pp','version_pp','codigo_po'];
    public $timestamps      = false;
    public $incrementing    = false;
}
