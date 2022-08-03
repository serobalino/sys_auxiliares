<template>
    <div class="card">
        <b-modal hide-footer centered id="resumen" size="xl" title="Subir Comprobantes" no-close-on-esc
                 no-close-on-backdrop hide-header-close v-if="cliente">
            <div v-if="subiendo">
                <div class="alert alert-warning" role="alert">
                    <div class="spinner-border text-primary" role="status"></div>
                    Procesando
                </div>
            </div>
            <div v-else>
                <div class="alert alert-info" role="alert" v-if="mensaje.estado===1">
                    <i class="fa fa-info-circle"></i> <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-danger" role="alert" v-if="mensaje.estado===2">
                    <span v-html="mensaje.texto"></span>
                </div>
                <div v-if="mensaje.estado===3">
                    <div class="alert alert-success" role="alert" >
                        <i class="fa fa-thumbs-up"></i> <span v-html="mensaje.texto"></span>
                    </div>
                </div>
            </div>

            <b-form-file accept=".txt" browse-text="Examinar" v-model="archivo" :disabled="subiendo"
                         placeholder="Elija un archivo"></b-form-file>
            <div class="text-center mt-3">
                <button class="btn btn-info" v-on:click="subir" :disabled="subiendo">Subir</button>
                <button class="btn btn-danger" v-on:click="ocultarModal" :disabled="subiendo">Cancelar</button>
            </div>
        </b-modal>
        <b-modal hide-footer centered id="resumen2" size="xl" title="Subir Comprobantes XLM" no-close-on-esc
                 no-close-on-backdrop hide-header-close v-if="cliente">
            <div v-if="subiendo">
                <div class="alert alert-warning" role="alert">
                    <b-progress :value="subidaXML.archivos" :max="archivo.length" show-progress animated/>
                    Procesando
                </div>
            </div>
            <div v-else>
                <div class="alert alert-info" role="alert" v-if="mensaje.estado===1">
                    <i class="fa fa-info-circle"></i> <span v-html="mensaje.texto"></span>
                </div>
                <div class="alert alert-danger" role="alert" v-if="mensaje.estado===2">
                    <span v-html="mensaje.texto"></span>
                </div>
                <div v-if="mensaje.estado===3">
                    <div class="alert alert-success" role="alert" >
                        <i class="fa fa-thumbs-up"></i> <span v-html="mensaje.texto"></span>
                        <button class="btn btn-info" v-on:click="limpiar">Limpiar</button>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p>Archivos guardados</p>
                            <b-table striped hover fixed small :items="subidaXML.lista.guardados" :fields="exitoTabla">
                                <template #empty="scope">
                                    <h4>No existen XML guardados</h4>
                                </template>
                            </b-table>
                        </div>
                        <div class="col-6">
                            <p>Archivos con error</p>
                            <b-table striped hover fixed small :items="subidaXML.lista.errores" :fields="errorTabla" table-variant="danger">
                                <template #empty="scope">
                                    <h4>No existen archivos con errores</h4>
                                </template>
                            </b-table>
                        </div>
                    </div>
                </div>
            </div>

            <b-form-file accept=".xml" browse-text="Examinar" :file-name-formatter="formatNames" v-model="archivo"
                         :disabled="subiendo" multiple placeholder="Elija varios archivos"></b-form-file>
            <div class="text-center mt-3">
                <button class="btn btn-info" v-on:click="subir2" :disabled="subiendo">Subir</button>
                <button class="btn btn-danger" v-on:click="ocultarModal" :disabled="subiendo">Cancelar</button>
            </div>
        </b-modal>
        <div class="card-header">Comprobantes Electrónicos</div>
        <div class="card-body">
            <b-alert show variant="info" v-if="cliente">
                <h4 class="alert-heading">{{ cliente.razon_cl }}</h4>
                <p>{{ cliente.apellidos_cl }} {{ cliente.nombres_cl }}</p>
                <button class="btn btn-success" v-b-modal.resumen>Subir Reporte del SRI</button>
                <button class="btn btn-success" v-b-modal.resumen2>Subir XML del SRI</button>
                <hr>
                <p class="mb-0">
                    <b>Cédula</b> {{ cliente.dni_cl }}<br>
                    <b>RUC</b> {{ cliente.ruc_cl }}<br>
                    <span v-if="cliente.email_cl"><b>Email</b> <a
                        :href="'mailto:'+cliente.email_cl">{{ cliente.email_cl }}</a><br></span>
                    <template v-if="cliente.contrasenas.length">
                        <span v-for="item in cliente.contrasenas">
                            <b>Contranseña de {{ item.label.nombre_tc }}</b>
                            <span
                                v-clipboard:copy="item.contrasena_hc"
                                v-clipboard:success="onCopy"
                                @mousedown="show=true"
                                @mouseup="show=false"
                                @mouseleave="show=false"
                                class="btn p-0"
                            >
                                {{ show ? item.contrasena_hc : '*'.repeat(item.contrasena_hc.length) }} <i class="fa fa-eye" :class="{'fa-eye':show,'fa-eye-slash':!show}"></i>
                            </span>
                        </span>
                    </template>
                </p>
            </b-alert>
            <b-alert show variant="danger" v-else>
                Elija un <b>Cliente</b>
            </b-alert>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Desde</label>
                    <datetime v-model="desde" input-class="form-control" placeholder="Elija una fecha" :auto="true"
                              :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
                <div class="form-group col-md-6">
                    <label>Hasta</label>
                    <datetime v-model="hasta" input-class="form-control" placeholder="Elija una fecha" :auto="true"
                              :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
            </div>
            <div class="botones text-right">
                <button class="btn btn-success" :disabled="cargandoEx" v-if="excel" v-on:click="descargarExcel">
                    <i class="fa" :class="cargandoEx ? 'fa-spin fa-spinner' :'fa-file-excel-o'"/>
                    {{ cargandoEx ? 'Generando Excel' : 'Generar Excel' }}
                </button>
                <button class="btn btn-primary" :disabled="cargandoBs" v-on:click="consulta">
                    <i class="fa fa-spin fa-spinner" v-if="cargandoBs"/>
                    Buscar
                </button>
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
                @on-row-click="verDetalles">
                <div slot="emptystate">
                    <div class="vgt-center-align vgt-text-disabled">{{ textoTabla }}</div>
                </div>
            </vue-good-table>
            <b-modal hide-footer centered id="comprobante" size="xl" title="Comprobante">
                <modal-comprobante :datos="detalle"></modal-comprobante>
            </b-modal>
        </div>
    </div>
