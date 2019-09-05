<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $primaryKey   =   "id_tc";
    public $timestamps      =   false;
    protected $table        =   "tipos_comprobante";
}
