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
    },
    store2(archivos,cliente){
        const data = new FormData();
        for( let i = 0; i < archivos.length; i++ ){
            data.append('archivos[' + i + ']', archivos[i]);
        }
        data.append('cliente', cliente.id_cl);
        return axios.post(PREFIJO+2,data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }
};
