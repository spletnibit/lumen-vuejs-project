import Vapi from 'vuex-rest-api'

const customers = new Vapi({
  baseURL: 'http://ponudbe.local/api/public',
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
    onSuccess: (state, payload) => {
      // find index of customer in the array by id
      let i = state.customers.map(item => item.id).indexOf(payload.data.id)
      // delete current object and replace it with a new one
      state.customers.splice(i, 1, payload.data)
    },
    path: ({ id }) => `/customers/${id}`})
  .delete({
    action: 'deleteCustomer',
    onSuccess: (state, payload) => {
      // find index of customer in the array by id
      let i = state.customers.map(item => item.id).indexOf(payload.data.id)
      state.customers.splice(i, 1)
    },
    path: ({ id }) => `/customers/${id}`})
  .get({
    action: 'getCustomers',
    property: 'customers',
    path: '/customers'})
  .post({
    action: 'addCustomer',
    onSuccess: (state, payload) => {
      state.customers.splice(state.customers.length, 1, payload.data)
    },
    path: '/customers/add'})
  .getStore({
    createStateFn: true
  })

customers.mutations['resetCustomer'] = (state, payload) => {
  state.customer = {
    id: null,
    name: null,
    address: null,
    city: null,
    zip: null,
    vat: null
  }
}

customers.mutations.updateCustomerState = (state, payload) => {
  state.customer[payload.key] = payload.val
}

export default customers
