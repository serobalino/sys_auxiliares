<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MensajeInvitado extends Mailable
{
    use Queueable, SerializesModels;

    protected $datos;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos){
        $this->datos=$datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        return $this->subject('☝️ Nuevo Interesado')
            ->markdown('emails.mensajes.landing')
            ->with([
                'nombre'    =>  $this->datos['nombre'],
                'correo'    =>  $this->datos['correo'],
                'mensaje'   =>  $this->datos['mensaje'],
            ]);
    }
}
