<template>
    <div class="row" style="margin-top: 20px;">

      <at-button
        icon="icon-plus"
        type="primary"
        @click="onProductCreateOpen" hollow>Dodaj izdelek/storitev</at-button>

      <at-button
        icon="icon-plus"
        type="success"
        @click="modal2=true" hollow>Dodaj kategorijo</at-button>


      <at-modal
        v-model="modal1"
        title="Dodaj izdelek/storitev"
        :show-footer="false">
        <div class="row">
          <input type="hidden" v-model="product.id" />
          <div class="col-sm-24 col-md-20">
            <label>Naziv</label>
            <at-input v-model="productName"></at-input>
          </div>
          <div class="col-sm-12 col-md-4">
            <label>Enota</label>
            <at-input v-model="product.unit" :status="getInputStatus('unit')"></at-input>
          </div>
          <div class="col-sm-12 col-md-8">
            <label>Cena na enoto</label>
            <at-input v-model="product.price" :status="getInputStatus('price')"></at-input>
          </div>
          <div class="col-sm-24 col-md-8">
            <label>DDV Stopnja</label>
            <at-input v-model="product.vat" :status="getInputStatus('vat')">
              <template slot="append">%</template>
            </at-input>
          </div>
          <div class="col-sm-24 col-md-8">
            <label>Kategorija</label>

            <at-select v-model="product.category_id" placeholder="" size="normal">
              <at-option v-for="(item, index) in categories" :value="item.id" :key="index"> {{ item.name }}</at-option>
            </at-select>
          </div>

          <div class="col-md-24" style="margin-top: 20px;">
            <div v-if="pending_product">
              <at-button
                type="success"
                loading>Shrani</at-button>
            </div>

            <div v-if="!pending_product">
              <at-button
                icon="icon-save"
                type="success"
                @click="onProductSave">Shrani</at-button>
            </div>
          </div>
        </div>
      </at-modal>
      <at-modal
        v-model="modal2"
        title="Dodaj kategorijo"
        :showFooter="false">
        <div class="row">
          <div class="col-md-24">
            <label>Naziv</label>
            <at-input v-model="product_category.name"  :status="getInputStatus('name')"></at-input>
          </div>
          <div class="col-md-24">
            <label>Nadrejena kategorija</label>
            <at-select v-model="product_category.parent_id">
              <at-option v-for="item in categories" :value="item.id" :key="item.id"> {{ item.name }}</at-option>
            </at-select>
          </div>
          <div class="col-md-24" style="margin-top: 20px;">
            <div v-if="pending_product_category">
              <at-button
                type="success"
                loading>Shrani</at-button>
            </div>

            <div v-if="!pending_product_category">
              <at-button
                icon="icon-save"
                type="success"
                @click="onCategoryCreate">Shrani</at-button>
            </div>
          </div>
        </div>
      </at-modal>

      <h3 class="col-md-24" style="margin-top: 20px;">Izdeleki/storitve</h3>
      <div v-if="pending_product">loading</div>
      <div class="col-md-24" v-if="products.length">
        <at-table :columns="productColumns" :data="products" stripe border></at-table>
      </div>

      <h3 class="col-md-24" style="margin-top: 20px;">Kategorije</h3>
      <div class="col-md-24" v-if="categories.length">
        <at-table :columns="categoryColumns" :data="categories" stripe border></at-table>
      </div>
    </div>

</template>

