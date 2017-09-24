import Vapi from 'vuex-rest-api'
const products = new Vapi({
  baseURL: 'http://ponudbe.local/api/public',
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
      name: '',
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
    onSuccess: (state, payload) => {
      // find index of product in the array by id
      let i = state.products.map(item => item.id).indexOf(payload.data.id)
      // delete current object and replace it with a new one
      state.products.splice(i, 1, payload.data)
    },
    path: ({ id }) => `/products/${id}`})
  .delete({
    action: 'deleteProduct',
    onSuccess: (state, payload) => {
      // find index of product in the array by id
      let i = state.products.map(item => item.id).indexOf(payload.data.id)
      // delete object at index i
      state.products.splice(i, 1)
    },
    path: ({ id }) => `/products/${id}`})
  .get({
    action: 'getProductCategories',
    property: 'categories',
    path: '/products/categories'})
  .post({
    action: 'addProductCategory',
    onSuccess: (state, payload) => {
      state.categories.splice(state.categories.length, 1, payload.data)
    },
    path: '/products/categories/add'})
  .delete({
    action: 'deleteProductCategory',
    onSuccess: (state, payload) => {
      // find index of category in the array by id
      let i = state.categories.map(item => item.id).indexOf(payload.data.id)
      state.categories.splice(i, 1)
    },
    path: ({ id }) => `/products/categories/${id}`})
  .post({
    action: 'addProduct',
    onSuccess: (state, payload) => {
      state.products.splice(state.products.length, 1, payload.data)
    },
    path: '/products/add'})
  .getStore({
    createStateFn: true
  })

products.mutations.updateProductState = (state, payload) => {
  state.product[payload.key] = payload.val
}

products.mutations.updateCategoryState = (state, payload) => {
  state.category[payload.key] = payload.val
}

products.mutations.resetProduct = (state) => {
  state.product = {
    id: null,
    name: null,
    unit: null,
    price: null,
    vat: null
  }
}

products.mutations['addProductToArray'] = (state, product) => {
  state.products.push(product)
}

export default products
