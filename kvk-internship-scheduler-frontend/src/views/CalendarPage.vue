<template>
  <header-nav />
  <DxScheduler
    id="scheduler"
    :adaptivity-enabled="true"
    :views="views"
    current-view="week"
    :data-source="dataSource"
    @appointmentAdded="showValue"
    @appointmentDeleted="deleteComment"
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

      apiClient.post("/comments", dataToSend);
    },

    deleteComment(e) {

      const commentId = e.appointmentData?.id;
      console.log('comment id:',commentId);

      const payload = { commentId: commentId };

      apiClient
        .delete(`/comments`, {data:payload})
        .then(() => {
          console.log(`Comment with ID ${commentId} deleted successfully.`);
        })
        .catch((error) => {
          console.error("Error deleting comment:", error);
        });
    },

    formatDate(date) {
      function padTo2Digits(num) {
        return num.toString().padStart(2, "0");
      }
      function formatDate(date) {
        return (
          [
            date.getFullYear(),
            padTo2Digits(date.getMonth() + 1),
            padTo2Digits(date.getDate()),
          ].join("-") +
          " " +
          [
            padTo2Digits(date.getHours()),
            padTo2Digits(date.getMinutes()),
            padTo2Digits(date.getSeconds()),
          ].join(":")
        );
      }

      return formatDate(date);
    },
    updateCurrentInternship(internship) {
      this.$store.commit("setCurrentInternship", internship);
    },
    getActiveInternship() {
      return apiClient
        .get("/internship-active", { withCredentials: true })
        .then((response) => {
          if (response.data && response.data.id) {
            this.internship_id = response.data.id;
            console.log("Active internship ID:", this.internship_id);
            this.getUserComments();
          } else {
            throw new Error("Internship ID not found in response");
          }
        });
    },

    getUserComments() {
      if (this.internship_id) {
        const payload = { internshipId: this.internship_id };
        apiClient
          .post("/internship/comments", payload)
          .then((response) => {
            this.dataSource = response.data.map((comment) => ({
              id: comment.id,
              text: "Praktika",
              startDate: new Date(comment.date_from),
              endDate: new Date(comment.date_to),
              description: comment.comment,
            }));
            console.log("Formatted comments for scheduler:", this.dataSource);
          })
          .catch((error) => console.error("Error fetching comments:", error));
      } else {
        console.error("Internship ID is not set yet.");
      }
    },
  },
  computed: {
    ...mapGetters(["getCurrentInternship"]),
  },
  mounted() {
    this.getActiveInternship()
      .then(() => {
        if (this.internship_id) {
          this.getUserComments();
        }
      })
      .catch((error) => {
        console.error("Error during the active internship retrieval:", error);
      });
  },
};
</script>
<style scoped>
#scheduler {
  height: 600px;
}
</style>
