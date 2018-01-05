<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemision extends Model{
    protected $table        = 'actividades';
    protected $primaryKey   = 'pemision_ac';
    public $incrementing    = false;
    public $timestamps      = false;
}
