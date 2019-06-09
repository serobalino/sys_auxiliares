<template>
    <div class="row">
        <div class="col-md-4">
            <lista-clientes v-on:seleccionado="clickeado"></lista-clientes>
        </div>
        <div class="col-md-8">
            <lista-comprobantes :cliente="cliente"></lista-comprobantes>
        </div>
    </div>
</template>

<script>
    export default {
        name: "dashboard",
        data:()=>({
            cliente:null
        }),
        methods:{
            clickeado:function(cliente){
                this.cliente=cliente;
            }
        }
    }
</script>

<style scoped>

</style>