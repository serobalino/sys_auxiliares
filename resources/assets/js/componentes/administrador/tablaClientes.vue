<template>
        <vue-good-table
                :columns="columnas"
                :rows="lista"
                :paginate="true"
                :lineNumbers="true"
                :onClick="elegir"
        />
</template>
<script>
    import VueGoodTable from 'vue-good-table';
    Vue.use(VueGoodTable);
    export default {
        name: "tabla-clientes",
        data:()=>({
            columnas: [
                {
                    label: 'RUC',
                    field: 'ruc_cl',
                    filterable: true,
                    type: 'number',
                },
                {
                    label: 'Apellidos',
                    field: 'apellidos_cl',
                    filterable: true,
                },
                {
                    label: 'Nombres',
                    field: 'nombres_cl',
                    filterable: true,
                },
                {
                    label: 'Razon Social',
                    field: 'razon_cl',
                    filterable: true,
                }
            ],
            lista: []
        }),
        methods:{
            cargar:function(){
                axios.get('/apiadm/clientes')
                    .then((response) => {
                    this.lista=response.data;
                    }).catch((error) => {
                    error.log(error);
                    });
            },
            elegir:function(row,index){
                axios.get('/apiadm/clientes/'+row.id_cl)
                    .then((response) => {
                        if(response.data.val){
                            $.notify({
                                icon: "notifications",
                                message: "Ha elejido a "+row.razon_cl

                            }, {
                                type: 'primary',
                                timer: 3000,
                                placement: {
                                    from: 'bottom',
                                    align: 'right'
                                }
                            });
                            location.replace(response.data.ruta);
                        }else{
                            $.notify({
                                icon: "error",
                                message: "Ha ocurrido un error vuelva a intentar."

                            }, {
                                type: 'danger',
                                timer: 3000,
                                placement: {
                                    from: 'bottom',
                                    align: 'right'
                                }
                            });
                        }
                    }).catch((error) => {
                    $.notify({
                        icon: "error",
                        message: "Ha ocurrido un error vuelva a intentar."

                    }, {
                        type: 'danger',
                        timer: 3000,
                        placement: {
                            from: 'bottom',
                            align: 'right'
                        }
                    });
                });

                console.log({row,index});
            }
        },
        mounted(){
            this.cargar();
        }
    }
</script>