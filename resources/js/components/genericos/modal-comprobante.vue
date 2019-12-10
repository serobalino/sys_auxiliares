<template>
    <json-view :data="datos.comprobante"></json-view>
</template>

<script>
    import { JSONView } from "vue-json-component";
    export default {
        name: "modal-comprobante",
        components: { "json-view": JSONView },
        props:{
            datos:{
                type:Object
            }
        }
    }
</script>

<style scoped>

</style>