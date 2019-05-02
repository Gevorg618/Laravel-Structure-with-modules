<template>
  <div>
    <!-- Page Container START -->
    <div class="page-container">
      <!-- Content Wrapper START -->
      <div class="main-content">
        <!-- Content -->
        <div class="authentication">
          <div class="sign-in-2">
            <div class="no-pdd-horizon bg">
              <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-xs-12">
                  <div class="mr-auto ml-auto">
                    <div class="vertical-align">
                      <div class="table-cell">
                        <slot/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Content -->
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
import FooterNav from "@/ui/global/footer";
import ViewPort from "@/mixins/viewport";
export default {
  components: {
    FooterNav
  },
  mixins: [ViewPort]
};
</script>
