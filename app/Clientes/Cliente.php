<?php

namespace App\Clientes;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $primaryKey   =   "id_cl";

    protected $appends = ['codigo'];

    public function getCodigoAttribute(){
        return md5(sha1($this->attributes['id_cl']));
    }

    public function contrasenas(){
        return $this->hasMany(Contrasena::class,'id_cl','id_cl')->where('estado_hc',true);
    }

}
