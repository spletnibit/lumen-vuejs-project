<template>
  <div class="row" style="margin-top: 20px;">

    <router-link to="/offers/add">
      <at-button
      icon="icon-plus"
      type="primary"
      @click="onOfferCreate" hollow>Dodaj ponudbo</at-button>
    </router-link>


    <h3 class="col-md-24" style="margin-top: 20px;">Ponudbe</h3>
    <div class="col-md-24" v-if="offers.length">
      <div class="at-table at-table--stripe at-table--normal at-table--border">
        <v-client-table name="offerTable" :data="offers" :columns="offerColumns" :options="offerOptions">
          <template slot="id" scope="props">
            <div>Ponudba #{{ props.row.id }}</div>
          </template>
          <template slot="total" scope="props">
            <div>{{ props.row.total | price }}</div>
          </template>
          <template slot="customer_id" scope="props">
            <div>{{ props.row.customer.name }}</div>
          </template>
          <template slot="created_at" scope="props">
            <div>
              {{ props.row.created_at | datetime }}
            </div>
          </template>
          <template slot="updated_at" scope="props">
            <div>
              {{ props.row.updated_at | datetime }}
            </div>
          </template>
          <template slot="actions" scope="props">
            <div>
              <at-button size="small" icon="icon-edit" type="primary" @click="onOfferEdit(props.row.id)" hollow circle></at-button>
              <at-button size="small" icon="icon-download" type="primary" @click="onOfferPdf(props.row.id)" hollow circle></at-button>
              <at-button size="small" icon="icon-trash" type="error" @click="onOfferDelete(props.row.id)" hollow circle></at-button>
            </div>
          </template>
        </v-client-table>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
  import Vue from 'vue'
  import { mapState, mapActions } from 'vuex'
  import {ClientTable} from 'vue-tables-2'

  Vue.use(ClientTable, {}, true)
  export default {
    name: 'offers',
    data () {
      return {
        offerColumns: ['id', 'customer_id', 'total', 'created_at', 'updated_at', 'actions'],
        offerOptions: {}
      }
    },
    created () {
      this.getOffers()
    },
    methods: {
      onOfferEdit (id) {
        this.$router.push({
          path: '/offers/edit/' + id
        })
      },
      onOfferDelete (id) {
        var self = this
        this.$Modal.confirm({
          title: 'Izbris ponudbe',
          content: 'Potrdite izbis ponudbe.',
          cancelText: 'Prekliči',
          okText: 'Potrdi'
        }).then(() => {
          self.deleteOffer({ params: { id: id } }).then((res) => {
            this.$Message.success('Ponudba je bila uspešno izbrisana')
          })
        })
      },
      onOfferPdf (id) {
        console.log(this.user)
        window.open('http://ponudbe.local/api/public/offers/pdf/' + id + '?token=' + this.user.token, '_blank', 'fullscreen=yes')
      },
      onOfferCreate () {
        this.modal1 = true
      },
      ...mapActions([
        'getOffers',
        'deleteOffer'
      ])
    },
    computed: {
      ...mapState({
        offers: state => state.offers.offers,
        pending_offers: state => state.offers.pending.offers,
        error_offers: state => state.offers.error.offers,
        user: state => state.user.user
      })
    }
  }
</script>

<style>
  iframe { border: 0; width: 100%; min-height: 600px; }
  .at-modal__wrapper { margin: 20px; }
  .at-modal { top: 0; width: 100%; height: 100%;}
</style>
