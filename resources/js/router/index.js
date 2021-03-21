import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../components/Home.vue'
import Products from '../components/Products.vue'
import Suppliers from '../components/Suppliers.vue'
import Orders from '../components/Orders.vue'
import SupplierProducts from '../components/SupplierProducts.vue'
import OrderDetail from '../components/OrderDetail.vue'


Vue.use(VueRouter)

const routes = [
    {
      path: '/home',
      component: Home
    },
    {
      path: '/products',
      component: Products
    },
    {
      path: '/suppliers',
      component: Suppliers
    },
    {
      path: '/supplier/:id',
      component: SupplierProducts,
      name: 'supplier-products'
    },
    {
      path: '/orders',
      component: Orders
    },

    {
      path: '/orders/:id',
      component: OrderDetail,
      name: 'order-detail'
    }
]

const router = new VueRouter({
    routes,
    mode: 'history'
})


export default router