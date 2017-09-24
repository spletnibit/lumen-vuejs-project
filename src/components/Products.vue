<template>
    <div class="row" style="margin-top: 20px;">

      <at-button
        icon="icon-plus"
        type="primary"
        @click="onProductCreateOpen" hollow>Dodaj izdelek/storitev</at-button>

      <at-button
        icon="icon-plus"
        type="success"
        @click="onCategoryCreateOpen" hollow>Dodaj kategorijo</at-button>


      <at-modal
        v-model="modal1"
        title="Dodaj izdelek/storitev"
        :show-footer="false">
        <div class="row">
          <div class="col-sm-24 col-md-20">
            <label>Naziv</label>
            <at-input v-model="productName" :status="getInputStatus('name')"></at-input>
          </div>
          <div class="col-sm-12 col-md-4">
            <label>Enota</label>
            <at-input v-model="productUnit" :status="getInputStatus('unit')"></at-input>
          </div>
          <div class="col-sm-12 col-md-8">
            <label>Cena na enoto</label>
            <at-input v-model="productPrice" :status="getInputStatus('price')"></at-input>
          </div>
          <div class="col-sm-24 col-md-8">
            <label>DDV Stopnja</label>
            <at-input v-model="productVat" :status="getInputStatus('vat')">
              <template slot="append">%</template>
            </at-input>
          </div>
          <div class="col-sm-24 col-md-8">
            <label>Kategorija</label>

            <at-select v-model="productCategoryId" placeholder="" size="normal" clearable>
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
            <at-input v-model="categoryName" :status="getInputStatus('name')"></at-input>
          </div>
          <div class="col-md-24">
            <label>Nadrejena kategorija</label>
            <at-select v-model="categoryParent">
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
        <div class="at-table at-table--stripe at-table--normal at-table--border">
          <v-client-table v-if="products.length" name="productTable" :data="products" :columns="productColumns2" :options="productOptions">
            <template slot="price" scope="props">
              <div>
                {{ props.row.price | price }}
              </div>
            </template>

            <template slot="category_id" scope="props">
              <div>
                <div v-if="props.row.category">{{ props.row.category.name }}</div>
                <div v-else>/</div>
              </div>
            </template>
            <template slot="actions" scope="props">
              <div>
                <at-button size="small" icon="icon-edit" type="primary" @click="onProductEdit(props.row.id)" hollow circle></at-button>
                <at-button size="small" icon="icon-trash" type="error" @click="onProductDelete(props.row.id)" hollow circle></at-button>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>


      <h3 class="col-md-24" style="margin-top: 20px;">Kategorije</h3>
      <div class="col-md-24" v-if="categories.length">
        <div class="at-table at-table--stripe at-table--normal at-table--border">
          <v-client-table v-if="categories.length" name="categoryTable" :data="categories" :columns="categoryColumns" :options="categoryOptions">
            <template slot="actions" scope="props">
              <div>
                <at-button size="small" icon="icon-trash" type="error" @click="onProductCategoryDelete(props.row.id)" hollow circle></at-button>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>



    </div>

</template>

<script type="text/babel">
  import Vue from 'vue'
  import { mapState, mapActions, mapMutations } from 'vuex'
  import {ClientTable} from 'vue-tables-2'

  Vue.use(ClientTable, {}, true)

  export default {
    name: 'products',
    data () {
      return {
        modal1: false,
        modal2: false,
        errors: [],
        productColumns2: ['name', 'unit', 'price', 'vat', 'category_id', 'actions'],
        productOptions: {},
        categoryColumns: ['name', 'actions'],
        categoryOptions: {
        }
      }
    },
    created () {
      this.getProducts()
    },
    mounted () {
      if (this.categories.length === 0) {
        this.getProductCategories()
      }
    },
    methods: {
      onCategoryCreateOpen () {
        this.errors = []
        this.modal2 = true
      },
      onProductCreateOpen () {
        this.resetProduct()
        this.modal1 = true
        this.errors = []
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

        this[request.method](request.args).then((res) => {
          self.$Message.success(request.successMessage)
          self.modal1 = false
        }).catch((e) => {
          console.log(e)
          self.$Message.error('Obrazec vsebuje napake.')
          self.errors = e.response.data
        })
      },
      onProductEdit (id) {
        this.errors = false
        var self = this
        this.resetProduct()
        this.getProduct({ params: { id: id } }).then((res) => {
          self.modal1 = true
        }).catch((e) => {
        })
      },
      onProductDelete (id) {
        this.deleteProduct({
          params: {
            id: id
          }
        }).then(() => {
          this.$Message.success('Artikel/storitev uspešno izbrisan(a).')
        })
      },
      onProductCategoryDelete (id) {
        this.deleteProductCategory({
          params: {
            id: id
          }
        }).then(() => {
          this.$Message.success('Kategorija uspešno izbrisana.')
        })
      },
      onCategoryCreate () {
        var self = this
        this.addProductCategory({ data: this.product_category }).then((res) => {
          self.$Message.success('Nova kategorija uspešno dodana.')
          self.modal2 = false
        }).catch((e) => {
          console.log(e.response)
          self.$Message.error('Obrazec vsebuje napake.')
          self.errors = e.response.data
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
        'deleteProductCategory',
        'deleteProduct',
        'getProducts',
        'getProduct',
        'updateProduct',
        'addProduct'
      ]),
      ...mapMutations([
        'resetProduct',
        'updateProductState',
        'updateCategoryState'
      ])
    },
    computed: {
      // product
      productName: {
        get () {
          return this.$store.state.products.product.name
        },
        set (val) {
          this.updateProductState({
            key: 'name',
            val: val
          })
        }
      },
      productUnit: {
        get () {
          return this.$store.state.products.product.unit
        },
        set (val) {
          this.updateProductState({
            key: 'unit',
            val: val
          })
        }
      },
      productPrice: {
        get () {
          return this.$store.state.products.product.price
        },
        set (val) {
          this.updateProductState({
            key: 'price',
            val: val
          })
        }
      },
      productVat: {
        get () {
          return this.$store.state.products.product.vat
        },
        set (val) {
          this.updateProductState({
            key: 'vat',
            val: val
          })
        }
      },
      productCategoryId: {
        get () {
          return this.$store.state.products.product.category_id
        },
        set (val) {
          this.updateProductState({
            key: 'category_id',
            val: val
          })
        }
      },
      categoryName: {
        get () {
          return this.$store.state.products.category.name
        },
        set (val) {
          this.updateCategoryState({
            key: 'name',
            val: val
          })
        }
      },
      categoryParent: {
        get () {
          return this.$store.state.products.category.parent_id
        },
        set (val) {
          this.updateCategoryState({
            key: 'parent_id',
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
