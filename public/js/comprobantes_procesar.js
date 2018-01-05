$( document ).ready(function() {
    lista();
});
function lista(){
    var token   =   $("input[name=_token]").val();
    var url     =   $("input[name=_url]").val();
    var total=0;
    var malos=0;
    $.ajax({
        type: "POST",
        url: url,
        headers:{
          "X-CSRF-TOKEN":token,
        },
        async:false,
        beforeSend: function( xhr ) {
            $('#lista').html('<div class="flex-column align-items-start"><div class="text-center text-primary"> <br> <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> <br></div></div>');
        },
        success: function (data) {
            var html="";
            console.log(data);
            $.each(data, function (numero,valor) {
                total='';
                contribuyente='';
                if(valor.total)
                    total="Valor: "+valor.total;
                if(valor.contribuyente!==undefined)
                    if(!valor.contribuyente.propietario){
                        contribuyente='<br>'+valor.contribuyente.nombre+'<br>'+valor.contribuyente.cod;
                        malos++;
                    }
                html+='<a href="#" class="list-group-item list-group-item-action flex-column align-items-start"><div class="d-flex w-100 justify-content-between"><h5 class="mb-1"><b>Archivo: </b>'+valor.archivo+'<br><b>Tipo de comprobante: </b>'+valor.comprobante+'</h5><small>'+valor.fecha+'</small></div><p class="mb-1"><span class="badge badge-'+valor.egreso.mensaje[1]+'">'+valor.egreso.mensaje[0]+'</span>'+contribuyente+'</p><small>'+total+'</small></a>';
                total=numero;
            });
            $('#lista').html(html);
        },
        error:function(data){
            $('#alerta').html(noguardo(0));
        },
        complete:function(valor){
            buenos=total-malos;
            if(malos==0)
                $('#alerta').html(faltancampos('Todos los comprobantes se han guardao'));
            if(total==0)
                $('#alerta').html(noguardo('No se encontró ningun comprobante'));
            if(buenos>0)
                $('#alerta').html(seguardo('Se guardaron '+buenos+' comprobantes'));
            $('#botones').removeClass('hidden-xs-up');
        }
    });
}

