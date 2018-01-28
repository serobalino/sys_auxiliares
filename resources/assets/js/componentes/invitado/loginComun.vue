<template>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="card card-signup animated fadeIn" v-if="!procesado">
            <form class="form" v-on:submit.prevent="enviar">
                <div class="header header-info text-center">
                    <h4>Ingresar con</h4>
                    <div class="social-line">
                        <a href="#pablo" class="btn btn-simple btn-just-icon">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                    </div>
                </div>
                <div class="alert alert-danger animated tada" v-if="mensaje===2">
                    <div class="container-fluid">
                        <div class="alert-icon">
                            <i class="material-icons">warning</i>
                        </div>
                        Error en los credenciales.
                    </div>
                </div>
                <div class="alert alert-warning animated tada" v-if="mensaje===1">
                    <div class="container-fluid">
                        <div class="alert-icon">
                            <i class="material-icons">warning</i>
                        </div>
                        No existe usuario.
                    </div>
                </div>
                <p class="text-divider">Ingrese sus credenciales</p>
                <div class="content">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">call_to_action</i>
                        </span>
                        <input type="text" v-model="usr" class="form-control" placeholder="Usuario..." required autocomplete="off"/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock_outline</i>
                        </span>
                        <input type="password" placeholder="Contraseña..." v-model="psw" class="form-control" required autocomplete="off"/>
                    </div>
                </div>
                <div class="footer text-center">
                    <button class="btn btn-simple btn-primary btn-lg" type="submit">Ingresar</button>
                </div>
            </form>
        </div>
        <div class="card card-signup" v-else>
            <div class="header header-info text-center">
                <h4>Procesando</h4>
            </div>
            <div class="content animated bounceIn">
                <div class="text-center">
                    <br><br><br><br>
                    <h1 class="text-muted text-center"><i class="fa fa-spinner fa-pulse fa-fw"></i></h1>
                    <br><br><br><br>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "login-comun",
        props:['url'],
        data:()=>({
            enviado:false,
            procesado:true,
            usr:'',
            psw:'',
            mensaje:0
        }),
        methods:{
            enviar:function(){
                this.procesado=true;
                axios.post(this.url,{
                    usr:this.usr,
                    psw:this.psw,
                }).then((response) => {
                    console.log(response.data);
                    this.usr='';
                    this.psw='';
                    if(response.data.val)
                        window.location=response.data.url;
                    else
                        this.mensaje=2;
                    this.procesado=false;
                }).catch((error) => {
                    this.procesado=false;
                    this.mensaje=1;
                });
            }
        },
        mounted(){
            this.procesado=false;
        }
    }
</script>