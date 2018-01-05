<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class DebitoProveedor extends Model{
    protected $table        = 'motivo_debito';
    protected $primaryKey   = ['descripcion_mc','version_mc'];
    public $timestamps      = false;
    public $incrementing    = false;
}
