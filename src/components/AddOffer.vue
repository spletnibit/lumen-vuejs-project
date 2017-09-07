<template>
  <div class="row" style="margin-top: 20px;">

    <h3 class="col-md-24" style="margin-bottom: 20px;">Naredi ponudbo za
      <at-select size="large" filterable>
        <at-option v-for="(customer, index) in customers" :value="customer.id" :key="index"> {{ customer.name }}</at-option>
      </at-select>
    </h3>

    <h3 class="col-md-24" style="margin-top: 20px;">Dodaj artikel / storitev</h3>
    <autocomplete
      url="http://ponudbe.dev/api/public/products/search"
      anchor="name"
      label="writer"
      :classes="{ input: 'at-input__original' }"
      :on-select="getProduct">
    </autocomplete>

    <div class="offers__add-products" style="margin-top: 20px;">
      <div class="col-md-8">Naziv</div>
      <div class="col-md-2">Kol</div>
      <div class="col-md-2">Enota</div>
      <div class="col-md-3">Cena</div>
      <div class="col-md-3">DDV</div>
      <div class="col-md-3">Popust</div>
      <div class="col-md-3 text-right">Skupaj</div>
    </div>

    <div class="col-md-24">
      <div class="row" v-for="(item, k) in items">
        <div class="col-md-8">
          <at-input v-model="item.name"></at-input>

        </div>
        <div class="col-md-2">
          <at-input-number v-model="items[k].qty"></at-input-number>
        </div>
        <div class="col-md-2 align-middle">
          {{ item.unit }}
        </div>
        <div class="col-md-3 align-middle">
          <div v-html="$options.filters.price(items[k].price)"></div>
        </div>
        <div class="col-md-3 align-middle">
          {{ items[k].vat }} %
        </div>
        <div class="col-md-3">
          <at-input v-model="item.discount">
            <template slot="append">%</template>
          </at-input>
        </div>
        <div class="col-md-3 align-middle text-right" v-html="$options.filters.price(item.total)"></div>
      </div>
    </div>

    <div class="col-md-24" style="margin-top: 20px;">
      <div class="row">
        <div class="col-md-20 text-right">Skupaj:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(subtotal)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">Popust:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(subtotal_discount)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">DDV:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(subtotal_vat)"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-20 text-right">Za plačilo z DDV:</div>
        <div class="col-md-4 text-right">
          <div v-html="$options.filters.price(total)"></div>
        </div>
      </div>
    </div>


    <div class="col-md-24">
      <div style="float: right;margin-top: 20px;">
        <at-button type="success" icon="icon-save">Shrani</at-button>
      </div>
    </div>

  </div>
</template>

<script type="text/babel">
  import { mapActions } from 'vuex'
  import Autocomplete from 'vue2-autocomplete-js'

  export default {
    name: 'AddOffer',
    components: { Autocomplete },
    data () {
      return {
        customers: [],
        items: [],
        item: {
          name: null,
          qty: 0,
          unit: null,
          price: 0,
          vat: 0,
          discount: 0,
          total: 0
        },
        subtotal: 0,
        subtotal_discount: 0,
        subtotal_vat: 0,
        total: 0

      }
    },
    watch: {
      items: {
        handler: function (after, before) {
          this.recalculate()
        },
        deep: true
      }
    },
    created () {
      var self = this

      // fill customers select
      this.getCustomers().then((res) => {
        self.customers = [...res.data]
      }).catch((e) => {
        self.$Message.error('Prišlo je do napake.')
      })
    },
    filters: {
      price (price) {
        return price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' &euro;'
      }
    },
    methods: {
      getProduct (product) {
        this.items.push({
          name: product.name,
          qty: 1,
          unit: product.unit,
          price: parseFloat(product.price),
          vat: parseFloat(product.vat),
          discount: 0
        })
        this.recalculate()
      },
      recalculate () {
        this.subtotal = 0
        this.subtotal_vat = 0
        this.subtotal_discount = 0
        this.total = 0

        for (var index in this.items) {
          let discount = this.items[index].price * parseFloat(this.items[index].discount) / 100
          let vat = parseFloat(this.items[index].total) * this.items[index].vat / 100

          this.items[index].total = (this.items[index].price - discount) * this.items[index].qty

          this.subtotal_discount += discount
          this.subtotal_vat += vat
          this.subtotal += this.items[index].total
          this.total += this.items[index].total + vat
        }
      },
      ...mapActions([
        'getCustomers'
      ])
    }
  }
</script>

<style>
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

  /*.showAll-transition{
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
  }*/

</style>
