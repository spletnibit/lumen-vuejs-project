import Vuex from 'vuex'
import Vue from 'vue'

import customers from './modules/customers'
import products from './modules/products'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    customers,
    products
  }
})
