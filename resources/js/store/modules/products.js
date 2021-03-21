import Vue from 'vue'
import Toast from '../../common/toast'

const state = {
    products: null
}

const getters = {

}

const mutations = {
    setProductsMutation: (state, products) => {
        return state.products = products
    }
}

const actions = {
    getProductsAction(context) {
        Vue.prototype.$Progress.start()
        return axios.get('/api/products/all').then((response) => {
            context.commit('setProductsMutation', response.data)
            Vue.prototype.$Progress.finish();
        })
    },

    getNextProductsAction(context, page) {
        Vue.prototype.$Progress.start()
        return axios.get(`/api/products/all?page=${page}`).then((response) => {
            context.commit('setProductsMutation', response.data)
            Vue.prototype.$Progress.finish();
        })
    },

    addProductAction(context, data) {
        Vue.prototype.$Progress.start()
        const form = data.form
    
        form.post('/api/products/add').then((response) => {
            if (response.data.status) {
                context.dispatch("getProductsAction")
                Vue.prototype.$Progress.finish();
                form.clear()
                form.reset()
                Toast.fire({
                    icon: 'success',
                    title: 'Product added successfully'
                })
            } else {
                Vue.prototype.$swal({
                    icon: "error",
                    title: "Error Occurred",
                    text: response.data.message
                })
                Vue.prototype.$Progress.fail();
            }
        }).catch((error) => {
            console.error(error, "Thrown Error");
            Vue.prototype.$Progress.fail();
        })
    },

    updateProductAction (context, data) {
        Vue.prototype.$Progress.start()
        const form = data.form
        
        form.put(`/api/products/update/${form.id}`).then((response) => {
            if (response.data.status) {
                context.dispatch("getProductsAction")
                Vue.prototype.$Progress.finish();
                form.clear()
                form.reset()
                Toast.fire({
                    icon: 'success',
                    title: 'Product updated successfully'
                })
                data.hideModal()
            } else {
                Vue.prototype.$swal({
                    icon: "error",
                    title: "Error Occurred",
                    text: response.data.message
                })
                Vue.prototype.$Progress.fail();
            }
        }).catch((error) => {
            console.error(error, "Thrown Error");
            Vue.prototype.$Progress.fail();
        })
    },

    deleteProductAction(context, id){
        axios.delete(`/api/products/delete/${id}`).then((response) => {
            if(response.data.status) {
                context.dispatch("getProductsAction")
                
                Vue.prototype.$swal.fire(
                    "Deleted!",
                    "Your product has been deleted.",
                    "success"
                  );

                  Vue.prototype.$Progress.finish();
            }else {
                Vue.prototype.$swal.fire(
                    "Error!",
                    response.data.message,
                    "error"
                  );

                  Vue.prototype.$Progress.fail();
            }
        }).catch((error) => {
            Vue.prototype.$Progress.fail();
            Vue.prototype.$swal.fire(
                "Error!",
                error.message,
                "error"
              );
        })
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}