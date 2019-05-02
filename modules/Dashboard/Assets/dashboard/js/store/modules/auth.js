import http from "@/services/axios";
import { isEmpty } from "lodash";

const state = {
  user: window.App.user
};

// getters
const getters = {
  user: state => state.user,
  fullname: state => state.user.fullname,
  isLoggedIn: state => !isEmpty(state.user),
  isGroupManager: state => state.user.isGroupManager,
  isGroupSupervisor: state => state.user.isGroupSupervisor,
  isWholesaleLenderManager: state => state.user.isWholesaleLenderManager,
  isDocuvaultEnabled: state => state.user.isDocuvaultEnabled,
  isAVMEnabled: state => state.user.isAVMEnabled,
  isManager: (state, getters) =>
    getters.isGroupManager || getters.isGroupSupervisor
};

// actions
const actions = {
  login({ commit }, payload) {
    return new Promise((resolve, reject) => {
      http
        .post("/auth/login", payload)
        .then(response => {
          commit("AUTH_SET", response.data);
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  },
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      http
        .post("/auth/logout")
        .then(response => {
          commit("AUTH_LOGOUT");
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  },
  reset({ commit }, payload) {
    return new Promise((resolve, reject) => {
      http
        .post("/auth/reset", payload)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  },
  resetConfirm({ commit }, payload) {
    return new Promise((resolve, reject) => {
      http
        .post("/auth/reset/confirm", payload)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  },
  resetPassword({ commit }, payload) {
    return new Promise((resolve, reject) => {
      http
        .post("/auth/reset/complete", payload)
        .then(response => {
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  }
};

// mutations
const mutations = {
  AUTH_SET(state, payload) {
    state.user = payload;
  },
  AUTH_LOGOUT(state) {
    state.user = {};
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
