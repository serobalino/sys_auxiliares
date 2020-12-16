<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Claves extends Model
{
    protected $primaryKey   =   "id_tc";
    public $timestamps      =   false;
    protected $table        =   "tipo_clave";
}
