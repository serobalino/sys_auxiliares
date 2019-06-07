<template>
    <div class="card">
        <div class="card-header">Clientes
            <b-button size="sm" pill v-b-modal.nuevo  variant="success"><i class="fa fa-plus"></i></b-button>
        </div>
        <div class="card-body">
            <b-form-group label="Buscar">
                <b-form-input

                        type="text"
                        required
                        placeholder="Carlos Tirado"
                        size="sm"
                ></b-form-input>
            </b-form-group>
            <b-list-group>
                <b-list-group-item href="#some-link">Awesome link</b-list-group-item>
                <b-list-group-item href="#">Link with active state</b-list-group-item>
                <b-list-group-item href="#">Action links are easy</b-list-group-item>
                <b-list-group-item href="#foobar" disabled>Disabled link</b-list-group-item>
            </b-list-group>
        </div>
        <b-modal hide-footer id="nuevo" size="xl" title="Nuevo" cancel-title="Cancelar" :ok-title="false">
            <p class="my-4">Hello from modal!</p>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "lista-clientes"
    }
</script>

<style scoped>

</style>