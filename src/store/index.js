import Vuex from 'vuex'
import Vue from 'vue'
import axios from 'axios'

import router from '../router/index'
import customers from './modules/customers'
import products from './modules/products'
import offers from './modules/offers'
import user from './modules/user'

Vue.use(Vuex)
let store = new Vuex.Store({
  strict: false,
  modules: {
    customers,
    products,
    offers,
    user
  }
})

axios.interceptors.request.use((config) => {
  config.headers['Authorization'] = 'Bearer ' + store._modules.root._children.user.state.user.token
  return config
})

axios.interceptors.response.use((config) => {
  return config
}, function (error) {
  console.log(store._modules.root._children.user.state)
  if (error.response.status === 401) {
    localStorage.removeItem('token')
    router.app.$Message.info('Potrebna je prijava.')
    router.push({path: '/login'})
  }
})

export default store
