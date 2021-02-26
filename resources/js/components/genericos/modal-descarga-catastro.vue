<template>
    <div class="position-relative">
        <b-overlay :show="cargando" rounded="sm" class="container-fluid">
            <vue-good-table
                    :columns="columns"
                    :rows="data"
                    :pagination-options="{
                        enabled: true,
                        mode: 'comprobantes',
                        perPage: 5,
                        dropdownAllowAll: true,
                        nextLabel: 'siguiente',
                        prevLabel: 'anterior',
                        rowsPerPageLabel: 'Registros por página',
                        ofLabel: 'de',
                        pageLabel: 'página',
                        allLabel: 'Todos',
                      }"
                    :sort-options="{enabled: true}"
            >
                <div slot="emptystate">
                    <div class="vgt-center-align vgt-text-disabled">Sin datos</div>
                </div>
            </vue-good-table>
            <template #overlay>
                <div class="container-fluid">
                    <div class="text-center">
                        <div class="">
                            <br>
                            <p>Procesando...</p>
                            <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
                            <b-progress :max="100" :value="tiempo" height="3rem" animated>
                                <div class="pb-5"/>
                                <b-progress-bar :value="tiempo">
                                    <span>Progreso: <strong>{{ tiempo }} / {{ 100 }}</strong></span>
                                </b-progress-bar>
                            </b-progress>
                            <br>
                        </div>
                    </div>
                </div>
            </template>
        </b-overlay>
    </div>
</template>

<script>
    import {catastros} from "../../servicios";

    export default {
        name: "modal-descarga-catastro",
        data: () => ({
            data: [],
            archivos: [],
            columns: [
                {
                    label: 'RUC',
                    field: 'numero_ruc',
                    filterOptions: {
                        enabled: true
                    }

                },
                {
                    label: 'Razon Social',
                    field: 'razon_social',
                    filterOptions: {
                        enabled: true
                    }
                },
                {
                    label: 'Tipo',
                    field: 'tipo_contribuyente',
                    filterOptions: {
                        enabled: true
                    }

                }
            ],
            cargando: true,
            tiempo: 5,
            total: 0,
            completados: 0,
            pasos:0,
        }),
        props: {
            value: {
                type: Object
            }
        },
        watch:{
            tiempo:function(val){
                if(val===100){
                    setTimeout(()=> {
                        this.cargando = false;
                    },10000);
                }
            },
        },
        methods: {
            procesar: function () {
                this.tiempo = 0;
                catastros.procesarCatastro(this.value).then((response) => {
                    this.archivos = response.data;
                    this.tiempo = 15;
                    this.total = this.contar(false);
                    this.descargar();
                    this.pasos=parseInt((0.5 *85)/this.total);
                });
            },
            contar: function (bandera,elemento=false) {
                let i = 0, complete = 0;
                let obj = null;
                this.archivos.forEach(item => {
                    item.forEach(item2 => {
                        i++;
                        if(item2===elemento){
                            item2.complete=true;
                        }
                        if (item2.complete) {
                            complete++;
                        } else {
                            if (obj === null) {
                                obj = item2;
                            }
                        }
                    });
                });
                this.completados = complete;
                if (bandera)
                    return obj;
                else
                    return i;
            },
            sacarKeys:function(){
                const labels = Object.keys(this.data.length ? this.data[0] : []);
                let columnas = [];
                labels.forEach(i=>{
                    columnas.push({
                        label: i.toUpperCase(),
                        field: i,
                        filterOptions: {
                            enabled: true
                        }
                    })
                });
                this.columns=columnas;
                this.tiempo = 100;
            },
            descargar: function () {
                const file = this.contar(true);
                this.tiempo+=this.pasos;
                if (file) {
                    catastros.descargarCatastro(file).then((response) => {
                        this.tiempo+=this.pasos;
                        this.data.push(...response.data);
                        this.contar(false,file);
                        this.descargar();
                    })
                }else{
                    this.sacarKeys();
                }
            }
        },
        mounted() {
            this.procesar();
        }
    }
</script>

<style scoped>

</style>
