<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Tabla20 extends Model
{
    protected $table        =   "tabla20";
    protected $primaryKey   =   "cod_t20";
    public $timestamps      =   false;

    protected $appends      =   ['label'];

    public function getLabelAttribute()
    {
        return "Retención de ".$this->attributes['porcentaje_t20']."%";
    }
}
