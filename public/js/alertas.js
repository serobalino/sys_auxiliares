function faltancampos(texto){
    var mensaje="";
    mensaje='<div class="alert alert-warning" role="alert"><strong>Faltan campos!</strong> es necesario completar todos los campos</div>';
    if(texto)
        mensaje='<div class="alert alert-warning" role="alert"><strong>Advertencia!</strong> '+texto+'</div>';
    return mensaje;
}
function noguardo(texto){
    var mensaje="";
    mensaje='<div class="alert alert-danger" role="alert"><strong>Error!</strong> '+texto+'</div>';
    if(texto==0)
        mensaje='<div class="alert alert-danger" role="alert"><strong>Error!</strong> Ha ocurrido un error vuelva a intentar</div>';
    return mensaje;
}
function seguardo(texto){
    var mensaje="";
    mensaje='<div class="alert alert-success" role="alert"><strong>Exito!</strong> '+texto+'</div>';
    return mensaje;
}
function info(texto){
    var mensaje="";
    mensaje='<div class="alert alert-info" role="alert"><strong>Información!</strong> '+texto+'</div>';
    return mensaje;
}