<template>
    <div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formulario-registro" v-on:submit.prevent="enviar">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel"><i class="material-icons">create_new_folder</i> Nuevo Cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" v-if="mensaje.estado===1">
                        <div class="alert-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        Ingrese la información del Contribuyente.
                    </div>
                    <div class="alert alert-success" v-if="mensaje.estado===2">
                        <div class="alert-icon">
                            <i class="material-icons">check</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        {{mensaje.texto}}
                    </div>

                    <div class="alert alert-warning" v-if="mensaje.estado===3">
                        <div class="alert-icon">
                            <i class="material-icons">warning</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        {{mensaje.texto}}
                    </div>

                    <div class="alert alert-danger" v-if="mensaje.estado===4">
                        <div class="alert-icon">
                            <i class="material-icons">error_outline</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        Error vuelva a intentar
                    </div>
                        <div class="form-group label-floating">
                            <label class="control-label">RUC</label>
                            <input type="text" class="form-control" name="ruc" required autocomplete="off" v-model="ruc">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control" name="nombres" required autocomplete="off" v-model="nombres">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" required autocomplete="off" v-model="apellidos">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Razón Social</label>
                            <input type="text" class="form-control" name="rsocial" required autocomplete="off" v-model="rsocial">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" >Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import {componentes} from '../../administrador.js';
    export default {
        name: "modal-registro",
        data:()=>({
            datos:[],
            mensaje:{
                estado:1,
                texto:'',
            },
            ruc:'',
            nombres:'',
            apellidos:'',
            rsocial:'',
            procesando:false,
        }),
        props:['url'],
        methods:{
            actualizarLista:function(){
                componentes.$emit('actualizar-tabla');
            },
            validar:function(){
                $('#formulario-registro').validate({
                    lang:'es',
                    focusInvalid: true,
                    errorElement: 'span',
                    errorClass: 'help-block',
                    ignore: "",
                    rules:{
                        ruc:{
                            required:true,
                            maxlength:13,
                            minlength:13
                        },
                        nombres:'required',
                        apellidos: 'required',
                        rsocial: 'required',
                    },
                    messages:{
                        ruc:{
                            required:'Es necesario el RUC del contribuyente.',
                            maxLength:'El RUC es de 13 dígitos.',
                            minLength:'El RUC es de 13 dígitos.'
                        },
                        nombres:'Son necesarios los Nombres del contribuyente.',
                        apellidos:'Son necesarios los Apellidos del contribuyente.',
                        rsocial:'Es necesaria la Razón Social del contribuyente.'

                    },
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                    },
                    errorPlacement: function(error, element) {
                        if(element.parent('.form-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            },
            enviar:function(){
                if($('#formulario-registro').valid()){
                    ///todo ajax recues con el ruc
                    axios.post(this.url,{
                        'ruc':1,
                        'apellidos':1,
                        'nombres':2,
                        'rsocial':3,

                    })
                        .then(response=>{
                            this.mensaje.texto=response.data.mensaje;
                            if(response.data.val){
                                this.mensaje.estado=2;
                                componentes.$emit('actualizar-tabla');
                            }else{
                                this.mensaje.estado=3;
                                this.ruc='';
                            }
                        })
                        .catch((error) => {
                            this.mensaje.estado=3;
                            this.mensaje.texto="Vuelva a intentar.";
                        });
                }else{
                    $.notify({
                        icon: "notifications",
                        message: "Complete el formulario."

                    }, {
                        type: 'danger',
                        timer: 3000,
                        placement: {
                            from: 'bottom',
                            align: 'right'
                        }
                    });
                }
            }

        },
        mounted(){
            this.validar();
        }

    }
</script>

<style scoped>

</style>