import { createStore } from "vuex";

export default createStore({
  state: {
    currentInternship: null,
  },
  mutations: {
    setCurrentInternship(state, payload) {
      state.currentInternship = payload;
    }
  },
  getters: {
    getCurrentInternship(state) {
      return state.currentInternship;
    }
  },
  actions: {
    
  }
});