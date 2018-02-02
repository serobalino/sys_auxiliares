
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

moment.locale('es');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export let componentes = new Vue();

Vue.component('tabla-clientes', require('./componentes/administrador/tablaClientes'));
Vue.component('modal-registro', require('./componentes/administrador/modalRegistro'));
Vue.component('avatar-session', require('./componentes/administrador/session'));

Vue.component('noticias', require('./componentes/administrador/perfil/noticias'));
Vue.component('pie',require('./componentes/administrador/pie'))

Vue.component('modalsesion', require('./componentes/administrador/perfil/modalSesion'));
Vue.component('principal', require('./componentes/administrador/perfil/principal'));

const administrador = new Vue({
    el: '#adm'
});