</template>

<script>
import {VueGoodTable} from 'vue-good-table';
import {Datetime} from 'vue-datetime';
import * as servicios from "../servicios";
import {Settings} from 'luxon'

Settings.defaultLocale = 'es'
export default {
    name: "lista-comprobantes",
    props: {
        cliente: {
            type: Object,
            default: null
        }
    },
    components: {
        VueGoodTable,
        Datetime
    },
    watch: {
        cliente: function () {
            this.filas = [];
            this.consulta();
        },
        desde: function () {
            this.excel = false;
        },
        hasta: function () {
            this.excel = false;
        }
    },
    data() {
        return {
            exitoTabla: [
                {
                    key: 'name',
                    label: 'Archivo',
                    sortable: true,
                }
            ],
            errorTabla: [
                {
                    key: 'name',
                    label: 'Archivo',
                    sortable: true,
                },
                {
                    key: 'error',
                    label: 'Observación',
                    sortable: true,
                },
            ],
            show: false,
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
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtro por fecha de emision',
                    },
                },
                {
                    label: 'Comprobante',
                    field: 'tipo.detalle_tc',
                },
                {
                    label: 'Emisor',
                    field: this.fieldFn,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtro por Razón Social',
                    },
                },
                {
                    label: 'Valor',
                    field: 'valor',
                    type: 'decimal',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Valor Total',
                        filterFn: this.filtrarValor,
                    },
                },
            ],
            filas: [],
            subiendo: false,
            mensaje: {
                estado: 1,
                texto: null,
            },
            excel: false,
            detalle: {
                comprobante: {}
            },
            textoTabla: "Elija un cliente",
            cargandoBs: false,
            cargandoEx: false,
            subidaXML:{
                start: false,
                archivos: 0,
                lista: {
                    guardados: [],
                    errores: []
                }
            }
        }
    },
    methods: {
        limpiar: function(){
            this.mensaje.estado=1;
            this.mensaje.texto = "Suba el resumen de comprobantes del cliente \n" + this.cliente.apellidos_cl + " " + this.cliente.nombres_cl + "</b>";
        },
        onCopy: function () {
            this.$toast.info("Se ha copiado la contraseña de "+this.cliente.razon_cl);
        },
        filtrarValor: function (data, filterString) {
            let x = parseFloat(filterString);
            return data >= x && data <= x + x;
        },
        verDetalles(data) {
            this.$root.$emit('bv::show::modal', 'comprobante');
            this.detalle = data.row;
        },
        formatNames(files) {
            if (files.length === 1) {
                return files[0].name
            } else {
                return `${files.length} archivos selecionados`
            }
        },
        ocultarModal: function () {
            this.$root.$emit('bv::hide::modal', 'resumen', '#btnShow');
            this.$root.$emit('bv::hide::modal', 'resumen2', '#btnShow');
            this.mensaje.estado = 1;
            this.mensaje.texto = "Suba el resumen de comprobantes del cliente \n" + this.cliente.apellidos_cl + " " + this.cliente.nombres_cl + "</b>";
        },
        fieldFn(rowObj) {
            return rowObj.comprobante.infoTributaria.nombreComercial || rowObj.comprobante.infoTributaria.razonSocial;
        },
        consulta: function () {
            if (this.cliente) {
                this.cargandoBs=true;
                this.mensaje.texto = "Suba el resumen de comprobantes del cliente \n" + this.cliente.apellidos_cl + " " + this.cliente.nombres_cl + "</b>";
                this.filas = [];
                this.textoTabla = "Procesando datos";
                servicios.comprobantes.update(this.cliente, this.desde, this.hasta).then((response) => {
                    this.filas = response.data;
                    this.textoTabla = "No tiene comprobantes registrados";
                    this.cargandoBs=false;
                    this.excel = true;
                }).catch((error) => {
                    this.$toast.error(error.response.data.message, 'Error');
                    this.excel = false;
                    this.cargandoBs=false;
                    this.textoTabla = "Ha ocurrido un error";
                });
            } else {
                this.$toast.error("Elija un cliente primero", 'Error');
                this.textoTabla = "Elija un cliente";
            }
        },
        descargarExcel: function () {
            this.cargandoEx=true;
            servicios.comprobantes.descargar(this.cliente, this.desde, this.hasta).then((response) => {
                let fileDownload = require('js-file-download');
                fileDownload(response.data, response.headers.nombre);
                this.cargandoEx=false;
            }).catch(()=>{
                this.cargandoEx=false;
            });
        },
        subir: function () {
            if (this.archivo) {
                this.subiendo = true;
                servicios.archivos.reporte(this.archivo, this.cliente).then((response) => {
                    if (response.data.guardados > 0)
                        this.consulta();
                    this.subiendo = false;
                    this.mensaje.estado = 3;
                    this.mensaje.texto = response.data;
                    this.$toast.show(this.archivo.name, 'Completado');
                    this.archivo = null;
                }).catch(error => {
                    this.subiendo = false;
                    this.mensaje.estado = 2;
                    this.mensaje.texto = error.response.data.message;
                });
            }
        },
        aftterUpload: function(){
            this.subiendo = false;
            this.mensaje.estado = 3;
            this.archivo = null;
            this.mensaje.texto = "Se ha procesado "+this.subidaXML.archivos+" archivos"
        },
        subir2: function () {
            this.subidaXML={
                start: false,
                archivos: 0,
                lista: {
                    guardados: [],
                    errores: []
                }
            }
            if (this.archivo) {
                this.subiendo = true;
                this.subidaXML.start = true;
                this.archivo.forEach(i=>{
                    servicios.archivos.xml(i, this.cliente).then((response) => {
                        this.subidaXML.archivos++;
                        if(response.data.guardados){
                            this.subidaXML.lista.guardados.push({
                                name: i.name,
                                feedback: response.data,
                            })
                        }else{
                            this.subidaXML.lista.errores.push({
                                name: i.name,
                                feedback: response.data,
                                error: response.data.existentes ? "Ya existe" : "No pertenece al cliente",
                            })
                        }
                        if(this.subidaXML.archivos===this.archivo.length){
                            this.aftterUpload()
                        }
                    }).catch(a=>{
                        this.subidaXML.archivos++;
                        this.subidaXML.lista.errores.push({
                            name: i.name,
                            feedback: a.data,
                            error: "Archivo invalido",
                        })
                        if(this.subidaXML.archivos===this.archivo.length){
                            this.aftterUpload()
                        }
                    });
                })
            }
        }
    },
}
</script>

<style scoped>
.botones {
    padding-bottom: 0.5rem;
    padding-top: 0.5rem;
}

.alert > span {
    white-space: pre;
}
</style>
