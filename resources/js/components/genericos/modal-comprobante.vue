<template>
    <div>
        <input type="checkbox" name="tabla" v-model="tabla"> Tabla
        <vue-json-to-table v-if="tabla" :data="datos.comprobante"></vue-json-to-table>
        <json-view v-else :data="datos.comprobante" rootKey="comprobante" :maxDepth="1"></json-view>
    </div>
</template>

<script>
import VueJsonToTable from 'vue-json-to-table';
import { JSONView } from "vue-json-component";
Vue.use(VueJsonToTable);
export default {
    name: "modal-comprobante",
    components: { "json-view": JSONView },
    props: {
        datos: {
            type: Object
        }
    },
    data: ()=>({
        tabla: true
    })
}
</script>

<style scoped>

</style>
