import axios from 'axios';

const PREFIJO="/app/clientes";

export default {

    index() {
        return axios.get(PREFIJO);
    },

    store(datos){
        return axios.post(PREFIJO,datos);
    }
};