<template>
  <div class="row" style="margin-top: 20px;">
    <at-button
      icon="icon-plus"
      type="primary"
      @click="onModalCreate" hollow>Dodaj stranko</at-button>

    <at-modal
      v-model="modal1"
      :title="getTitle"
      :showFooter="false"
      width="">
      <div class="row">
        <input type="hidden" v-model="customer.id" />
        <div class="col-md-24">
          <label>Naziv</label>
          <at-input v-model="customer.name" :status="getInputStatus('name')"></at-input>
        </div>
        <div class="col-md-24">
          <label>Naslov</label>
          <at-input v-model="customer.address" :status="getInputStatus('address')"></at-input>
        </div>
        <div class="col-sm-24 col-md-12">
          <label>Kraj</label>
          <at-input v-model="customer.city" :status="getInputStatus('city')"></at-input>
        </div>
        <div class="col-sm-24 col-md-12">
          <label>Poštna št.</label>
          <at-input v-model="customer.zip" :status="getInputStatus('zip')"></at-input>
        </div>
        <div class="col-sm-24 col-md-24">
          <label>ID za DDV</label>
          <at-input v-model="customer.vat"  :status="getInputStatus('vat')"></at-input>
        </div>

        <div class="col-md-24" style="margin-top: 20px;">
          <div v-if="pending_customer">
            <at-button
              type="success"
              loading>Shrani</at-button>
          </div>

          <div v-if="!pending_customer">
            <at-button
              icon="icon-save"
              type="success"
              @click="onCreate">Shrani</at-button>
          </div>

        </div>
      </div>
    </at-modal>

    <h3 class="col-md-24" style="margin-top: 20px;">Stranke</h3>
    <div class="col-md-24" v-if="customers.length">
      <at-table :columns="columns" :data="customers" :page-size="50" stripe border pagination></at-table>
    </div>

  </div>
</template>

<script type="text/babel">
  import { mapState, mapActions, mapMutations } from 'vuex'

  export default {
    name: 'customers',
    data () {
      return {
        modal1: false,
        errors: false,
        errorsMessage: '',
        columns: [
          { title: '#', key: 'id' },
          { title: 'Naziv', key: 'name', sortType: 'normal' },
          { title: 'Naslov', key: 'address', sortType: 'normal' },
          { title: 'Mesto', key: 'city', sortType: 'normal' },
          { title: 'Poštna št.', key: 'zip' },
          { title: 'ID ZA DDV', key: 'vat' },
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
                      self.getCustomer({ params: { id: params.item.id } }).then((res) => {
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
                      this.$Modal.confirm({
                        title: 'Izbris stranke',
                        content: 'Potrdite izbis stranke.',
                        cancelText: 'Prekliči',
                        okText: 'Potrdi'
                      }).then(() => {
                        this.$Loading.start()
                        var message = 'Stranka ' + params.item.name + ' je bila uspešno izbrisana'
                        self.deleteCustomer({ params: { id: params.item.id } }).then((res) => {
                          this.$Loading.finish()
                          this.$Message.success(message)
                        }).catch((e) => {
                          this.$Loading.finish()
                        })
                      })
                    }
                  }
                }, '')
              ])
            }
          }
        ],
        tableData: null
      }
    },
    created () {
      this.onIndex()
    },
    methods: {
      onIndex () {
        var self = this
        this.$Loading.start()
        this.getCustomers().then((res) => {
          self.$Loading.finish()
        }).catch((e) => {
          self.$Message.error('Prišlo je do napake.')
          self.$Loading.finish()
        })
      },
      onModalCreate () {
        this.RESET_CUSTOMER()
        this.modal1 = true
      },
      onCreate () {
        var self = this
        this.errors = false
        this.errorsMessage = ''
        this.$Loading.start()

        var request = {
          method: 'addCustomer',
          args: {
            data: this.customer
          },
          successMessage: 'Stranka uspešno dodana.'
        }

        if (this.customer.id !== null) {
          request.method = 'updateCustomer'
          request.args = {
            params: { id: this.customer.id },
            data: this.customer
          }
          request.successMessage = 'Nova stranka uspešno urejena.'
        }

        console.log(request)

        this[request.method](request.args)
          .then((res) => {
            self.$Message.success(request.successMessage)
            self.modal1 = false
            self.$Loading.finish()
            self.onIndex()
          }).catch((e) => {
            self.$Message.error('Obrazec vsebuje napake.')
            self.errors = e.response.data.messages
            self.$Loading.finish()
          })
      },
      getInputStatus (key) {
        if (typeof this.errors[key] !== 'undefined') {
          return 'error'
        }
      },
      ...mapActions([
        'getCustomers',
        'getCustomer',
        'updateCustomer',
        'deleteCustomer',
        'addCustomer'
      ]),
      ...mapMutations([
        'RESET_CUSTOMER'
      ])
    },
    computed: {
      getTitle () {
        return this.customer.id ? 'Uredi stranko' : 'Dodaj stranko'
      },
      ...mapState({
        customers: state => state.customers.customers,
        pending_customers: state => state.customers.pending.customers,
        error_customers: state => state.customers.error.customers,
        customer: state => state.customers.customer,
        pending_customer: state => state.customers.pending.customer,
        error_customer: state => state.customers.error.customer
      })
    }
  }
</script>
