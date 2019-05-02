import http from "@/services/axios";

const state = {};

// getters
const getters = {};

// actions
const actions = {
  get({ commit }, hash) {
    return new Promise((resolve, reject) => {
      http
        .get("/tickets/view", { params: { hash: hash } })
        .then(response => {
          resolve(response.data);
        })
        .catch(error => reject(error.response.data));
    });
  }
};

// mutations
const mutations = {};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
