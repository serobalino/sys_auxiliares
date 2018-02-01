<template>
        <li>
            <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                <i class="material-icons">person</i> {{nombre[0]}}
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" v-on:click="cerrar">Cerrar Sesión</a>
                </li>
                <li>
                    <a href="#" data-toggle="modal" data-target="#miPerfil">Cambiar contraseña</a>
                </li>
            </ul>
        </li>
</template>
<script>
    export default {

        name: "session",
        props:['url'],
        data:()=>({
            datos:[],
            nombre:'',
        }),
        methods:{
            cargar:function(){
                axios.get(this.url)
                    .then(response=>{
                        let nombre=response.data.user.nombres_ad;
                        this.datos=response.data;
                        this.nombre=nombre.split(" ");
                    });
            },
            cerrar:function(){
                axios.delete(this.datos.logout)
                    .then(response=>{
                        if(response.data.val){
                            $.notify({
                                icon: "exit_to_app",
                                message: "Ha cerrado sessión."

                            }, {
                                type: 'info',
                                timer: 3000,
                                placement: {
                                    from: 'bottom',
                                    align: 'right'
                                }
                            });
                            location.replace(response.data.ruta)
                        }
                    })
                    .catch((error) => {
                        $.notify({
                            icon: "exit_to_app",
                            message: "No se pudo cerrar sesión vuelva a intentar."

                        }, {
                            type: 'danger',
                            timer: 3000,
                            placement: {
                                from: 'bottom',
                                align: 'right'
                            }
                        });
                    });
            }
        },
        mounted(){
            this.cargar();
        }
    }
</script>