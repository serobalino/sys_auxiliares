<template>
    <div class="card">
        <b-modal hide-footer centered id="resumen" size="md" title="Subir Comprobantes" no-close-on-esc no-close-on-backdrop hide-header-close v-if="cliente">
            <div class="alert alert-info" role="alert" >
                <i class="fa fa-info-circle"></i> Suba el resumen de comprobantes del cliente <b>{{cliente.apellidos_cl}} {{cliente.nombres_cl}}</b>
            </div>
            <div class="alert alert-warning" role="alert" >
                <div class="spinner-border text-primary" role="status"></div> Procesando
            </div>
            <div class="alert alert-danger" role="alert" >
                <i class="fa fa-stop"></i>
            </div>
            <div class="alert alert-success" role="alert">
                <i class="fa fa-thumbs-up"></i>
            </div>
            <b-form-file accept=".txt" browse-text="Examinar" v-model="archivo" placeholder="Elija un archivo"></b-form-file>
            <div class="text-center mt-3">
                <button class="btn btn-info" v-on:click="subir">Subir</button>
                <button class="btn btn-danger" >Cancelar</button>
            </div>
        </b-modal>
        <div class="card-header">Comprobantes Electrónicos</div>
        <div class="card-body">
            <b-alert show variant="info" v-if="cliente">
                <h4 class="alert-heading">{{cliente.razon_cl}}</h4>
                <p>{{cliente.apellidos_cl}} {{cliente.nombres_cl}}</p>
                <button class="btn btn-success"  v-b-modal.resumen  >Subir Reporte</button>
                <hr>
                <p class="mb-0">
                   <b>Cédula</b> {{cliente.dni_cl}}<br>
                   <b>RUC</b> {{cliente.ruc_cl}}
                </p>
            </b-alert>
            <b-alert show variant="danger" v-else>
                Elija un <b>Cliente</b>
            </b-alert>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Desde</label>
                    <datetime v-model="desde" input-class="form-control"  placeholder="Elija una fecha" :auto="true" :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
                <div class="form-group col-md-6">
                    <label>Hasta</label>
                    <datetime v-model="hasta" input-class="form-control"  placeholder="Elija una fecha" :auto="true" :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
            </div>
            <div class="botones text-right">
                <button class="btn btn-success" v-if="filas.length>0"><i class="fa fa-file-excel-o" ></i> Generar Excel</button>
                <button class="btn btn-primary" v-on:click="consulta">Buscar</button>
            </div>
            <vue-good-table
                    :columns="columns"
                    :rows="filas"
                    :pagination-options="{
                        enabled: true,
                        mode: 'comprobantes',
                        perPage: 20,
                        dropdownAllowAll: true,
                        nextLabel: 'siguiente',
                        prevLabel: 'anterior',
                        rowsPerPageLabel: 'Comprobantes por página',
                        ofLabel: 'de',
                        pageLabel: 'página', // for 'pages' mode
                        allLabel: 'Todos',
                      }"/>
        </div>
    </div>
</template>

<script>
    import { VueGoodTable } from 'vue-good-table';
    import { Datetime } from 'vue-datetime';
    import { Settings } from 'luxon';
    import * as servicios from "../servicios";
    Settings.defaultLocale = 'es';
    Vue.use(Datetime);
    export default {
        name: "lista-comprobantes",
        props:{
            cliente: {
                type: Object,
                default: null
            }
        },
        components: {
            VueGoodTable,
        },
        watch:{
            cliente:function(){
                this.consulta();
            }
        },
        data:()=>({

            archivo:null,
            desde:null,
            hasta:null,
            columns: [
                {
                    label: 'Name',
                    field: 'name',
                },
                {
                    label: 'Age',
                    field: 'age',
                    type: 'number',
                },
                {
                    label: 'Created On',
                    field: 'createdAt',
                    type: 'date',
                    dateInputFormat: 'YYYY-MM-DD',
                    dateOutputFormat: 'MMM Do YY',
                },
                {
                    label: 'Percent',
                    field: 'score',
                    type: 'percentage',
                },
            ],
            filas: [],
        }),
        methods:{
            consulta:function(){
                if(this.cliente){
                    servicios.comprobantes.update(this.cliente,this.desde,this.hasta).then((response)=>{
                        this.filas=response.data;
                    }).catch((error)=>{
                        this.$toast.error(error.response.data.message, 'Error');
                    });
                }else{
                    this.$toast.error("Elija un cliente primero", 'Error');
                }
            },
            subir:function(){
                servicios.comprobantes.store(this.archivo,this.cliente).then((response)=>{
                    console.log(response)
                });
            }
        },
        updated(){

        }

    }
</script>

<style scoped>
    .botones{
        padding-bottom: 0.5rem;
        padding-top: 0.5rem;
    }
</style>