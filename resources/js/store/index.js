import  Vue from 'vue'
import Vuex from 'vuex'

import products from './modules/products'
import suppliers from './modules/suppliers'
import orders from './modules/orders'

Vue.use(Vuex)

const store =  new Vuex.Store({
    modules: {
       products,
       suppliers,
       orders
    }
})


export default store