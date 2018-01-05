<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable{
    use Notifiable;

    protected $guard        =   "adm";

    protected $table        =   "administradores";

    const CREATED_AT        =   "fechac_ad";
    const UPDATED_AT        =   "fecha_ad";
    protected $primaryKey   =   "id_ad";
    protected $fillable     = [
        'email_ad', 'nombres_ad','id_ad'
    ];
    protected $hidden       = [
        'contrasena_ad', 'rstcontrasena_ad',
    ];


    public function getAuthIdentifier() {
        return $this->id_ad;
    }
    public function getAuthPassword() {
        return $this->contrasena_ad;
    }
    public function getRememberToken() {
        return $this->rstcontrasena_ad;
    }
    public function setRememberToken($token) {
        $this->rstcontrasena_ad = $token;
    }
    public function getRememberTokenName() {
        return 'rstcontrasena_ad';
    }
    public function getPasswordAttribute() {
        return $this->contrasena_ad;
    }
    public function setPasswordAttribute($contrasena) {
        $this->contrasena_cl = Hash::make($contrasena);
    }
}
