import Vue from 'vue'
import router from './router/index'
import store from './store/index'
import VueProgressBar from 'vue-progressbar'
import moment from 'moment'
import { HasError, AlertError } from 'vform'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue-search-select/dist/VueSearchSelect.css'
import { ModelSelect } from 'vue-search-select'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';


require('./bootstrap');

window.Vue = Vue;

const options = {
  color: '#3490dc',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)

// Sweet Alert 2
Vue.use(VueSweetalert2);

// Pagination component
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('model-select', ModelSelect)
Vue.component('v-select', vSelect)

Vue.filter('formatDate', (date) => {
  return moment(date).format("MMM Do YY");
})


const app = new Vue({
  el: '#app',
  router,
  store
});
