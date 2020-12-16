import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersistence from 'vuex-persist';

import catalogos from "./catalogos"

const vuexLocalStorage = new VuexPersistence({
    key: location.origin,
    storage: window.sessionStorage
});

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        catalogos,
    },
    plugins: [vuexLocalStorage.plugin]
});

export default store;
