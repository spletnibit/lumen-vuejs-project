<template>
  <div class="row" style="margin-top: 20px;">

    <h3 class="col-md-24" style="margin-bottom: 20px;">Naredi ponudbo za
      <at-select size="large" v-model="offer.customer_id" filterable>
        <at-option v-for="(customer, index) in customers" :value="customer.id" :key="index"> {{ customer.name }}</at-option>
      </at-select>
    </h3>

    <h3 class="col-md-24" style="margin-top: 20px;">Dodaj artikel / storitev</h3>
    <autocomplete
      url="http://ponudbe.dev/api/public/products/search"
      anchor="name"
      label="test"
      placeholder="Začni tipkati naziv"
      :customHeaders="{ Authorization: 'Bearer ' + user.token }"
      :classes="{ input: 'at-input__original' }"
      :on-select="onProductAdd">
    </autocomplete>

    <div class="offers__add-products" style="margin-top: 20px;">
      <div class="col-md-8">Naziv</div>
      <div class="col-md-3">Kol</div>
      <div class="col-md-3">Cena</div>
      <div class="col-md-3">DDV</div>
      <div class="col-md-3">Popust</div>
      <div class="col-md-3 text-right">Skupaj</div>
      <div class="col-md-1 text-center"></div>
    </div>

    <div class="col-md-24">

      <div class="row">
        <div class="col-md-24" v-for="(category, c) in categories">
          <h3>{{ category.name }}</h3>

          <div v-for="(product_id, p) in category.products" v-if="categories[c].products[p] !== 'undefined'">
            <offer-product
              :category-index="c"
              :product-index="p"
              :index="getProductIndexById(product_id)"
              :data="getProductById(product_id)"></offer-product>
          </div>

        </div>
      </div>

    </div>

    <div class="col-md-24" style="margin-top: 20px;">
      <div class="row">
        <div class="col-md-20 text-right">Skupaj:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(offer.subtotal)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">Popust:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(offer.subtotal_discount)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">DDV:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(offer.subtotal_vat)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">Za plačilo z DDV:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(offer.total)"></div>
        </div>
      </div>
    </div>


    <div class="col-md-24">
      <div style="float: right;margin-top: 20px;">
        <at-button type="success" icon="icon-save" @click="onOffersSave">Shrani</at-button>
      </div>
    </div>

  </div>
</template>

<script type="text/babel">
  import { mapState, mapActions, mapMutations } from 'vuex'
  import Autocomplete from 'vue2-autocomplete-js'
  import OfferProduct from './OfferProduct.vue'

  export default {
    name: 'AddOffer',
    components: { Autocomplete, OfferProduct },
    data () {
      return {
        customers: []
      }
    },
    created () {
      var self = this

      // check if edit mode
      if (typeof this.$route.params.id !== 'undefined') {
        this.getOffer({ params: { id: this.$route.params.id } }).then((res) => {
          self.setOffer(res.data)
          self.buildCategoryTree()
          self.recalculateOffer()
        }).catch((e) => {
          self.$Message.error('Prišlo je do napake.')
        })
      }

      // fill customers select
      this.getCustomers().then((res) => {
        self.customers = [...res.data]
      }).catch((e) => {
        self.$Message.error('Prišlo je do napake.')
      })
    },
    mounted () {
      console.log(this.user)
    },
    methods: {
      onOffersSave () {
        var request = {
          method: 'addOffer',
          args: {
            data: this.offer
          },
          successMessage: 'Nova ponudba uspešno dodana.'
        }

        if (this.offer.id !== null) {
          request = {
            method: 'updateOffer',
            args: {
              params: { id: this.offer.id },
              data: this.offer
            },
            successMessage: 'Nova ponudba uspešno urejena.'
          }
        }

        this[request.method](request.args).then((res) => {
          self.$Message.success(request.successMessage)
        }).catch((e) => {
          self.$Message.error('Obrazec vsebuje napake.')
          self.errors = e.response.data.messages
        })
      },
      onProductAdd (product) {
        this.addProductToOffer({
          id: product.id,
          name: product.name,
          pivot: { qty: 1, discount: 0 },
          unit: product.unit,
          price: parseFloat(product.price),
          vat: parseFloat(product.vat)
        })
        this.recalculateOffer()
      },
      getProductIndexById (id) {
        return this.offer.products.findIndex(x => x.id === id)
      },
      getProductById (id) {
        return this.offer.products.find(x => x.id === id)
      },
      ...mapActions([
        'getCustomers',
        'addOffer',
        'getOffer',
        'updateOffer'
      ]),
      ...mapMutations([
        'addProductToOffer',
        'recalculateOffer',
        'setOffer',
        'buildCategoryTree'
      ])
    },
    computed: {
      ...mapState({
        user: state => state.user.user,

        offer: state => state.offers.offer,
        pending_offer: state => state.offers.pending.offer,
        error_offer: state => state.offers.error.offer,

        categories: state => state.offers.categories
      })
    }
  }
</script>

<style>
  .offers__products { font-size: 12px; }
  .offers__add-products { width: 100%;display: flex;font-size: 18px; border-bottom: 1px solid #e3e3e3; margin-bottom: 15px; padding-bottom: 5px;}
  .offers__totals { width: 100%;display: flex;}


  .at-table { width: 100%; }
  .at-input-number { min-width: auto; }
  .at-input-group { line-height: 1.5; }
  .align-middle { padding: 5px 4px 0; }
  .at-select {width: 150px;display: inline-block;}
  .text-right { text-align: right;}
  .text-center { text-align: center;}


  .transition, .autocomplete, .showAll-transition, .autocomplete ul, .autocomplete ul li a{
    transition:all 0.3s ease-out;
    -moz-transition:all 0.3s ease-out;
    -webkit-transition:all 0.3s ease-out;
    -o-transition:all 0.3s ease-out;
  }

  .autocomplete ul{
    z-index: 9;
    font-family: sans-serif;
    position: absolute;
    list-style: none;
    background: #f8f8f8;
    padding: 10px 0;
    margin: 0;
    display: inline-block;
    min-width: 15%;
    margin-top: 10px;
  }

  .autocomplete ul:before{
    content: "";
    display: block;
    position: absolute;
    height: 0;
    width: 0;
    border: 10px solid transparent;
    border-bottom: 10px solid #f8f8f8;
    left: 46%;
    top: -20px
  }

  .autocomplete ul li a{
    text-decoration: none;
    display: block;
    background: #f8f8f8;
    color: #2b2b2b;
    padding: 5px;
    padding-left: 10px;
  }

  .autocomplete ul li a:hover, .autocomplete ul li.focus-list a{
    color: white;
    background: #2F9AF7;
  }

  .autocomplete ul li a span, /*backwards compat*/
  .autocomplete ul li a .autocomplete-anchor-label{
    display: block;
    margin-top: 3px;
    color: grey;
    font-size: 13px;
  }

  .autocomplete ul li a:hover .autocomplete-anchor-label,
  .autocomplete ul li.focus-list a span, /*backwards compat*/
  .autocomplete ul li a:hover .autocomplete-anchor-label,
  .autocomplete ul li.focus-list a span{ /*backwards compat*/
    color: white;
  }
  .showAll-transition{
    opacity: 1;
    height: 50px;
    overflow: hidden;
  }

  .showAll-enter{
    opacity: 0.3;
    height: 0;
  }

  .showAll-leave{
    display: none;
  }

</style>
