import axios from 'axios';

const PREFIJO="/api/";

export default {
    listaCatastros() {
        return axios.get(`${PREFIJO}catastros`);
    },
    procesarCatastro(tipo) {
        return axios.post(`${PREFIJO}catastros`,tipo);
    },
    descargarCatastro(file) {
        return axios.post(`${PREFIJO}catastros/download`,file);
    }
}
