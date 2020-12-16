<?php

namespace App\Clientes;

use App\Catalogos\Claves;
use Illuminate\Database\Eloquent\Model;

class Contrasena extends Model
{
    protected $primaryKey   =   null;
    public $incrementing    =   false;
    protected $table        =   "historial_claves";

    protected $casts        = [
        'estado_hc' => 'boolean',
    ];

    public function label(){
        return $this->hasOne(Claves::class,'id_tc','id_tc');
    }
}
