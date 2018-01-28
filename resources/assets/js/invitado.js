
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('enviar-email', require('./componentes/invitado/enviarEmail'));
Vue.component('mapa', require('./componentes/invitado/direccionMapa'));
Vue.component('login-comun', require('./componentes/invitado/loginComun'));
const invitado = new Vue({
    el: '#invitado'
});