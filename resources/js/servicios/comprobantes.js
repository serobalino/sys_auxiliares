import axios from 'axios';

const PREFIJO="/app/comprobantes/";

export default {

    update(cliente,desde=null,hasta=null) {
        const id=cliente.id_cl;
        return axios.patch(PREFIJO+id,{desde:desde,hasta:hasta});
    },

    store(archivo,cliente){
        const data = new FormData();
        data.append('archivo', archivo);
        data.append('cliente', cliente.id_cl);
        return axios.post(PREFIJO,data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }
};