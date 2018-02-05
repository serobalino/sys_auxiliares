<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doc_cliente extends Model{
    protected $table        = 'documentos_clientes';
    protected $primaryKey   = ['codigo_td','id_cl'];
    public $timestamps      = false;
    public $incrementing    = false;

    public function es(){
        return $this->hasOne('App\Cliente','id_cl','id_cl');
    }
}
