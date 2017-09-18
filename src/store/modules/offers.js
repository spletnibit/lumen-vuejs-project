import Vapi from 'vuex-rest-api'

const offers = new Vapi({
  baseURL: 'http://ponudbe.dev/api/public',
  state: {
    offers: [],
    offer: {
      id: null,
      customer_id: 1,
      products: [],
      subtotal: 0,
      subtotal_discount: 0,
      subtotal_vat: 0,
      total: 0
    },
    categories: {}
  }})
  .post({
    action: 'addOffer',
    // property: 'offer',
    path: '/offers/add'})
  .put({
    action: 'updateOffer',
    path: ({ id }) => `/offers/${id}`})
  .get({
    action: 'getOffer',
    property: 'offer',
    path: ({ id }) => `/offers/${id}`})
  .get({
    action: 'getOffers',
    property: 'offers',
    path: '/offers'})
.getStore({
  createStateFn: true
})

offers.mutations['addProductToOffer'] = (state, product) => {
  state.offer.products.push(product)
}

offers.mutations['removeProductFromOffer'] = (state, index) => {
  state.offer.products.splice(index, 1)
}

offers.mutations['setOffer'] = (state, offer) => {
  state.offer = offer
}

offers.mutations['recalculateOffer'] = (state) => {
  state.offer.subtotal = 0
  state.offer.subtotal_vat = 0
  state.offer.subtotal_discount = 0
  state.offer.total = 0

  for (var index in state.offer.products) {
    let item = state.offer.products[index]

    let discount = item.price * parseFloat(item.pivot.discount) / 100
    state.offer.products[index].total = (item.price - discount) * item.pivot.qty
    state.offer.subtotal_discount += discount

    let vat = item.total * item.vat / 100
    state.offer.subtotal_vat += vat
    state.offer.subtotal += state.offer.products[index].total
    state.offer.total += state.offer.products[index].total + vat
  }
}

offers.mutations['buildCategoryTree'] = (state, payload) => {
  // costruct array index map of products and categories
  console.log(state, 'd')
  for (var productIndex in state.offer.products) {
    var productId = state.offer.products[productIndex].id
    var categoryId = state.offer.products[productIndex].category_id

    // add category to map if it doesn't exist
    if (typeof state.categories[categoryId] === 'undefined') {
      state.categories[categoryId] = state.offer.products[productIndex].category
    }

    // init product array if category was just added
    if (typeof state.categories[categoryId]['products'] === 'undefined') {
      state.categories[categoryId]['products'] = []
    }

    // check if product is not already in the array, otherwise add it
    if (!state.categories[categoryId].products.includes(productId)) {
      state.categories[categoryId].products.push(productId)
    }
  }
}

offers.mutations['removeProductFromCategoryTree'] = (state, payload) => {
  delete state.categories[payload.categoryIndex].products[payload.productIndex]
}

export default offers
