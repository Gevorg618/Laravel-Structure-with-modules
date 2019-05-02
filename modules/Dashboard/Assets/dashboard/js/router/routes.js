import * as guards from "./guards";

const routes = [
  {
    path: "/",
    component: require("views/base").default,
    beforeEnter: guards.authenticated,
    children: [
      {
        path: "/",
        name: "index",
        component: () =>
          import(/* webpackChunkName: "homepage-index" */ "@/views/homepage/index")
      }
    ]
  },
  {
    path: "/login",
    component: require("views/auth").default,
    beforeEnter: guards.unauthenticated,
    children: [
      {
        path: "",
        name: "login.index",
        beforeEnter: guards.unauthenticated,
        component: () =>
          import(/* webpackChunkName: "auth-login" */ "@/views/auth/login")
      },
      {
        path: "reset/:token",
        props: true,
        name: "login.reset.confirm",
        beforeEnter: guards.unauthenticated,
        component: () =>
          import(/* webpackChunkName: "auth-password-confirm" */ "@/views/auth/password/confirm")
      },
      {
        path: "reset",
        name: "login.reset",
        beforeEnter: guards.unauthenticated,
        component: () =>
          import(/* webpackChunkName: "auth-password-reset" */ "@/views/auth/password/form")
      }
    ]
  },
  {
    path: "/tickets",
    component: require("views/base").default,
    beforeEnter: guards.authenticated,
    children: [
      {
        path: ":hash",
        props: true,
        beforeEnter: guards.authenticated,
        name: "tickets.view",
        component: () =>
          import(/* webpackChunkName: "tickets-view" */ "@/views/tickets/view")
      }
    ]
  },
  {
    path: "/errors",
    component: require("views/base").default,
    children: [
      {
        path: "",
        name: "errors.index",
        component: require("@/views/errors/not-found").default
      },
      {
        path: "404",
        name: "errors.404",
        component: require("@/views/errors/not-found").default
      }
    ]
  },
  {
    path: "*",
    component: require("views/base").default,
    children: [
      {
        path: "/",
        name: "wildcard",
        component: require("@/views/errors/not-found").default
      }
    ]
  }
];

export default routes;
