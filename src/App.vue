<template>
  <div id="app" class="container-fluid">
    <div class="row" v-if="user.token">
      <div class="col-md-24">
        <at-menu mode="horizontal" active-Name="offers">
          <at-menu-item name="offers"><router-link to="/offers"><i class="icon icon-home"></i>Ponudbe</router-link></at-menu-item>
          <at-menu-item name="products"><router-link to="/products"><i class="icon icon-package"></i>Artikli / storitve</router-link></at-menu-item>
          <at-menu-item name="customers"><router-link to="/customers"><i class="icon icon-users"></i>Stranke</router-link></at-menu-item>
          <at-menu-item name="settings"><router-link to="/settings"><i class="icon icon-settings"></i>Nastavitve</router-link></at-menu-item>
        </at-menu>
      </div>

    </div>
    <div class="row">
      <div class="col-md-24">
        <router-view></router-view>
      </div>

      <!--<div class="col-md-4">-->
        <!--<at-button icon="icon-log-out" @click="onLogOut">Odjava</at-button>-->
      <!--</div>-->
    </div>
  </div>
</template>

<script type="text/babel">
  import { mapState, mapMutations, mapActions } from 'vuex'

  export default {
    name: 'app',
    methods: {
      onLogOut () {
        this.logoutUser({
          data: this.user
        }).then(() => {
          this.$router.push({ path: '/login' })
          this.logOutUser()
        })
      },
      ...mapActions([
        'logoutUser'
      ]),
      ...mapMutations([
        'logOutUser'
      ])
    },
    computed: {
      ...mapState({
        user: state => state.user.user
      })
    }
  }
</script>

<style>
  .at-modal { width: 80%; }
</style>
