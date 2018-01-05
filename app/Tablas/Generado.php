<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class Generado extends Model{
    protected $table        = 'tipo_generados';
    protected $primaryKey   = 'id_tg';
    public $timestamps      = false;
    public $incrementing    = false;
}
