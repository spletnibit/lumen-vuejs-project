<template>
  <div id="app" class="container-fluid">
    <div class="row">
      <div class="col-md-24">
        <at-menu mode="horizontal" active-Name="offers" theme="dark">
          <at-menu-item name="offers"><router-link to="/offers"><i class="icon icon-home"></i>Ponudbe</router-link></at-menu-item>
          <at-menu-item name="products"><router-link to="/products"><i class="icon icon-package"></i>Artikli / storitve</router-link></at-menu-item>
          <at-menu-item name="customers"><router-link to="/customers"><i class="icon icon-users"></i>Stranke</router-link></at-menu-item>
          <at-menu-item name="settings"><router-link to="/settings"><i class="icon icon-settings"></i>Nastavitve</router-link></at-menu-item>
        </at-menu>

      </div>
      <div class="col-md-24">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'app'
}
</script>

<style>
  .at-modal { width: 80%; }
</style>
