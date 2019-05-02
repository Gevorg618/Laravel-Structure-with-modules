<template>
  <section>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <b-message
          type="is-danger" 
          has-icon>
          Sorry, This page was not found.
        </b-message>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  created() {
    console.log(this.$route);
  }
};
</script>

<style>
</style>
