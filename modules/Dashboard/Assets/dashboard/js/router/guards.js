import store from "@/store";

// Restrict to authenticated users
function authenticated(to, from, next) {
  if (!store.getters["auth/isLoggedIn"]) {
    next({ name: "login.index" });
  }

  next();
}

// Restrict to unauthenticated users
function unauthenticated(to, from, next) {
  if (store.getters["auth/isLoggedIn"]) {
    next({ name: "index" });
  }

  next();
}

export { authenticated, unauthenticated };
