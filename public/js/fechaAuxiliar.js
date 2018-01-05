/**
 * Created by SebastiaN on 1/7/2017.
 */
var URL="";
var URLex="";

var fecha_inicio    =   "";
var fecha_fin       =   "";

var fecha_inicio2   =   "";
var fecha_fin2      =   "";


var alerta  = $('#alerta');
$('#botones').hide();
function ponerUrl(entrada,entradaexcel){
    URL=entrada;
    URLex=entradaexcel;
}
function clientesTabla(inicio,fin,tipo) {
    var id_table="#comprobantes";
    var name_table="Comprobantes";
    var token   =   $("input[name=_token]").val();
    if ($.fn.dataTable.isDataTable(id_table)){
        table = $(id_table).DataTable();
        table.destroy();
        $(id_table).html('');
        console.log('Aki borra la tabla');
        $('#botones').hide();
    }
        $(id_table).html('<thead><tr><th>#</th><th>Tipo</th><th>RUC</th><th>Empresa</th><th>Fecha</th><th>Sin Imp.</th><th>Dsc.</th><th>Propina</th><th>Inporte</th><th>Impuestos</th></tr></thead><tfoot><tr><th>#</th><th>Tipo</th><th>RUC</th><th>Empresa</th><th>Fecha</th><th>Sin Imp.</th><th>Dsc.</th><th>Propina</th><th>Inporte</th><th>Impuestos</th></tr></tfoot>');
        $(id_table+' tfoot th').each( function () {
            var title = $(this).text();
            if(title!='')
                $(this).html( '<input type="text" class="form-control" placeholder="Filtro '+title+'" />' );
        });
        var aux = $(id_table).DataTable({
            language:{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "No hay datos de "+inicio+" hasta "+fin,
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sSearch":         "Buscar:",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Ãšltimo",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            bLengthChange: false,
            ajax: {
                url: URL,
                headers:{
                    "X-CSRF-TOKEN":token,
                },
                type:'POST',
                data:{'inicio':inicio,'fin':fin,'tipo':tipo},
                dataSrc: "",
            },
            columns: [
                { data: "id_"},
                { data: "compro"},
                { data: "ruc"},
                { data: "razon"},
                { data: "emision"},
                { data: "tsi"},
                { data: "td"},
                { data: "pro"},
                { data: "inp"},
                { data: "imp"},
            ],
            select: true
        });
        aux.columns().every( function () {
            var that = this;
            $('input', this.footer()).on( 'keyup change', function () {
                if ( that.search()!== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
        $('#comprobantes_filter').hide();
        if (aux.data().any()){
            $('#botones').hide();
        }else{
            $('#botones').show();
        }
};
$('#rangoFechas').daterangepicker({
    "showISOWeekNumbers": true,
    "autoApply": true,
    "locale": {
        "format": "DD MMM YYYY",
        "separator": " al ",
        "applyLabel": "Enviar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "hasta",
        "customRangeLabel": "Custom",
        "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    },
}, function(start, end, label) {
    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') );
    fecha_inicio2   =   start.format('DD MMM YYYY');
    fecha_fin2      =   end.format('DD MMM YYYY');
    fecha_inicio    =   start.format('YYYY-MM-DD');
    fecha_fin       =   end.format('YYYY-MM-DD');
});
alerta.html(info('Elija que tipo de auxiliar quiere generar'));
$('#boton').click(function(){
    if(fecha_fin2=="" || fecha_inicio2==""){
        alerta.html(noguardo(' Debe elegir un rango de fechas'));
    }else{
        if($('#auxiliar').val()==''){
            alerta.html(noguardo(' Elija el tipo de auxiliar'));
        }else{
            var valor   =   $('select option:selected').text();
            var tipo    =   $('select option:selected').val();
            alerta.html(seguardo('Se generará el auxiliar de '+valor+' desde '+fecha_inicio2+' hasta '+fecha_fin2));
            clientesTabla(fecha_inicio,fecha_fin,tipo)
        }
    }
});
$('#excel').click(function(){
    crearExcel();
});
function crearExcel(){
    tabla = $('#comprobantes').DataTable();
    var tipo    =   $('select option:selected').val();
    var aux_url =URLex+'?inicio='+fecha_inicio+'&fin='+fecha_fin+'&tipo='+tipo;
    console.log(aux_url);
    if (!tabla.data().any()){
        alerta.html(noguardo('No hay datos por favor vuelva a intentar'));
    }else{

        window.open(aux_url, '_blank');
    }
}