import Vapi from 'vuex-rest-api'
import axios from 'axios'

const products = new Vapi({
  baseURL: 'http://ponudbe.dev/api/public',
  axios: axios.create({
    headers: {
      Authorization: 'Bearer ' + localStorage.getItem('token')
    }
  }),
  state: {
    products: [],
    categories: [],
    category: {
      id: null,
      parent_id: null,
      name: null
    },
    product: {
      id: null,
      name: null,
      unit: null,
      price: null,
      vat: null,
      category_id: null
    }
  }})
  .get({
    action: 'getProducts',
    property: 'products',
    path: '/products'})
  .get({
    action: 'getProduct',
    property: 'product',
    path: ({ id }) => `/products/${id}`})
  .put({
    action: 'updateProduct',
    property: 'product',
    path: ({ id }) => `/products/${id}`})
  .delete({
    action: 'deleteProduct',
    property: 'product',
    path: ({ id }) => `/products/${id}`})
  .get({
    action: 'getProductCategories',
    property: 'categories',
    path: '/products/categories'})
  .post({
    action: 'addProductCategory',
    property: 'category',
    path: '/products/categories/add'})
  .post({
    action: 'addProduct',
    property: 'product',
    path: '/products/add'})
  .getStore({
    createStateFn: true
  })

products.mutations['resetProduct'] = (state) => {
  state.product = {
    id: null,
    name: null,
    unit: null,
    price: null,
    vat: null
  }
}

export default products
