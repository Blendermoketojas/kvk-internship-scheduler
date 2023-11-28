<template>
  <header-nav />
  <DxScheduler
    id="scheduler"
    :adaptivity-enabled="true"
    :views="views"
    current-view="week"
    :data-source="dataSource"
    @appointmentAdded="showValue"
  >
  </DxScheduler>
</template>
<script>
import "devextreme/dist/css/dx.light.css";
import { DxScheduler } from "devextreme-vue/scheduler";
import HeaderNav from "@/components/DesktopHeader.vue";
import { mapGetters } from "vuex";
import apiClient from "@/utils/api-client";

export default {
  components: {
    DxScheduler,
    HeaderNav,
  },
  name: "CalendarComponent",
  data() {
    return {
      views: ["month", "week"],
      dataSource: [],
      internship_id: null,
    };
  },
  methods: {
    showValue(e) {
      const appointmentData = e.appointmentData;

      const formattedStartDate = this.formatDate(appointmentData.startDate);
      const formattedEndDate = this.formatDate(appointmentData.endDate);

      const dataToSend = {
        internshipId: this.internship_id,
        comment: appointmentData.description,
        dateFrom: formattedStartDate,
        dateTo: formattedEndDate,
      };

      apiClient
        .post("/comments", dataToSend)
    },

    formatDate(date) {
      const offset = date.getTimezoneOffset();
      const adjustedDate = new Date(date.getTime() - offset * 60 * 1000);
      return adjustedDate.toISOString().split("T")[0];
    },
    updateCurrentInternship(internship) {
      this.$store.commit("setCurrentInternship", internship);
    },
    getActiveInternship() {
      apiClient
        .get("/internship-active", { withCredentials: true })
        .then((response) => {
          this.internship_id = response.data.id;
          console.log("Active internship ID:", this.internship_id);
        });
    },
  },
  computed: {
    ...mapGetters(["getCurrentInternship"]),
  },
  mounted() {
    if (!this.getCurrentInternship) {
      this.getActiveInternship();
    }
  },
};
</script>
<style scoped>
#scheduler {
  height: 600px;
}
</style>
