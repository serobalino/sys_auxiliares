import axios from 'axios';

const PREFIJO="/app/archivos";

export default {
    reporte(archivo,cliente){
        const data = new FormData();
        data.append('archivo', archivo);
        data.append('cliente', cliente.id_cl);
        return axios.post(PREFIJO+"/reporte",data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },
    xml(archivos,cliente){
        const data = new FormData();
        for( let i = 0; i < archivos.length; i++ ){
            data.append('archivos[' + i + ']', archivos[i]);
        }
        data.append('cliente', cliente.id_cl);
        return axios.post(PREFIJO+"/comprobante",data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }
};
