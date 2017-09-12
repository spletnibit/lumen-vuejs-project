import Vapi from 'vuex-rest-api'
import axios from 'axios'

const customers = new Vapi({
  baseURL: 'http://ponudbe.dev/api/public',
  axios: axios.create({
    headers: {
      Authorization: 'Bearer ' + localStorage.getItem('token')
    }
  }),
  state: {
    customers: [],
    customer: {
      id: null,
      name: null,
      address: null,
      city: null,
      zip: null,
      vat: null
    }
  }})
/*
 CUSTOMERS
 */
  .get({
    action: 'getCustomer',
    property: 'customer',
    path: ({ id }) => `/customers/${id}`})
  .put({
    action: 'updateCustomer',
    property: 'customer',
    path: ({ id }) => `/customers/${id}`})
  .delete({
    action: 'deleteCustomer',
    property: 'customer',
    path: ({ id }) => `/customers/${id}`})
  .get({
    action: 'getCustomers',
    property: 'customers',
    path: '/customers'})
  .post({
    action: 'addCustomer',
    property: 'customer',
    path: '/customers/add'})
  .getStore({
    createStateFn: true
  })

customers.mutations['RESET_CUSTOMER'] = (state, payload) => {
  state.customer = {
    id: null,
    name: null,
    address: null,
    city: null,
    zip: null,
    vat: null
  }
}

export default customers
