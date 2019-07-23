<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $primaryKey   =   "id_cl";

    protected $appends = ['codigo'];

    public function getCodigoAttribute(){
        return md5(sha1($this->attributes['id_cl']));
    }

}
