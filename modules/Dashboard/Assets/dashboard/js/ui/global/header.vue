<template>
  <nav class="navbar is-transparent">
    <div class="navbar-brand">
      <router-link :to="{'name': 'index'}" class="navbar-item"><img :src="$app.logo"></router-link>
      <div :class="{'is-active': isActive}" 
           class="navbar-burger burger is-pulled-right"
           data-target="header-nav"
           @click="onBurgerClick">
        <span/>
        <span/>
        <span/>
      </div>
    </div>

    <div id="header-nav" 
         :class="{'is-active': isActive}" 
         class="navbar-menu">
      <div class="navbar-start">
        <router-link :to="{'name': 'index'}" class="navbar-item">Home</router-link>
        <a class="navbar-item" href="#">
          Order
        </a>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="field is-grouped">
            <div class="navbar-end">
              <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                  {{ user.fullname }}
                </a>

                <div class="navbar-dropdown is-right">
                  <a class="navbar-item">
                    Account
                  </a>
                  <a class="navbar-item">
                    Notifications
                  </a>
                  <hr class="navbar-divider">
                  <a class="navbar-item" @click="onLogout">Logout</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      isActive: false
    };
  },
  computed: {
    ...mapGetters("auth", ["user"])
  },
  methods: {
    ...mapActions("auth", ["logout"]),
    onBurgerClick() {
      this.isActive = !this.isActive;
    },
    onLogout() {
      this.logout()
        .then(() => {
          this.$router.push({ name: "login.index" });
        })
        .catch(error => {
          console.log(error);
          this.$toast.open({
            message: "Failed to Logout.",
            type: "is-danger"
          });
        });
    }
  }
};
</script>
