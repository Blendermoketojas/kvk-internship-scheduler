<template>
  <div>
    <custom-header v-if="isDesktop" />
    <mobile-nav v-if="!isDesktop" />
    <div class="mainDiv">
      <div class="pageDescription">
        <h1 class="heading">Įvertinimai</h1>
        <h2>Čia peržiūrėti savo įvertinimus</h2>
      </div>

      <v-list>
        <v-list-item
          v-for="internship in paginatedInternships"
          :key="internship.id"
          @click="selectInternship(internship.id)"
        >
          <v-list-item-content>
            <v-list-item-title>{{ internship.title }}</v-list-item-title>
            <v-list-item-title v-if="userRoleId === 3 || userRoleId === 4">
              <b>{{ internship.user_profiles[0]?.fullname }}</b>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-pagination
        v-model="currentPage"
        :length="totalInternshipPages"
        @input="fetchInternships"
      ></v-pagination>

      <v-expand-transition>
        <div v-if="showGrades" class="animated-grades">
          <v-data-table
            :items="displayedGrades"
            :loading="loadingGrades"
            :no-data-text="noDataText"
            :loading-text="loadingText"
            class="grades-table"
          >
            <template v-slot:items="props">
              <td>{{ props.item.internshipName }}</td>
              <td>{{ props.item.grade }}</td>
            </template>
          </v-data-table>
        </div>
      </v-expand-transition>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import internshipService from "@/services/internships/InternshipService";
import { mapGetters } from "vuex";
import mobileNav from "@/components/MobileSidebar.vue";

export default {
  components: { customHeader, mobileNav },

  data() {
    return {
      isDesktop: window.innerWidth > 950,
      noDataText: "Nėra informacijos",
      loadingText: "Kraunama, prašome palaukti",
      currentPage: 1,
      pageSize: 5,
      totalInternshipPages: 1,
      paginatedInternships: [],
      selectedGrades: [],
      selectedMentorGrades: [],
      selectedManagerGrades: [],
      internships: [],
      showGrades: false,
      roleId: null,
    };
  },

  methods: {
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },
    async fetchInternships() {
      try {
        let filteredInternships = [];
        if (this.userRoleId === 3) {
          const response =
            await internshipService.getLinkedStudentsInactiveInternships();
          // const userIds = Object.keys(response.data);
          // const internshipsArray = response.data.internships;
          this.internships = response.data;
          // console.log(internshipsArray);

          filteredInternships = this.internships.filter(
            (internship) => internship.is_head_of_internship_evaluated === 1
          );
          console.log(filteredInternships);
        } else if (this.userRoleId === 4) {
          const response =
            await internshipService.getLinkedStudentsInactiveInternships();

          // const userIds = Object.keys(response.data);
          // const internshipsArray = response.data.internships;

          this.internships = response.data;

          filteredInternships = this.internships.filter(
            (internship) => internship.is_mentor_evaluated === 1
          );
          console.log(filteredInternships);
        } else if (this.userRoleId === 5) {
          const response = await internshipService.getCurrentUserInternships();
          const userIds = Object.keys(response.data);
          const internshipsArray = response.data.internships;
          filteredInternships = internshipsArray;
        }

        const startIndex = (this.currentPage - 1) * this.pageSize;
        const endIndex = startIndex + this.pageSize;
        this.paginatedInternships = filteredInternships.slice(
          startIndex,
          endIndex
        );
        this.totalInternshipPages = Math.ceil(
          filteredInternships.length / this.pageSize
        );
      } catch (error) {
        console.error("Error fetching internships:", error);
      }
    },

    async selectInternship(internshipId) {
      this.showGrades = true;
      this.loadingGrades = true;

      try {
        const response = await internshipService.getCurrentUserInternshipGrades(
          internshipId
        );
        const internshipManager = 3;
        const mentor = 4;
        const userIds = Object.keys(response.data);

        switch (this.userRoleId) {
          case 3:
            this.selectedManagerGrades = [
              {
                "Pažymio autorius": "Praktikos vadovas",
                Pažymys: response.data[0].grade,
                galutinis: response.data[0].is_final === 1 ? "Taip" : "Ne",
                Komentaras: response.data[0].comment,
              },
            ];
            console.log(this.selectedManagerGrades);
            break;
          case 4:
            this.selectedMentorGrades = [
              {
                "Pažymio autorius": "Mentorius",
                Pažymys: response.data[0].grade,
                galutinis: response.data[0].is_final === 1 ? "Taip" : "Ne",
                Komentaras: response.data[0].comment,
              },
            ];
            console.log(this.selectedMentorGrades);
            break;

          case 5:
            const mentorGradesArray =
              response.data[
                userIds.find((userId) => userId === mentor.toString())
              ] || [];
            const managerGradesArray =
              response.data[
                userIds.find(
                  (userId) => userId === internshipManager.toString()
                )
              ] || [];
            this.selectedGrades = [
              ...mentorGradesArray.map((grade) => ({
                "Pažymio autorius": "Mentorius",
                Pažymys: grade.grade,
                galutinis: grade.is_final === 1 ? "Taip" : "Ne",
                Komentaras: grade.comment,
              })),
              ...managerGradesArray.map((grade) => ({
                "Pažymio autorius": "Praktikos vadovas",
                Pažymys: grade.grade,
                galutinis: grade.is_final === 1 ? "Taip" : "Ne",
                Komentaras: grade.comment,
              })),
            ];
            console.log(this.selectedGrades);
            break;
        }
      } catch (error) {
        console.error("Error while fetching data:", error);
      } finally {
        this.loadingGrades = false;
      }
    },
  },
  watch: {
    currentPage: "fetchInternships",
  },

  created() {
    this.fetchInternships();
    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },

  computed: {
    ...mapGetters(["getUser"]),

    userRoleId() {
      return this.getUser.role_id;
    },

    displayedGrades() {
      switch (this.userRoleId) {
        case 3:
          return this.selectedManagerGrades;
        case 4:
          return this.selectedMentorGrades;
        case 5:
          return this.selectedGrades;
        default:
          return [];
      }
    },
  },
};
</script>

<style>
.mainDiv {
  padding: 0 100px;
}
.heading {
  font-weight: bold;
}
h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
</style>
