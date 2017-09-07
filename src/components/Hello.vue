<template>
  <div class="hello">
    <at-button @click="showMessage">Show message</at-button>
  </div>
</template>

<script>
export default {
  name: 'hello',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  methods: {
    showMessage: function () {
      this.$Message('Thanks for using AT-UI')
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
