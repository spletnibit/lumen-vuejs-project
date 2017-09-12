import Vuex from 'vuex'
import Vue from 'vue'

import customers from './modules/customers'
import products from './modules/products'
import offers from './modules/offers'
import user from './modules/user'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: false,
  modules: {
    customers,
    products,
    offers,
    user
  }
})
