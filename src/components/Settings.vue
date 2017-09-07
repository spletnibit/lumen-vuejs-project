<template>
  <div class="row" style="margin-top: 20px;">
    <h3 class="col-md-24">Nastavitve</h3>

    <div class="col-sm-24 col-md-18" style="margin-top: 20px;">
      <label>Naziv</label>
      <at-input></at-input>
    </div>
    <div class="col-sm-24 col-md-6" style="margin-top: 20px;">
      <label>ID za DDV</label>
      <at-input></at-input>
    </div>
    <div class="col-sm-24 col-md-12">
      <label>Naslov</label>
      <at-input></at-input>
    </div>
    <div class="col-sm-12 col-md-6">
      <label>Kraj</label>
      <at-input></at-input>
    </div>
    <div class="col-sm-12 col-md-6">
      <label>Poštna št.</label>
      <at-input></at-input>
    </div>

    <div class="col-md-24" style="margin-top: 20px;">
      <label>Besedilo v nogi PDF izpisa</label>
      <at-textarea rows="5"></at-textarea>
    </div>

  </div>
</template>

<script type="text/babel">
  export default {
    name: 'settings'
  }
</script>
