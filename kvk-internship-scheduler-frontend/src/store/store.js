import { createStore } from "vuex";

export default createStore({
  state: {
    currentInternship: null,
    role_id: null,
  },
  mutations: {
    setCurrentInternship(state, payload) {
      state.currentInternship = payload;
    },
    setRoleId(state, role_id) {
      state.role_id = role_id;
    }
  },
  getters: {
    getCurrentInternship(state) {
      return state.currentInternship;
    }
  },
  actions: {
    login({ commit }, userCredentials) {

      commit('setRoleId', response.data.user.role_id);
    }

  }
});