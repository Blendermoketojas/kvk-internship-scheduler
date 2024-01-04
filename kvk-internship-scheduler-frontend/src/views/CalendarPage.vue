<template>
  <header-nav />
  <v-alert v-if="showErrorAlert" color="error" icon="$error" title="Kaida!" text="Komentaras nebuvo sukurtas!"></v-alert>

  <DxScheduler
    id="scheduler"
    :adaptivity-enabled="true"
    :views="views"
    current-view="week"
    :data-source="dataSource"
    @appointmentAdded="addedComment"
    @appointmentDeleted="deleteComment"
    @appointmentUpdated="updatedComment"
    @appointmentAdding="onAppointmentAdding"
    @appointmentUpdating="onAppointmentUpdating"
    @cellClick="onCellClick"
    allowDragging="false"
    allowResizing="false"
    :min="minDate"
    :max="maxDate"
  >
  </DxScheduler>
</template>
<script>
import "devextreme/dist/css/dx.light.css";
import { DxScheduler } from "devextreme-vue/scheduler";
import HeaderNav from "@/components/DesktopHeader.vue";
import { mapGetters } from "vuex";
import apiClient from "@/utils/api-client";
import { locale, loadMessages } from "devextreme/localization";
import ltMessages from "devextreme/localization/messages/lt.json";

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
      showErrorAlert:false,
    };
  },
  methods: {

    onAppointmentAdding(e) {
    const { startDate } = e.appointmentData;
    if (startDate < this.minDate || startDate > this.maxDate) {
      e.cancel = true;
  
      console.log("Appointment outside of date range is not allowed.");
    }
  },



  onAppointmentUpdating(e) {
    const { startDate } = e.newData;
    if (startDate && (startDate < this.minDate || startDate > this.maxDate)) {
      e.cancel = true;

      console.log("Updating to a date outside of range is not allowed.");
    }
  },

  onCellClick(e) {
    if (e.cellData.startDate < this.minDate || e.cellData.startDate > this.maxDate) {
      e.cancel = true;

      console.log("Selecting a date outside of range is not allowed.");
    }
  },


    addedComment(e) {
      const appointmentData = e.appointmentData;

      const formattedStartDate = this.formatDate(appointmentData.startDate);
      const formattedEndDate = this.formatDate(appointmentData.endDate);

      const dataToSend = {
        internshipId: this.internship_id,
        comment: appointmentData.description,
        dateFrom: formattedStartDate,
        dateTo: formattedEndDate,
      };

      apiClient.post("/comments", dataToSend)
    .then(response => {
console.log('lol')
      // this.dataSource.push({
      //   id: response.data.id, 
      //   text: "Praktika",
      //   startDate: new Date(formattedStartDate),
      //   endDate: new Date(formattedEndDate),
      //   description: appointmentData.description,
      // });

      // this.dataSource = [...this.dataSource];
    })
    .catch(error => {
      console.error("Error adding comment:", error);
      this.showErrorAlert = true;
          setTimeout(() => (this.showErrorAlert = false), 6000);
    });
    },

    deleteComment(e) {
      const commentId = e.appointmentData?.id;
      console.log("comment id:", commentId);

      const payload = { commentId: commentId };

      apiClient.delete(`/comments`, { data: { commentId: commentId } })
    .then(() => {
      this.dataSource = this.dataSource.filter(comment => comment.id !== commentId);
    })
    .catch(error => {
      console.error("Error deleting comment:", error);
    });
    },

    updatedComment(e) {
      const appointmentData = e.appointmentData;
      const formattedStartDate = this.formatDate(appointmentData.startDate);
      const formattedEndDate = this.formatDate(appointmentData.endDate);

      const dataToSend = {
        commentId: appointmentData.id,
        comment: appointmentData.description,
        dateFrom: formattedStartDate,
        dateTo: formattedEndDate,
      };
      apiClient.put("/comments", dataToSend)
    .then(() => {

      const commentIndex = this.dataSource.findIndex(comment => comment.id === appointmentData.id);
      if (commentIndex !== -1) {
        this.dataSource[commentIndex] = {
          ...this.dataSource[commentIndex],
          startDate: new Date(formattedStartDate),
          endDate: new Date(formattedEndDate),
          description: appointmentData.description,
        };
 
        this.dataSource = [...this.dataSource];
      }
    })
    .catch(error => {
      console.error("Error updating comment:", error);
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
            this.minDate = new Date(response.data.date_from);
            this.maxDate = new Date(response.data.date_to);
            console.log("Active internship ID:", this.internship_id);
          } else {
            this.internship_id = null;
            this.minDate = null;
        this.maxDate = null;
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
  created() {
    loadMessages(ltMessages);
    locale("lt");
  },
};
</script>
<style scoped>
#scheduler {
  height: 600px;
}
</style>
