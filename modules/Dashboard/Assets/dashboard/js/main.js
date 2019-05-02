import "es6-promise/auto";
import Vue from "vue";
import router from "@/router";
import store from "@/store";
import Meta from "vue-meta";
import App from "app";
import { each } from "lodash";
import * as filters from "./filters";
require("@/ui/vendors/elementui");
require("@/ui/vendors/bulma");
require("@/ui/vendors/vuevalidate");

import { sync } from "vuex-router-sync";
sync(store, router);

const events = new Vue();
Vue.prototype.$events = events;
Vue.prototype.$app = window.App;

Vue.use(Meta);

each(filters.default, (callback, name) => {
  Vue.filter(name, callback);
});

new Vue({
  router,
  store,
  metaInfo: {
    title: "Dashboard",
    titleTemplate: `%s | ${window.App.options.name}`
  },
  created() {
    console.log("Created Component.");
  },
  render: h => h(App)
}).$mount("#app");
