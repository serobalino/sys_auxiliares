<template>
    <div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel"><i class="material-icons">create_new_folder</i> Nuevo Cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div class="alert-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        Ingrese la información del Contribuyente.
                    </div>
                    <div class="alert alert-success">
                        <div class="alert-icon">
                            <i class="material-icons">check</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        <b>Success Alert:</b> Yuhuuu! You've got your $11.99 album from The Weeknd
                    </div>

                    <div class="alert alert-warning">
                        <div class="alert-icon">
                            <i class="material-icons">warning</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        <b>Warning Alert:</b> Hey, it looks like you still have the "copyright &copy;  2015" in your footer. Please update it!
                    </div>

                    <div class="alert alert-danger">
                        <div class="alert-icon">
                            <i class="material-icons">error_outline</i>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="material-icons">clear</i></span>
                        </button>
                        <b>Error Alert:</b> Damn man! You screwed up the server this time. You should find a good excuse for your Boss...
                    </div>
                    <form>
                        <div class="form-group label-floating">
                            <label class="control-label">RUC</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Nombres</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Apellidos</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Razón Social</label>
                            <input type="text" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" v-on:click="actualizarLista">Guardar</button>
                </div>
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
        }),
        methods:{
            actualizarLista:function(){
                componentes.$emit('actualizar-tabla');
            }
        }
    }
</script>

<style scoped>

</style>