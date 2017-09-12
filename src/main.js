// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'

Vue.config.productionTip = false

Vue.filter('price', (value) => {
  /*
    Format price to 2 decimal places with comma as thousand separator with default currency
   */
  if (value) {
    return value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' €'
  }
})

Vue.filter('datetime', (value) => {
  /*
   Format MySQL datetime to d. M. Y H:i
   */
  if (value) {
    let date = new Date(value)
    let hours = date.getHours()
    let minutes = date.getMinutes()

    let month = date.getMonth() + 1
    month = month < 10 ? '0' + month : month

    let day = date.getDate()
    day = day < 10 ? '0' + day : day

    hours = hours % 24
    minutes = minutes < 10 ? '0' + minutes : minutes

    let strTime = hours + ':' + minutes
    return [month, day, date.getFullYear()].join('. ') + ' ' + strTime
  }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
