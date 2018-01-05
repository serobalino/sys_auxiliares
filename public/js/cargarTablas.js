function clientesTabla() {
    var id_table="#table-clientes";
    var name_table="Clientes";
    if ($.fn.dataTable.isDataTable(id_table)){
        table = $(id_table).DataTable();
        table.ajax.reload();
        console.log('Recargado '+name_table);
    }else{
        $(id_table).html('<thead><tr><th>RUC</th><th>EMPRESA</th><th>NOMBRE</th></tr></thead><tfoot><tr><th>RUC</th><th>EMPRESA</th><th>NOMBRE</th></tr></tfoot>');
        $(id_table+' tfoot th').each( function () {
            var title = $(this).text();
            if(title!='')
                $(this).html( '<input type="text" class="form-control" placeholder="Filtro '+title+'" />' );
        });
        var aux = $(id_table).DataTable({
            language:{
                "sProcessing":     "Procesando...",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "NingÃºn dato disponible en esta tabla",
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
                url: "../apiadm/clientes",
                dataSrc: ""
            },
            columns: [
                { data: "ruc_cl"},
                { data: "razon_cl"},
                { data: "apellidos_cl"},
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
        $(id_table+' tbody').on( 'click', 'tr', function () {
            aux2 = $(id_table).DataTable();
            var id_aux=(aux2.row(this).data());
            elegir_cliente(id_aux.id_cl);
        });
        $('#table-clientes_filter').hide();
    }
};