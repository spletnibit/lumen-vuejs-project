import Vue from 'vue'
import Router from 'vue-router'
import AtComponents from 'at-ui'

Vue.use(Router)
Vue.use(AtComponents)

import Auth from '@/components/Auth'
import Products from '@/components/Products'
import Offers from '@/components/Offers'
import AddOffer from '@/components/AddOffer'
import Customers from '@/components/Customers'
import Settings from '@/components/Settings'

const router = new Router({
  routes: [
    { path: '/login', component: Auth },
    { name: 'guarded', path: '/products', component: Products },
    { name: 'guarded', path: '/offers', component: Offers },
    { name: 'guarded', path: '/offers/add', component: AddOffer },
    { name: 'guarded', path: '/offers/edit/:id', component: AddOffer },
    { name: 'guarded', path: '/customers', component: Customers },
    { name: 'guarded', path: '/settings', component: Settings }
  ]
})

import user from '../store/modules/user'

/*
 protect frontend routed and redirect them to login, if jwt token is absent
 */
router.beforeEach((to, from, next) => {
  console.log(user.state())
  if (!user.state().user.token) {
    if (to.name === 'guarded') {
      router.app.$Message.info('Potrebna je prijava.')
      router.push({path: '/login'})
      return
    }
  }
  next()
})

export default router
