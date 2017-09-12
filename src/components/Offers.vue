<template>
  <div class="row" style="margin-top: 20px;">

    <router-link to="/offers/add">
      <at-button
      icon="icon-plus"
      type="primary"
      @click="modal1=true" hollow>Dodaj ponudbo</at-button>
    </router-link>

    <at-modal
      v-model="pdf.modal"
      title="PDF Ponudbe"
      width=""
      :show-footer="false">
      <div class="row">
        <div class="col-md-24">

          <div class="embed-responsive embed-responsive-1by1">
            <iframe class="embed-responsive-item" :src="pdf.url"></iframe>
          </div>
        </div>
      </div>
    </at-modal>


    <h3 class="col-md-24" style="margin-top: 20px;">Ponudbe</h3>
    <div class="col-md-24" v-if="tableData !== null">
      <at-table :columns="columns" :data="tableData" stripe border></at-table>
    </div>
  </div>
</template>

<script type="text/babel">
  import { mapState, mapActions } from 'vuex'

  export default {
    name: 'offers',
    data () {
      return {
        pdf: {
          modal: false,
          url: 'http://ponudbe.dev/api/public/offers/pdf/1'
        },
        columns: [
          { title: '#',
            render: (h, params) => {
              return params.index + 1
            }
          },
          { title: 'Stranka', key: 'customer' },
          { title: 'Skupaj',
            key: 'total',
            render: (h, params) => {
              return this.$options.filters.price(params.item.total)
            },
            sortType: 'normal' },
          { title: 'Dodano',
            key: 'created_at',
            sortType: 'normal',
            render: (h, params) => {
              return this.$options.filters.datetime(params.item.created_at)
            }
          },
          { title: 'Spremenjeno',
            key: 'updated_at',
            render: (h, params) => {
              return this.$options.filters.datetime(params.item.updated_at)
            },
            sortType: 'normal' },
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
                      console.log('/offers/edit/' + params.item.id)
                      self.$router.push({
                        path: '/offers/edit/' + params.item.id
                      })
                    }
                  }
                }, ''),
                h('AtButton', {
                  props: {
                    size: 'small',
                    icon: 'icon-download',
                    type: 'primary',
                    hollow: true,
                    circle: true
                  },
                  style: {
                    marginRight: '8px'
                  },
                  on: {
                    click: () => {
                      this.pdf.url = 'http://ponudbe.dev/api/public/offers/pdf/' + params.item.id
                      this.pdf.modal = true
                    }
                  }
                }, 'PDF'),
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
      var self = this
      this.$Loading.start()
      this.tableData = null

      this.getOffers().then((res) => {
        var data = [...res.data]
        for (var key in data) {
          if (data[key].customer !== null) {
            data[key].customer = data[key].customer.name
          }
        }
        data.name = 'Ponudba #' + data.id
        self.tableData = data
        self.$Loading.finish()
      }).catch((e) => {
        self.$Message.error('Prišlo je do napake.')
        self.$Loading.finish()
      })
    },
    methods: {
      generatePDF () {
      },
      ...mapActions([
        'getOffers'
      ])
    },
    computed: {
      pdfUrl () {
      },
      ...mapState({
        offers: state => state.offers.offers,
        pending_offers: state => state.offers.pending.offers,
        error_offers: state => state.offers.error.offers
      })
    }
  }
</script>

<style>
  iframe { border: 0; width: 100%; min-height: 600px; }
  .at-modal__wrapper { margin: 20px; }
  .at-modal { top: 0; width: 100%; height: 100%;}
</style>
