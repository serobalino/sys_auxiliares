import axios from 'axios';

const PREFIJO="/ctlgs";

export default {
    comprobantes() {
        return axios.get(`${PREFIJO}/comprobantes`);
    },
    claves() {
        return axios.get(`${PREFIJO}/claves`);
    },

}
