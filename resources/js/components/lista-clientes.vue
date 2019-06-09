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
                <b-list-group flush>
                    <b-list-group-item
                            :href="'#'+item.dni_cl"
                            v-for="item in filtrar"
                            :key="item.id_cl"
                            :active="item.id_cl===picker.id_cl"
                            v-on:click="seleccionar(item)"
                    >{{item.apellidos_cl+" "+item.nombres_cl}}</b-list-group-item>
                </b-list-group>
                <div class="text-center" v-if="filtrar.length===0">
                    <p class="text-danger">No existen registros</p>
                </div>
            </div>
        </div>
        <b-modal hide-footer centered id="nuevo" size="xl" title="Nuevo">
            <div class="alert alert-info" role="alert" v-if="estado.id===1">
                <i class="fa fa-info-circle"></i> Ingrese la información del cliente
            </div>
            <div class="alert alert-warning" role="alert" v-if="estado.id===2">
                <div class="spinner-border text-primary" role="status"></div> Procesando
            </div>
            <div class="alert alert-danger" role="alert" v-if="estado.id===3">
                <i class="fa fa-stop"></i> {{estado.mensaje}}
            </div>
            <div class="alert alert-success" role="alert" v-if="estado.id===4">
                <i class="fa fa-thumbs-up"></i> {{estado.mensaje}}
            </div>
            <form v-on:submit.prevent="validar">
                <fieldset :disabled="estado.disabled">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Nombres</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="'required'" name="nombres" v-model="formulario.nombres" class="form-control" placeholder="Fany Loyola">
                            <small class="text-danger">{{ errors.first('nombres') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Apellidos</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="'required'" name="apellidos" v-model="formulario.apellidos" class="form-control" placeholder="Robalino Altamirano">
                            <small class="text-danger">{{ errors.first('apellidos') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Razón Social</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="'required'" name="razón social" v-model="formulario.razon" class="form-control" placeholder="ASECONT PUYO">
                            <small class="text-danger">{{ errors.first('razón social') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="'required|min:10|max:10'" name="cédula" v-model="formulario.dni" class="form-control" placeholder="1600123456">
                            <small class="text-danger">{{ errors.first('cédula') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>RUC</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="'required|min:13|max:13'" name="ruc" v-model="formulario.ruc" class="form-control" placeholder="1600123456001">
                            <small class="text-danger">{{ errors.first('ruc') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import es from 'vee-validate/dist/locale/es';
    Validator.localize('es',es);
    import * as servicios from "../servicios";
    import VeeValidate, { Validator } from 'vee-validate';
    Vue.use(VeeValidate);
    export default {
        name: "lista-clientes",
        data:()=>({
            lista:[],
            picker:{
                id_cl:null,
            },
            cargando:true,
            buscar:null,
            formulario:{
                apellidos:null,
                nombres:null,
                razon:null,
                ruc:null,
                dni:null
            },
            estado:{
                id:1,
                mensaje:"",
                disabled:false
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
            seleccionar:function(item){
                this.picker=item;
                this.$emit('seleccionado',item);
            },
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
                        this.estado.id=2;
                        this.estado.disabled=true;
                        servicios.clientes.store(this.formulario).then((response)=>{
                            this.estado.id=4;
                            this.estado.mensaje=response.data.mensaje;
                            this.estado.disabled=false;
                            this.consultar();
                            this.formulario={
                                apellidos:null,
                                nombres:null,
                                razon:null,
                                ruc:null,
                                dni:null
                            };
                        }).catch((error)=>{
                            this.estado.id=3;
                            this.estado.disabled=false;
                            this.estado.mensaje=error.response.data.message;
                        });
                    } else {
                        this.estado.id=1;
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