import Vapi from 'vuex-rest-api'

const products = new Vapi({
  baseURL: 'http://ponudbe.dev/api/public',
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
    onSuccess: (state, payload) => {
      state.categories.push(payload.data)
    },
    path: '/products/categories/add'})
  .post({
    action: 'addProduct',
    onSuccess: (state, payload) => {
      console.log('a', state, payload)
      // state.products.push(payload.data)
    },
    onError: (state, error) => {
      console.log('b', state, error)
      // state.products.push(payload.data)
    },
    path: '/products/add'})
  .getStore({
    createStateFn: true
  })

products.mutations.updateProductState = (state, payload) => {
  state.product[payload.key] = payload.val
}

products.mutations.resetProduct = (state) => {
  // state.product = {
  //   id: null,
  //   name: null,
  //   unit: null,
  //   price: null,
  //   vat: null
  // }
}

products.mutations['addProductToArray'] = (state, product) => {
  state.products.push(product)
}

export default products
