import Vue from "vue";
import VueRouter from "vue-router";
import routes from "./routes";

Vue.use(VueRouter);
const router = new VueRouter({
  mode: "history",
  base: __dirname + "dashboard",
  saveScrollPosition: true,
  scrollBehavior: function(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return {
        x: 0,
        y: 0
      };
    }
  },
  routes: routes
});

export default router;
