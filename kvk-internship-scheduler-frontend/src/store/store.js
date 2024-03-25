import { createStore } from "vuex";

export default createStore({
  state: {
    currentInternship: null,
    role_id: null,
    uploadAction: null,
    internshipDialogData: null,
    user:null,
  },
  mutations: {
    setCurrentInternship(state, payload) {
      state.currentInternship = payload;
    },
    setRoleId(state, role_id) {
      state.role_id = role_id;
    },
    setUser(state, user) {
      state.user = user;
    },
    setUploadAction(state, payload) {
      state.uploadAction = payload;
    },
    setInternshipDialogData(state, payload) {
      state.internshipDialogData = payload;
    }
  },
  getters: {
    getCurrentInternship(state) {
      return state.currentInternship;
    },
    getUser(state){
      return state.user || {};
    },
    getUploadAction(state) {
      return state.uploadAction;
    },
    getInternshipDialogData(state) {
      return state.internshipDialogData;
    },
    getRoleId(state) {
      return state.user ? state.user.role_id : null;
    },
    getUserId(state) {
      return state.user ? state.user.id : null;
    },
    getUserFullName(state) {
      return state.user ? state.user.fullname : '';
    },
  },
  actions: {},
});
