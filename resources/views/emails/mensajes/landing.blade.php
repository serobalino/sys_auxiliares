@component('mail::message')
# Nuevo Mensaje

Ha escrito un mensaje
<br>
Nombre:<b>{{$nombre}}</b>
<br>
Correo:<b>{{$correo}}</b>
<br>
<k>{{$mensaje}}</k>
<br><br><br>
Recuerda que es un mensaje automatizado,<br>
{{ config('app.name') }}
@endcomponent
