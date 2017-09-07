import Vue from 'vue'
import Router from 'vue-router'
import AtComponents from 'at-ui'

Vue.use(Router)
Vue.use(AtComponents)

import Hello from '@/components/Hello'
import Products from '@/components/Products'
import Offers from '@/components/Offers'
import AddOffer from '@/components/AddOffer'
import Customers from '@/components/Customers'
import Settings from '@/components/Settings'

export default new Router({
  routes: [
    { path: '/', component: Hello },
    { path: '/products', component: Products },
    { path: '/offers', component: Offers },
    { path: '/offers/add', component: AddOffer },
    { path: '/customers', component: Customers },
    { path: '/settings', component: Settings }
  ]
})
