import axios from 'axios';

const PREFIJO="/app/comprobantes/";

const CABECERA={headers:{'Content-Type': 'multipart/form-data'}};

export default {

    update(cliente,desde=null,hasta=null) {
        const id=cliente.id_cl;
        return axios.patch(PREFIJO+id,{desde:desde,hasta:hasta});
    },

    store(archivo){
        const data = new FormData();
        data.append('archivo', archivo);
        return axios.post(PREFIJO,data);
    }
};