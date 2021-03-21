
const state = {
    orders: null
}

const getters = {

}

const mutations = {
    setOrdersMutation: (state, data) => {
        return state.orders = data;
    }
}

const actions = {
    getOrdersAction(context) {
        Vue.prototype.$Progress.start()
        return axios.get('/api/orders/all').then((response) => {
            context.commit('setOrdersMutation', response.data)
            Vue.prototype.$Progress.finish();
        })
    },

    getNextOrdersAction(context, page) {
        Vue.prototype.$Progress.start()
        return axios.get(`/api/orders/all?page=${page}`).then((response) => {
            context.commit('setOrdersMutation', response.data)
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