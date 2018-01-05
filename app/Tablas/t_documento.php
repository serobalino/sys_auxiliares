<?php

namespace App\Tablas;

use Illuminate\Database\Eloquent\Model;

class t_documento extends Model{
    protected $table        = 'tipo_documento';
    protected $primaryKey   = 'codigo_td';
    public $timestamps      =  false;
    public $incrementing    = false;


}
