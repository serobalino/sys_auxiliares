<?php

namespace App\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Tabla19 extends Model
{
    protected $table        =   "tabla19";
    protected $primaryKey   =   "cod_t19";
    public $timestamps      =   false;

    protected $appends      =   ['label'];

    public function getLabelAttribute()
    {
        return $this->attributes['detalle_t19'];
    }
}
