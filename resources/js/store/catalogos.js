import {catalogos} from "../servicios";

export default {
    namespaced: true,
    state: {
        comprobantes:[],
    },
    mutations: {
        setComprobantes (state, elementos) {
            state.comprobantes=elementos;
        },
    },
    actions: {
        cargarComprobantes({commit}) {
            return catalogos.comprobantes().then(
                response => {
                    commit('setComprobantes',response.data);
                    return Promise.resolve(response.data);
                },
                error => {
                    commit('setComprobantes',[]);
                    return Promise.resolve([]);
                }
            )
        },
    },
    getters: {
        getComprobantes: state => {
            return state.comprobantes;
        },
    }
}
