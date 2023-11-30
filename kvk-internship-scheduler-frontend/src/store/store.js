import { createStore } from "vuex";

export default createStore({
  state: {
    currentInternship: null,
    role_id: null,
    userInfo: null,
    uploadAction: null,
    internshipDialogData: null,
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
      return state.userInfo || {};
    },
    getUploadAction(state) {
      return state.uploadAction;
    },
    getInternshipDialogData(state) {
      return state.internshipDialogData;
    }
  },
  actions: {},
});
