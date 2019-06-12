import axios from 'axios';

const PREFIJO="/app/comprobantes/";

export default {

    update(cliente,desde=null,hasta=null) {
        const id=cliente.id_cl;
        return axios.patch(PREFIJO+id,{desde:desde,hasta:hasta});
    },

    store(datos){
        return axios.post(PREFIJO,datos);
    }
};