<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Tabla21 extends Model
{
    protected $table        =   "tabla21";
    protected $primaryKey   =   "cod_t21";
    public $timestamps      =   false;

    protected $appends      =   ['label'];

    public function getLabelAttribute()
    {
        //return "Retención de ".$this->attributes['porcentaje_t21']."%";
        return $this->attributes['detalle_t21'];
    }
}
