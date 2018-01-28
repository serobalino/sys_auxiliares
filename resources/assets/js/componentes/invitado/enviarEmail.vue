<template>
    <div>
        <div v-if="!procesado">
            <div class="section landing-section animated fadeIn" v-if="!enviado">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h2 class="text-center title">Contáctanos</h2>
                        <h4 class="text-center description">Necesitas más información?<br>Envíanos tu inquietud y gustosos te ayudaremos</h4>
                        <form class="contact-form" v-on:submit.prevent="enviar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Nombre</label>
                                        <input type="text" class="form-control" v-model="nombre" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group label-floating">
                                        <label class="control-label">Correo electrónico</label>
                                        <input type="email" class="form-control" v-model="correo" required autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group label-floating">
                                <label class="control-label">Mensaje</label>
                                <textarea class="form-control" rows="4" v-model="mensaje" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 text-center">
                                    <button class="btn btn-black btn-raised">
                                        Enviar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card card-nav-tabs animated fadeIn" v-else>
                <div class="content">
                    <div class="tab-content text-center">
                        <h2 class="text-success"><span class="fa fa-envelope-o"></span> Se ha enviado su mensaje</h2>
                    </div>
                </div>
            </div>
            <br><br><br><br><br><br>
        </div>
        <div v-else>
            <br>
            <div class="text-center animated fadeIn">
                <h1 class="text-gray"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></h1>
                <h3 class="description">Procesando</h3>
            </div>
            <br>
        </div>
    </div>
</template>
<script>
    export default {
        name: "enviar-email",
        data:()=>({
            enviado:false,
            procesado:false,
            nombre:'',
            correo:'',
            mensaje:''
        }),
        methods:{
            enviar:function(){
                this.procesado=true;
                axios.post('/',{
                    correo:this.correo,
                    nombre:this.nombre,
                    mensaje:this.mensaje,
                }).then((response) => {
                    this.enviado=response.data.val;
                    this.procesado=false;
                }).catch((error) => {
                    this.procesado=true;
                });
            },
            verificar:function(){
                this.procesado=true;
                axios.options('/').then((response) => {
                    this.enviado=response.data.val;
                    this.procesado=false;
                }).catch((error) => {
                    this.procesado=true;
                });
            }
        },
        mounted(){
            new WOW().init();
            this.verificar();
        }
    }
</script>