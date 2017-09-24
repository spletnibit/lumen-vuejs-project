import Vapi from 'vuex-rest-api'
import Vue from 'Vue'
const offers = new Vapi({
  baseURL: 'http://ponudbe.local/api/public',
  state: {
    offers: [],
    offer: {
      id: null,
      customer_id: null,
      products: [],
      subtotal: 0,
      subtotal_discount: 0,
      subtotal_vat: 0,
      total: 0
    },
    categories: {
      0: { id: 0, name: '', products: [] }
    }
  }})
  .post({
    action: 'addOffer',
    // property: 'offer',
    path: '/offers/add'})
  .delete({
    action: 'deleteOffer',
    onSuccess: (state, payload) => {
      // find index of customer in the array by id
      let i = state.offers.map(item => item.id).indexOf(payload.data.id)
      state.offers.splice(i, 1)
    },
    path: ({ id }) => `/customers/${id}`})
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

offers.mutations.updateOfferState = (state, payload) => {
  state.offer[payload.key] = payload.val
}

offers.mutations['addProductToOffer'] = (state, product) => {
  // check if product already in the offer
  let i = state.offer.products.map(item => item.id).indexOf(product.id)

  // if product already exists then increase qty
  if (i !== -1) {
    const product = state.offer.products[i]
    product.pivot.qty++
    state.offer.products.splice(i, 1, product)
  } else {
    state.offer.products.splice(state.offer.products.length, 1, product)
  }
}

offers.mutations['removeProductFromOffer'] = (state, index) => {
  state.offer.products.splice(index, 1)
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

offers.mutations.buildCategoryTree = (state, payload) => {
  // costruct array index map of products and categories
  state.offer.products.forEach((product, index) => {
    if (product.category_id === null) {
      product.category_id = 0
    } else {
      // add category to map if it doesn't exist
      if (typeof state.categories[product.category_id] === 'undefined') {
        const category = { ...product.category, 'products': [] }
        Vue.set(state.categories, product.category_id, category)
      }
    }

    if (!state.categories[product.category_id].products.includes(product.id)) {
      state.categories[product.category_id].products.splice(state.categories[product.category_id].products.length, 1, product.id)
    }
  })

  /*
  for (var productIndex in state.offer.products) {
    var productId = state.offer.products[productIndex].id
    var categoryId = state.offer.products[productIndex].category_id
    console.log(productId, categoryId)

    if (categoryId === null) {
      categoryId = 0
    } else {
      // add category to map if it doesn't exist
      if (typeof state.categories[categoryId] === 'undefined') {
        state.categories = { ...state.categories, categoryId: state.offer.products[productIndex].category }
        // Vue.set(state.categories, categoryId.toString(), state.offer.products[productIndex].category)
      }
    }
    // check if product is not already in the array, otherwise add it
    if (!state.categories[categoryId].products.includes(productId)) {
      // state.categories[categoryId].products.push(productId)
      // state.categories[categoryId].products.splice(state.categories.products.length, 1, productId)
    }
  }
  */
}

offers.mutations.resetOffer = (state) => {
  state.offer = {
    id: null,
    customer_id: null,
    products: [],
    subtotal: 0,
    subtotal_discount: 0,
    subtotal_vat: 0,
    total: 0
  }
}

offers.mutations['removeProductFromCategoryTree'] = (state, payload) => {
  delete state.categories[payload.categoryIndex].products[payload.productIndex]
}

export default offers
