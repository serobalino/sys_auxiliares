<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Tabla18 extends Model
{
    protected $table        =   "tabla18";
    protected $primaryKey   =   "cod_t18";
    public $timestamps      =   false;

    protected $appends      =   ['label'];

    public function getLabelAttribute()
    {
        return $this->attributes['detalle_t18'];
    }
}
