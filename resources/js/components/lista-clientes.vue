<template>
    <div class="card">
        <div class="card-header">Clientes
            <b-button size="sm" pill variant="success" v-on:click="nuevo"><i class="fa fa-plus"></i></b-button>
        </div>
        <div class="card-body">
            <div v-if="cargando">
                <div class="text-center">
                    <b-spinner class="m-5" variant="primary" type="grow" label="Cargando..."></b-spinner>
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
                <b-list-group flush class="max-he">
                    <b-list-group-item
                        :href="'#'+item.dni_cl"
                        v-for="item in filtrar"
                        :key="item.id_cl"
                        :active="item.id_cl===picker.id_cl"
                        v-on:click="seleccionar(item)"
                        v-on:dblclick="editar(item)"
                    >{{item.apellidos_cl+" "+item.nombres_cl}}</b-list-group-item>
                </b-list-group>
                <div class="text-center" v-if="filtrar.length===0">
                    <p class="text-danger">No existen registros</p>
                </div>
            </div>
        </div>
        <b-modal hide-footer centered v-model="modal" size="xl" :title="bandera ? 'Actualizar' : 'Nuevo'">
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
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>Cédula</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="{required:!bandera,min:10,max:10}" :readonly="bandera" name="cédula" v-model="formulario.dni" class="form-control" placeholder="1600123456">
                            <small class="text-danger">{{ errors.first('cédula') }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"><span class="text-danger">*</span>RUC</label>
                        <div class="col-sm-10">
                            <input type="text" v-validate="{required:!bandera,min:13,max:13}" :readonly="bandera" name="ruc" v-model="formulario.ruc" class="form-control" placeholder="1600123456001">
                            <small class="text-danger">{{ errors.first('ruc') }}</small>
                        </div>
                    </div>
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
                        <label class="col-sm-2 col-form-label">Correo electrónico</label>
                        <div class="col-sm-10">
                            <input type="email" v-validate="'email'" name="email" v-model="formulario.email" class="form-control" placeholder="srobalino@asecontpuyo.com">
                            <small class="text-danger">{{ errors.first('email') }}</small>
                        </div>
                    </div>
                    <div class="form-group row" v-for="item in claves">
                        <label class="col-sm-2 col-form-label">Contraseña de {{item.nombre_tc}}</label>
                        <div class="col-sm-10">
                            <input type="text" :name="item.nombre_tc" v-model="item.clave" class="form-control">
                            <small class="text-danger">{{ errors.first(item.nombre_tc) }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> {{bandera ? "Actualizar" : "Guardar"}}</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import * as servicios from "../servicios";
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
                email:null,
                dni:null
            },
            claves:[],
            estado:{
                id:1,
                mensaje:"",
                disabled:false
            },
            modal:false,
            bandera:false,
        }),
        computed: {
            filtrar:function(){
                if (!!!this.buscar)
                    return this.lista;
                let self = this;
                return self.lista.filter(function (post) {
                    return post.apellidos_cl.indexOf(self.buscar.toUpperCase()) !== -1 || post.nombres_cl.indexOf(self.buscar.toUpperCase()) !== -1 || post.dni_cl.indexOf(self.buscar.toUpperCase()) !== -1 || post.razon_cl.indexOf(self.buscar.toUpperCase()) !== -1;
                })
            },
            // lstComprobantes:function(){
            //     return this.$store.getters['catalogos/getComprobantes'];
            // }
        },
        methods:{
            nuevo:function(){
                this.estado.id=1;
                this.bandera=false;
                this.formulario={
                    apellidos:null,
                    nombres:null,
                    razon:null,
                    email:null,
                    ruc:null,
                    dni:null
                };
                this.picker={
                    id_cl:null,
                };
                this.modal=true;
                this.limpiarClaves();
            },
            limpiarClaves:function(){
              this.claves.map(i=>{
                  i.clave=null;
              })
            },
            asignarClave:function(claves){
                this.claves.map(i=>{
                    claves.map(j=>{
                        if(i.id_tc===j.id_tc)
                            i.clave=j.contrasena_hc;
                    })
                })
            },
            seleccionar:function(item){
                this.estado.id=0;
                this.picker=item;
                this.limpiarClaves();
                this.asignarClave(item.contrasenas);
                this.$emit('seleccionado',item);
            },
            consultar:function(){
                this.cargando=true;
                servicios.clientes.index().then((response)=>{
                    this.lista=response.data;
                    this.cargando=false;
                    this.actualizarSelecionado();
                });
            },
            loadCatalogos:function(){
                servicios.catalogos.claves().then(r=>r.data).then(claves=>{
                    this.claves=claves;
                });
            },
            validar:function(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.estado.id=2;
                        this.estado.disabled=true;
                        this.enviar(this.bandera);
                    } else {
                        this.estado.id=1;
                    }
                });
            },
            actualizarSelecionado:function(){
                const id = this.picker.id_cl;
                if(id){
                    const busqueda=this.lista.findIndex(i=>i.id_cl===id)
                    if(busqueda>=0){
                        this.picker=this.lista[busqueda]
                        this.$emit('seleccionado',this.picker);
                    }
                }
            },
            enviar:function(tipo){
                this.formulario.contrasenas=this.claves;
                if(tipo){
                    servicios.clientes.update(this.formulario).then((response)=>{
                        this.estado.id=4;
                        this.estado.mensaje=response.data.mensaje;
                        this.estado.disabled=false;
                        this.consultar();
                        this.modal=false;
                        this.limpiarClaves();
                    }).catch((error)=>{
                        this.estado.id=3;
                        this.estado.disabled=false;
                        this.estado.mensaje=error.response.data.message;
                    });
                }else{
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
                            dni:null,
                            email:null,
                        };
                        this.modal=false;
                    }).catch((error)=>{
                        this.estado.id=3;
                        this.estado.disabled=false;
                        this.estado.mensaje=error.response.data.message;
                    });
                }
            },
            editar:function(item){
                this.bandera=true;
                this.modal=true;
                this.picker=item;
                this.formulario.apellidos=item.apellidos_cl;
                this.formulario.nombres=item.nombres_cl;
                this.formulario.razon=item.razon_cl;
                this.formulario.ruc=item.ruc_cl;
                this.formulario.dni=item.dni_cl;
                this.formulario.codigo=item.id_cl;
                this.formulario.email=item.email_cl;
            }
        },
        mounted() {
            this.consultar();
        },
        created() {
            this.loadCatalogos()
        }
    }
</script>

<style scoped>
.max-he{
    max-height: 63vh;
    overflow: scroll;
}
</style>
