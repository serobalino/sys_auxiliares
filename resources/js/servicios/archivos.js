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
    xml(archivo,cliente){
        const data = new FormData();
        data.append('cliente', cliente.id_cl);
        data.append('archivos[0]', archivo);
        return axios.post(PREFIJO+"/comprobante",data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }
};