<script type="text/babel">
  import { mapState, mapActions, mapMutations } from 'vuex'

  export default {
    name: 'products',
    data () {
      return {
        modal1: false,
        modal2: false,
        errors: [],

        categoryColumns: [
          { title: 'Naziv', key: 'name' },
          { title: 'Akcije',
            render: (h, params) => {
              return h('div', [
                h('AtButton', {
                  props: {
                    size: 'small',
                    icon: 'icon-trash',
                    type: 'error',
                    hollow: true,
                    circle: true
                  },
                  on: {
                    click: () => {
                      this.$Message(params.item.address)
                    }
                  }
                }, '')
              ])
            }
          }
        ],
        productColumns: [
          { title: 'Naziv', key: 'name' },
          { title: 'Enota', key: 'unit' },
          { title: 'Cena', key: 'price' },
          { title: 'DDV', key: 'vat' },
          { title: 'Kategorija', key: 'category' },
          {
            title: 'Akcije',
            render: (h, params) => {
              var self = this
              return h('div', [
                h('AtButton', {
                  props: {
                    size: 'small',
                    icon: 'icon-edit',
                    type: 'primary',
                    hollow: true,
                    circle: true
                  },
                  style: {
                    marginRight: '8px'
                  },
                  on: {
                    click: () => {
                      this.$Loading.start()
                      self.errors = false
                      self.getProduct({ params: { id: params.item.id } }).then((res) => {
                        self.modal1 = true
                        this.$Loading.finish()
                      }).catch((e) => {
                        console.log(e)
                        this.$Loading.finish()
                      })
                    }
                  }
                }, ''),
                h('AtButton', {
                  props: {
                    size: 'small',
                    icon: 'icon-trash',
                    type: 'error',
                    hollow: true,
                    circle: true
                  },
                  on: {
                    click: () => {
                      this.$Message(params.item.address)
                    }
                  }
                }, '')
              ])
            }
          }
        ]
      }
    },
    created () {
      var self = this
      this.$Loading.start()
      this.getProducts().then((res) => {
        self.$Loading.finish()
      }).catch((e) => {
        self.$Message.error('Prišlo je do napake.')
        self.$Loading.finish()
      })
    },
    mounted () {
      if (this.categories.length === 0) {
        this.getProductCategories()
      }
    },
    methods: {
      onProductCreateOpen () {
        this.modal1 = true
        this.resetProduct()
      },
      onProductSave () {
        var self = this

        var request = {
          method: 'addProduct',
          args: {
            data: this.product
          },
          successMessage: 'Nov artikel/storitev uspešno dodan/a.'
        }

        if (this.product.id !== null) {
          request.method = 'updateProduct'
          request.args = {
            params: { id: this.product.id },
            data: this.product
          }
          request.successMessage = 'Artikel/storitev uspešno urejen/a.'
        }

        console.log(request)

        this[request.method](request.args).then((res) => {
          self.$Message.success(request.successMessage)
          self.modal1 = false
        }).catch((e) => {
          self.$Message.error('Obrazec vsebuje napake.')
          console.log(e, Object.keys(e), 'f')
//          self.errors = e.response.data.messages
        })
      },
      onCategoryCreate () {
        var self = this
        this.addProductCategory({ data: this.product_category }).then((res) => {
          self.$Message.success('Nova kategorija uspešno dodana.')
          self.modal2 = false
        }).catch((e) => {
          self.$Message.error('Obrazec vsebuje napake.')
          self.errors = e.response.data.messages
        })
      },
      handleConfirm () {
        this.$Message('Confirm')
      },
      handleCancel () {
        this.$Message('Cancel')
      },
      getInputStatus (key) {
        if (typeof this.errors[key] !== 'undefined') {
          return 'error'
        }
      },
      ...mapActions([
        'getProductCategories',
        'addProductCategory',
        'getProducts',
        'getProduct',
        'updateProduct',
        'addProduct'
      ]),
      ...mapMutations([
        'resetProduct',
        'updateProductState'
      ])
    },
    computed: {
      // product
      productName: {
        get () {
          return this.$store.state.products.product.name
        },
        set (val) {
          console.log(val)
          this.updateProductState({
            key: 'name',
            val: val
          })
        }
      },
      ...mapState({
        product: state => state.products.product,
        pending_product: state => state.products.pending.product,
        error_product: state => state.products.error.product,

        products: state => state.products.products,
        pending_products: state => state.products.pending.products,
        error_products: state => state.products.error.products,

        categories: state => state.products.categories,
        pending_categories: state => state.products.pending.categories,
        error_categories: state => state.products.error.categories,

        product_category: state => state.products.category,
        pending_product_category: state => state.products.pending.category,
        error_product_category: state => state.products.error.category
      })
    }
  }
</script>

<style>
  .at-select { height: 32px; }
  .at-select__selection { height: 100%; }
</style>
