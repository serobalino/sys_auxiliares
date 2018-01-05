<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable{
    use Notifiable;

    protected $guard        =   "cli";

    protected $table        =   "clientes";

    const CREATED_AT        =   "fechac_cl";
    const UPDATED_AT        =   "fecha_cl";
    protected $primaryKey   =   "id_cl";
    protected $fillable     = [
        'nombre_cl', 'apellidos_cl', 'email_cl','usuario_cl','ruc_cl','id_cl'
    ];
    protected $hidden       = [
        'contrasena_cl', 'rstcontrasena_cl',
    ];


    public function getAuthIdentifier() {
        return $this->id_cl;
    }
    public function getAuthPassword() {
        return $this->contrasena_cl;
    }
    public function getRememberToken() {
        return $this->rstcontrasena_cl;
    }
    public function setRememberToken($token) {
        $this->rstcontrasena_cl = $token;
    }
    public function getRememberTokenName() {
        return 'rstcontrasena_cl';
    }
    public function getPasswordAttribute() {
        return $this->contrasena_cl;
    }
    public function setPasswordAttribute($contrasena) {
        $this->contrasena_cl = Hash::make($contrasena);
    }
}
