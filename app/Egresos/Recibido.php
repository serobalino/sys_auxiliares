<?php

namespace App\Egresos;

use Illuminate\Database\Eloquent\Model;

class Recibido extends Model{
    protected $table        = 'egresos';
    protected $primaryKey   = ['secuencial_eg','pemision_ac','codigo_su','ruc_pr'];
    public $timestamps      = false;
    public $incrementing    = false;


    /*
     * return $this->belongsToMany('\App\Menu','menu_task_user')
            ->withPivot('task_id','status');
     *
     * */
    public function facturas(){
        return $this->belongsToMany(Factura::class,$this->table,'secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_tc','secuencial_eg','pemision_ac','codigo_su','ruc_pr','codigo_tc')
                    ;
    }
}
