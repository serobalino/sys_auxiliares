<template>
    <div class="card">
        <div class="card-header">Clientes
            <b-button size="sm" pill v-b-modal.nuevo  variant="success"><i class="fa fa-plus"></i></b-button>
        </div>
        <div class="card-body">
            <div v-if="cargando">
                <div class="text-center">
                    <b-spinner class="m-5" variant="primary" type="grow" label="Loading..."></b-spinner>
                </div>
            </div>
            <div v-else>
                <b-form-group label="Buscar">
                    <b-form-input
                            v-model="buscar"
                            type="text"
                            required
                            placeholder="Carlos Tirado"
                            size="sm"
                    ></b-form-input>
                </b-form-group>
                <b-list-group>
                    <b-list-group-item v-for="item in filtrar" :key="item.id_cl">{{item.apellidos_cl+" "+item.nombres_cl}}</b-list-group-item>
                </b-list-group>
                <div class="text-center" v-if="filtrar.length===0">
                    <p class="text-danger">No existen registros</p>
                </div>
            </div>
        </div>
        <b-modal hide-footer centered id="nuevo" size="xl" title="Nuevo">
            <div class="alert alert-primary" role="alert">
                This is a primary alert—check it out!
            </div>
            <form v-on:submit.prevent="validar">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Nombres</label>
                    <div class="col-sm-10">
                        <input type="text" v-validate="'required'" name="nombres" v-model="formulario.nombres_cl" class="form-control" placeholder="Fany Loyola">
                        <small class="text-danger">{{ errors.first('nombres') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Apellidos</label>
                    <div class="col-sm-10">
                        <input type="text" v-validate="'required'" name="apellidos" v-model="formulario.apellidos_cl" class="form-control" placeholder="Robalino Altamirano">
                        <small class="text-danger">{{ errors.first('nombres') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Razón Social</label>
                    <div class="col-sm-10">
                        <input type="text" v-validate="'required|min:13|max:13'" name="razón social" v-model="formulario.razon_cl" class="form-control" placeholder="ASECONT PUYO">
                        <small class="text-danger">{{ errors.first('razón social') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>RUC</label>
                    <div class="col-sm-10">
                        <input type="text" v-validate="'required'" name="ruc" v-model="formulario.dni_cl" class="form-control" placeholder="1600123456001">
                        <small class="text-danger">{{ errors.first('ruc') }}</small>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>

    import * as servicios from "../servicios";
    import VeeValidate, { Validator } from 'vee-validate';
    import es from 'vee-validate/dist/locale/es';
    Validator.localize('es',es);
    Vue.use(VeeValidate);
    export default {
        name: "lista-clientes",
        data:()=>({
            lista:[],
            cargando:true,
            buscar:null,
            formulario:{
                apellidos_cl:null,
                nombres_cl:null,
                razon_cl:null,
                dni_cl:null
            }
        }),
        computed: {
            filtrar:function(){
                if (!!!this.buscar)
                    return this.lista;
                let self = this;
                return self.lista.filter(function (post) {
                    return post.apellidos_cl.indexOf(self.buscar) !== -1 || post.nombres_cl.indexOf(self.buscar) !== -1 || post.dni_cl.indexOf(self.buscar) !== -1 || post.razon_cl.indexOf(self.buscar) !== -1;
                })
            }
        },
        methods:{
            consultar:function(){
                this.cargando=true;
                servicios.clientes.index().then((response)=>{
                    this.lista=response.data;
                    this.cargando=false;
                });
            },
            validar:function(){
                this.$validator.validateAll().then((result) => {
                    if (result) {

                    } else {

                    }
                });
            }
        },
        mounted() {
            this.consultar();
        }
    }
</script>

<style scoped>

</style>