<template>
  <div>
    <!-- Header START -->
    <header-nav/>
    <!-- Header END -->
    <!-- Page Container START -->
    <div class="page-container">
      <!-- Content Wrapper START -->
      <div class="main-content">
        <div class="container-fluid">
          <!-- Content -->
          <slot/>
        <!-- Content -->
        </div>
      </div>
      <!-- Content Wrapper END -->
    </div>
    <!-- Footer START -->
    <footer-nav/>
    <!-- Footer END -->
  <!-- Page Container END -->
  </div>
</template>

<script>
import HeaderNav from "@/ui/global/header";
import FooterNav from "@/ui/global/footer";
export default {
  components: {
    HeaderNav,
    FooterNav
  }
};
</script>
