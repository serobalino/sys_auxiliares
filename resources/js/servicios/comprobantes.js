import axios from 'axios';

const PREFIJO="/app/comprobantes";

export default {

    update(cliente,desde,hasta) {
        const id=cliente.id_cl;
        return axios.patch(PREFIJO+"/"+id,{desde:desde,hasta:hasta});
    },
    descargar(cliente,desde,hasta) {
        return axios.get(PREFIJO+"/"+cliente.id_cl,{
            params:{
                desde:desde,
                hasta:hasta
            },
            responseType: 'blob'
        });
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
