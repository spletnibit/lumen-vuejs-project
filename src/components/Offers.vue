<template>
  <div class="row" style="margin-top: 20px;">

    <router-link to="/offers/add">
      <at-button
      icon="icon-plus"
      type="primary"
      @click="modal1=true" hollow>Dodaj ponudbo</at-button>
    </router-link>


    	<textarea rows="10" id='textarea' class="col-md-24">

 <html>
<head>
<style>
.important {
  color: darkred;
  font-size: 10em;
}
</style>
</head>
<body>
        <div>black</div>
        <div>black (should not have color parameter in PDF source)</div>

        <div style='color: red; font-size: 30px'>hello</div>
        <div style='color: rgb(0, 255, 0); font-size: 30px'>hello</div>
        <div style='color: #0000FF; font-size: 30px'>hello</div>

        <div class='important'>hello</div>

        <div>black</div>
        <div>black (should not have color parameter in PDF source)</div>

</body>
</html>


        </textarea>

    <at-button
      icon="icon-pdf"
      type="primary"
      @click="generatePDF" hollow>PDF</at-button>
    </div>
</template>

<script type="text/babel">
  export default {
    name: 'offers',
    methods: {
      generatePDF () {
        console.log('asd')
      }
    }
  }
</script>
