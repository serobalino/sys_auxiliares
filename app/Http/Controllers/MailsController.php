<?php

namespace App\Http\Controllers;

use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailsController extends Controller{
    public function enviar(){
        Mail::send('emails.message', function($message)
        {
            //remitente
            $message->from('adivinasd@gmail.com','Sebastian');

            //asunto
            $message->subject('asunto del email');

            //receptor
            $message->to(env('CONTACT_MAIL'), env('CONTACT_NAME'));

        });
    }
    public function enviar1(){
        Mail::raw('Mail enviado desde lavel a pelo', function($message) {
            $message->from('sistema@asecont-puyo.com','ASECONT-PUYO');
            $message->subject('PRueba de mailing');
            $message->to('odm_sd@hotmail.com');
        });
    }
    public function enviar2(){
        Mailgun::raw("Enviado desde mailgun clase",function ($message){
            $message->from('sistema@asecont-puyo.com','ASECONT-PUYO');
            $message->subject('PRueba de mailing');
            $message->to('odm_sd@hotmail.com');
        });
    }
}
