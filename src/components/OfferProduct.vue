<template>
  <div class="offers__products-item" v-if="data">
    <div class="col-md-8">
      <at-input v-model="data.name"></at-input>
    </div>
    <div class="col-md-3 qty-input">
      <at-input v-model="data.pivot.qty" @change="recalculateOffer">
        <template slot="append">{{ data.unit }}</template>
      </at-input>
    </div>
    <div class="col-md-3 align-middle">
      <div v-html="$options.filters.price(data.price)"></div>
    </div>
    <div class="col-md-3 align-middle">
      {{ data.vat }} %
    </div>
    <div class="col-md-3">
      <at-input v-model="data.pivot.discount" :min="0" :max="100" @change="recalculateOffer">
        <template slot="append">%</template>
      </at-input>
    </div>
    <div class="col-md-3 align-middle text-right" v-html="$options.filters.price(data.total)"></div>
    <div class="col-md-1 align-middle text-center">
      <at-button size="small" icon="icon-x" @click="onProductRemove()" circle></at-button>
    </div>
  </div>
</template>

<script type="text/babel">
  import { mapMutations } from 'vuex'
  export default {
    name: 'OfferProduct',
    props: ['index', 'data', 'productIndex', 'categoryIndex'],
    methods: {
      onProductRemove () {
        this.removeProductFromOffer(this.index)
        this.removeProductFromCategoryTree({
          productIndex: this.productIndex,
          categoryIndex: this.categoryIndex
        })
        this.recalculateOffer()
      },
      ...mapMutations([
        'removeProductFromOffer',
        'recalculateOffer',
        'removeProductFromCategoryTree'
      ])
    }
  }
</script>

<style>
  .offers__products-item {
    display: flex;
  }

  .qty-input .at-input__original { width: 50px;}
  .qty-input .at-input-group__append { width: 40px;}

  h3 {margin: 10px 0 20px;}
</style>
