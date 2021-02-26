<template>
    <div class="accordion" id="acordeon">
        <div class="card" v-for="(item,index) in lista" :key="index">
            <div class="card-header">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#item-'+index" aria-expanded="false">
                        {{ item.title }}
                    </button>
                </h5>
            </div>
            <div :id="'item-'+index" class="collapse" data-parent="#acordeon">
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <button
                                type="button"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                v-for="(item2,index2) in item.items"
                                :key="'a'+index2"
                                v-on:click="procesar(item2)"
                        >
                            {{ item2.name }}
                            <img :src="item2.ext | icono" :alt="item2.ext"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <b-modal hide-footer centered v-model="modal" size="xl" :title="tipo.name" modal-class="todo">
            <modal-descarga-catastro
                    v-if="modal"
                    v-model="tipo"
            />
        </b-modal>
    </div>
</template>

<script>
    import {catastros} from "../servicios";

    const prettyFileIcons = require('pretty-file-icons');

    export default {
        name: "lista-catastros",
        data: () => ({
            lista: [],
            tipo: {name:null},
            modal:false,
        }),
        methods: {
            consultar: function () {
                catastros.listaCatastros().then(response => {
                    this.lista = response.data.data;
                });
            },
            procesar: function (file) {
                this.tipo=file;
                this.modal=true;
            }
        },
        filters: {
            icono: function (ext) {
                const tipo = prettyFileIcons.getIcon("a." + ext);
                return require("pretty-file-icons/svg/" + tipo + ".svg");
            }
        },
        mounted() {
            this.consultar();
        }
    }
</script>

<style scoped>
    img {
        max-height: 3em;
    }
</style>