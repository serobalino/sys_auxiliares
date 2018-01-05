<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class t_comprobante extends Model
{
    protected $table        = 'tipo_comprobante';
    protected $primaryKey   = 'codigo_tc';
    public $timestamps      = false;
    public $incrementing    = false;

    protected $hidden       = [
        'tegresos_tc', 'tingresos_tc',
    ];
}
