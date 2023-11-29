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
<<<<<<< HEAD
    login({ commit }, userCredentials) {

      commit('setRoleId', response.data.user.role_id);
    }

=======
    
>>>>>>> cec53f62f9f0ce18785fbdd2dd0a671d8d30ef19
  }
});