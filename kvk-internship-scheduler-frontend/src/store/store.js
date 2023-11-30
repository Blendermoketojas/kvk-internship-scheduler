import { createStore } from "vuex";

export default createStore({
  state: {
    currentInternship: null,
    role_id: null,
    userInfo:null,
  },
  mutations: {
    setCurrentInternship(state, payload) {
      state.currentInternship = payload;
    },
    setRoleId(state, role_id) {
      state.role_id = role_id;
    },
    setUser(state, userInfo) {
      state.userInfo = userInfo;
    },
  },
  getters: {
    getCurrentInternship(state) {
      return state.currentInternship;
    },
    getUser(state){
      return state.userInfo || {};
    },
  },
  actions: {},
});
