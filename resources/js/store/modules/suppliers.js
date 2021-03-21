
const state = {
  suppliers: null
}

const getters = {
    getSuppliersReport: (state) =>{
         return state.suppliers.data.map((sup) => {
             return {
                 supplier: sup.name,
                 total: sup.supplier_products.length
             }
         })
    }
}

const mutations = {
    setSuppliersMutation: (state, data) => {
        return state.suppliers = data;
    }
}

const actions = {
    getSuppliersAction(context) {
        Vue.prototype.$Progress.start()
        return axios.get('/api/suppliers/all').then((response) => {
            context.commit('setSuppliersMutation', response.data)
            Vue.prototype.$Progress.finish();
        })
    },

    getNextSuppliersAction(context, page) {
        Vue.prototype.$Progress.start()
        return axios.get(`/api/suppliers/all?page=${page}`).then((response) => {
            context.commit('setSuppliersMutation', response.data)
            Vue.prototype.$Progress.finish();
        })
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}