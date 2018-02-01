<template>
    <div id="miPerfil" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cambiar contraseña</h4>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <h6 class="category text-gray"></h6>
                        <h4 class="card-title">{{usuario.nombres_ad}}</h4>
                        <p class="card-content">{{usuario.email_ad}}</p>
                        <form action="">
                            <div class="form-group label-floating">
                                <label class="control-label">Contraseña</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group label-floating">
                                <label class="control-label">Repitacontraseña</label>
                                <input type="text" class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" >Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "modal-sesion",
        props:['url'],
        data:()=>({
            datos:[],
            usuario:[],
        }),
        methods: {
            cargar: function () {
                axios.get(this.url)
                    .then(response => {
                        this.datos = response.data;
                        this.usuario=response.data.user;
                    });
            },
        },
        mounted(){
          this.cargar();
        }
    }
</script>

<style scoped>

</style>