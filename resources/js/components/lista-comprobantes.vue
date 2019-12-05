<template>
    <div class="card">
        <b-modal hide-footer centered id="resumen" size="xl" title="Subir Comprobantes" no-close-on-esc no-close-on-backdrop hide-header-close v-if="cliente">
            <div v-if="subiendo">
                <div class="alert alert-warning" role="alert" >
                    <div class="spinner-border text-primary" role="status"></div> Procesando
                </div>
            </div>
            <div v-else>
                <div class="alert alert-info" role="alert" v-if="mensaje.estado===1">
                    <i class="fa fa-info-circle"></i> <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-danger" role="alert" v-if="mensaje.estado===2" >
                    <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-success" role="alert" v-if="mensaje.estado===3">
                    <i class="fa fa-thumbs-up"></i> <span v-html="mensaje.texto"></span>
                </div>
            </div>

            <b-form-file accept=".txt" browse-text="Examinar" v-model="archivo" :disabled="subiendo" placeholder="Elija un archivo"></b-form-file>
            <div class="text-center mt-3">
                <button class="btn btn-info" v-on:click="subir" :disabled="subiendo">Subir</button>
                <button class="btn btn-danger" v-on:click="ocultarModal" :disabled="subiendo"  >Cancelar</button>
            </div>
        </b-modal>
        <b-modal hide-footer centered id="resumen2" size="xl" title="Subir Comprobantes XLM" no-close-on-esc no-close-on-backdrop hide-header-close v-if="cliente">
            <div v-if="subiendo">
                <div class="alert alert-warning" role="alert" >
                    <div class="spinner-border text-primary" role="status"></div> Procesando
                </div>
            </div>
            <div v-else>
                <div class="alert alert-info" role="alert" v-if="mensaje.estado===1">
                    <i class="fa fa-info-circle"></i> <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-danger" role="alert" v-if="mensaje.estado===2" >
                    <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-success" role="alert" v-if="mensaje.estado===3">
                    <i class="fa fa-thumbs-up"></i> <span v-html="mensaje.texto"></span>
                </div>
            </div>

            <b-form-file accept=".xml" browse-text="Examinar" :file-name-formatter="formatNames" v-model="archivo"  :disabled="subiendo" multiple placeholder="Elija varios archivos"></b-form-file>
            <div class="text-center mt-3">
                <button class="btn btn-info" v-on:click="subir2" :disabled="subiendo">Subir</button>
                <button class="btn btn-danger" v-on:click="ocultarModal" :disabled="subiendo"  >Cancelar</button>
            </div>
        </b-modal>
        <div class="card-header">Comprobantes Electrónicos</div>
        <div class="card-body">
            <b-alert show variant="info" v-if="cliente">
                <h4 class="alert-heading">{{cliente.razon_cl}}</h4>
                <p>{{cliente.apellidos_cl}} {{cliente.nombres_cl}}</p>
                <button class="btn btn-success"  v-b-modal.resumen  >Subir Reporte del SRI</button>
                <button class="btn btn-success"  v-b-modal.resumen2  >Subir XML del SRI</button>
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
                <button class="btn btn-success" v-if="excel" v-on:click="descargarExcel"><i class="fa fa-file-excel-o" ></i> Generar Excel</button>
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
                      }"
                    :sort-options="{
                        enabled: true,
                        initialSortBy: {field: 'fecha_co', type: 'asc'}
                      }"
            />
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
                this.filas=[];
                this.consulta();
            },
            desde:function(){
                this.excel=false;
            },
            hasta:function(){
                this.excel=false;
            }
        },
        data(){
            return {
                archivo: null,
                desde: null,
                hasta: null,
                columns: [
                    {
                        label: 'Fecha',
                        field: 'fecha_co',
                        type: 'date',
                        dateInputFormat: 'YYYY-MM-DD',
                        dateOutputFormat: 'DD-MM-YYYY',
                    },
                    {
                        label: 'Comprobante',
                        field: 'tipo.detalle_tc',
                    },
                    {
                        label: 'Emisor',
                        field: this.fieldFn,
                    },
                    {
                        label: 'Valor',
                        field: 'valor',
                        type: 'decimal',
                    },
                ],
                filas: [],
                subiendo:false,
                mensaje:{
                    estado:1,
                    texto:null,
                },
                excel:false,
            }
        },
        methods:{
            formatNames(files) {
                if (files.length === 1) {
                    return files[0].name
                } else {
                    return `${files.length} archivos selecionados`
                }
            },
            ocultarModal:function(){
                this.$root.$emit('bv::hide::modal', 'resumen', '#btnShow');
                this.$root.$emit('bv::hide::modal', 'resumen2', '#btnShow');
                this.mensaje.estado=1;
                this.mensaje.texto="Suba el resumen de comprobantes del cliente \n"+this.cliente.apellidos_cl+" "+this.cliente.nombres_cl+"</b>";
            },
            fieldFn(rowObj) {
                return rowObj.comprobante.infoTributaria.nombreComercial || rowObj.comprobante.infoTributaria.razonSocial;
            },
            consulta:function(){
                if(this.cliente){
                    this.mensaje.texto="Suba el resumen de comprobantes del cliente \n"+this.cliente.apellidos_cl+" "+this.cliente.nombres_cl+"</b>";
                    servicios.comprobantes.update(this.cliente,this.desde,this.hasta).then((response)=>{
                        this.filas=response.data;
                        if(response.data.length)
                            this.excel=true;
                    }).catch((error)=>{
                        this.$toast.error(error.response.data.message, 'Error');
                        this.excel=false;
                    });
                }else{
                    this.$toast.error("Elija un cliente primero", 'Error');
                }
            },
            descargarExcel:function(){
                servicios.comprobantes.descargar(this.cliente,this.desde,this.hasta).then((response)=>{
                    let fileDownload = require('js-file-download');
                    fileDownload(response.data, response.headers.nombre);
                });
            },
            subir:function(){
                if(this.archivo){
                    this.subiendo=true;
                    servicios.archivos.reporte(this.archivo,this.cliente).then((response)=>{
                        if(response.data.guardados>0)
                            this.consulta();
                        this.subiendo=false;
                        this.mensaje.estado=3;
                        this.mensaje.texto=response.data;
                        this.$toast.show(this.archivo.name, 'Completado');
                        this.archivo=null;
                    }).catch(error=>{
                        this.subiendo=false;
                        this.mensaje.estado=2;
                        this.mensaje.texto=error.response.data.message;
                    });
                }
            },
            subir2:function(){
                if(this.archivo){
                    this.subiendo=true;
                    servicios.archivos.xml(this.archivo,this.cliente).then((response)=>{
                        if(response.data.guardados>0)
                            this.consulta();
                        this.subiendo=false;
                        this.mensaje.estado=3;
                        this.mensaje.texto=response.data;
                        let lista="";
                        lista+= this.archivo.map(i=>{
                            return i.name+"</br>";
                        });
                        this.$toast.show(lista,'Completado');
                        this.archivo=null;
                    }).catch(error=>{
                        this.subiendo=false;
                        this.mensaje.estado=2;
                        this.mensaje.texto=error.response.data.message;
                    });
                }
            }
        },
    }
</script>

<style scoped>
    .botones{
        padding-bottom: 0.5rem;
        padding-top: 0.5rem;
    }
    .alert>span{
        white-space: pre;
    }
</style>
