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
  strict: true,
  modules: {
    customers,
    products,
    offers,
    user
  }
})

axios.interceptors.request.use((config) => {
  router.app.$Loading.start()
  config.headers['Authorization'] = 'Bearer ' + store._modules.root._children.user.state.user.token
  return config
})

axios.interceptors.response.use((config) => {
  router.app.$Loading.finish()
  return config
}, function (error) {
  router.app.$Message.error('Prišlo je do napake.')
  if (typeof error.response !== 'undefined' && error.response.status === 401) {
    localStorage.removeItem('token')
    router.app.$Message.info('Potrebna je prijava.')
    router.push({path: '/login'})
  }
  return Promise.reject(error)
})

export default store
