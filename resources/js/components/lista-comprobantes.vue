<template>
    <div class="card">
        <div class="card-header">Comprobantes Electrónicos</div>
        <div class="card-body">
            <b-alert show variant="info" v-if="cliente">
                <h4 class="alert-heading">{{cliente.razon_cl}}</h4>
                <p>{{cliente.apellidos_cl}} {{cliente.nombres_cl}}</p>
                <hr>
                <p class="mb-0">
                    {{cliente.dni_cl}}
                </p>
            </b-alert>
            <b-alert show variant="danger" v-else>
                Elija un <b>Cliente</b>
            </b-alert>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Desde</label>
                    <datetime v-model="desde" input-class="form-control"  placeholder="Email" :auto="true" :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
                <div class="form-group col-md-6">
                    <label>Hasta</label>
                    <datetime v-model="hasta" input-class="form-control"  placeholder="Password" :auto="true" :phrases="{ok:'Aceptar',cancel:'Cancelar'}" value-zone="UTC-5"/>
                </div>
            </div>
            <div class="botones text-right">
                <button class="btn btn-primary">Buscar</button>
                <button class="btn btn-success"><i class="fa fa-file-excel-o"></i> Generar Excel</button>
            </div>
            <vue-good-table
                    :columns="columns"
                    :rows="rows"
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
        data:()=>({
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
            rows: [
                { id:1, name:"John", age: 20, createdAt: '201-10-31:9: 35 am',score: 0.03343 },
                { id:2, name:"Jane", age: 24, createdAt: '2011-10-31', score: 0.03343 },
                { id:3, name:"Susan", age: 16, createdAt: '2011-10-30', score: 0.03343 },
                { id:4, name:"Chris", age: 55, createdAt: '2011-10-11', score: 0.03343 },
                { id:5, name:"Dan", age: 40, createdAt: '2011-10-21', score: 0.03343 },
                { id:6, name:"John", age: 20, createdAt: '2011-10-31', score: 0.03343 },
            ],
        }),

    }
</script>

<style scoped>
    .botones{
        padding-bottom: 0.5rem;
        padding-top: 0.5rem;
    }
</style>