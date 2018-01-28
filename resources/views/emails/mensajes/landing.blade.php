@component('mail::message')
# Nuevo Mensaje

Ha escrito un mensaje
<br>
Nombre:<strong>{{$nombre}}</strong>
<br>
Correo:{{$correo}}
<br>
{{$mensaje}}
<br><br><br>
Recuerda que es un mensaje automatizado,<br>
{{ config('app.name') }}
@endcomponent
