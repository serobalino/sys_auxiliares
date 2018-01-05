$( document ).ready(function() {
    clientesTabla();
    formulario_envio();
});
function formulario_envio(){
    var formulario = $('#clientes-form');
    formulario.submit(function(e) {
        if (e.isDefaultPrevented()) {
            $('#alerta-cliente').html(faltancampos());
        }else{
            console.log('formulario lleno');
            enviar_cliente(formulario.serialize());
        }
        e.preventDefault();
    });
}
function enviar_cliente(frm){
    $.ajax({
        type: "POST",
        url: "../apiadm/clientes",
        data: frm,
        success: function (data) {
            console.log(data);
            if(data.return) {
                $('#alerta-cliente').html(seguardo(data.mensaje));
                $('#clientes-form').trigger("reset");
                clientesTabla();
            }else
                $('#alerta-cliente').html(noguardo(data.mensaje));
        },
        error:function(data){
            $('#alerta-cliente').html(noguardo(0));
        }
    });
}
function elegir_cliente(id){
    $.ajax({
        type: "GET",
        url: "../apiadm/clientes/"+id,
        success: function (data) {
            console.log(data);
            if(data.return) {
                window.location=data.ruta;
            }else
                console.log('Error en el resultado');
        },
        error:function(data){
            console.log('Error en la peticion');
        }
    });
}