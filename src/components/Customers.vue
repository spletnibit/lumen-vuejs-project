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
          <at-input v-model="customerName" :status="getInputStatus('name')"></at-input>
        </div>
        <div class="col-md-24">
          <label>Naslov</label>
          <at-input v-model="customerAddress" :status="getInputStatus('address')"></at-input>
        </div>
        <div class="col-sm-24 col-md-12">
          <label>Kraj</label>
          <at-input v-model="customerCity" :status="getInputStatus('city')"></at-input>
        </div>
        <div class="col-sm-24 col-md-12">
          <label>Poštna št.</label>
          <at-input v-model="customerZip" :status="getInputStatus('zip')"></at-input>
        </div>
        <div class="col-sm-24 col-md-24">
          <label>ID za DDV</label>
          <at-input v-model="customerVat"  :status="getInputStatus('vat')"></at-input>
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
      <div class="at-table at-table--stripe at-table--normal at-table--border">
        <v-client-table name="customerTable" :data="customers" :columns="customerColumns" :options="customerOptions">
          <template slot="actions" scope="props">
            <div>
              <at-button size="small" icon="icon-edit" type="primary" @click="onCustomerEdit(props.row.id)" hollow circle></at-button>
              <at-button size="small" icon="icon-trash" type="error" @click="onCustomerDelete(props.row.id)" hollow circle></at-button>
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
    name: 'customers',
    data () {
      return {
        modal1: false,
        errors: false,
        errorsMessage: '',
        customerColumns: ['name', 'address', 'city', 'zip', 'vat', 'actions'],
        customerOptions: {}
      }
    },
    created () {
      this.onIndex()
    },
    methods: {
      onCustomerDelete (id) {
        var self = this
        this.$Modal.confirm({
          title: 'Izbris stranke',
          content: 'Potrdite izbis stranke.',
          cancelText: 'Prekliči',
          okText: 'Potrdi'
        }).then(() => {
          self.deleteCustomer({ params: { id: id } }).then((res) => {
            this.$Message.success('Stranka je bila uspešno izbrisana')
          })
        })
      },
      onCustomerEdit (id) {
        this.errors = false
        var self = this
        this.getCustomer({ params: { id: id } }).then((res) => {
          self.modal1 = true
        }).catch((e) => {
          self.$Message.error('Obrazec vsebuje napake.')
          self.errors = e.response.data
        })
      },
      onIndex () {
        this.getCustomers()
      },
      onModalCreate () {
        this.resetCustomer()
        this.modal1 = true
      },
      onCreate () {
        var self = this
        this.errors = []

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

        this[request.method](request.args)
          .then((res) => {
            self.$Message.success(request.successMessage)
            self.modal1 = false
            self.onIndex()
          }).catch((e) => {
            self.$Message.error('Obrazec vsebuje napake.')
            self.errors = e.response.data
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
        'resetCustomer',
        'updateCustomerState'
      ])
    },
    computed: {
      customerName: {
        get () {
          return this.$store.state.customers.customer.name
        },
        set (val) {
          this.updateCustomerState({
            key: 'name',
            val: val
          })
        }
      },
      customerAddress: {
        get () {
          return this.$store.state.customers.customer.address
        },
        set (val) {
          this.updateCustomerState({
            key: 'address',
            val: val
          })
        }
      },
      customerCity: {
        get () {
          return this.$store.state.customers.customer.city
        },
        set (val) {
          this.updateCustomerState({
            key: 'city',
            val: val
          })
        }
      },
      customerZip: {
        get () {
          return this.$store.state.customers.customer.zip
        },
        set (val) {
          this.updateCustomerState({
            key: 'zip',
            val: val
          })
        }
      },
      customerVat: {
        get () {
          return this.$store.state.customers.customer.vat
        },
        set (val) {
          this.updateCustomerState({
            key: 'vat',
            val: val
          })
        }
      },
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
