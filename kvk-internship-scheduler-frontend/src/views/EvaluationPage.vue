<template>
  <div>
    <custom-header></custom-header>
    <div class="mainDiv">
      <div class="pageDescription">
        <h1 class="heading">Įvertinimai</h1>
        <h2>Čia peržiūrėti savo įvertinimus</h2>
      </div>

      <v-list>
        <v-list-item v-for="internship in paginatedInternships" :key="internship.id" @click="selectInternship(internship.id)">
          <v-list-item-content>
            <v-list-item-title>{{ internship.title }}</v-list-item-title>
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
          <v-data-table :items="selectedGrades" :loading="loadingGrades" :no-data-text="noDataText" :loading-text="loadingText" class="grades-table">
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

export default {
  components: { customHeader },

  data() {
    return {
      noDataText: "Nėra informacijos",
      loadingText: "Kraunama, prašome palaukti",
      currentPage: 1,
      pageSize: 5,
      totalInternshipPages: 1,
      paginatedInternships: [],
      selectedGrades: [],
      showGrades: false,
    };
  },

  methods: {
  async fetchInternships() {
    try {
      const response = await internshipService.getCurrentUserInternships();

      const userIds = Object.keys(response.data);
      const internshipsData = userIds.reduce((result, userId) => { return result.concat(response.data[userId]);}, []);
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;

      this.paginatedInternships = internshipsData.slice(startIndex, endIndex);

      this.totalInternshipPages = Math.ceil(internshipsData.length / this.pageSize);
    } catch (error) {
      console.error("Error fetching internships:", error);
    }
  },

  async selectInternship(internshipId) {
    this.showGrades = true;
    this.loadingGrades = true;

    try {
      const response = await internshipService.getCurrentUserInternshipGrades(internshipId);
      const internshipManager = 3;
      const mentor = 4;
      const userIds = Object.keys(response.data);
      
      const mentorGradesArray = response.data[userIds.find(userId => userId === mentor.toString())] || [];
      const managerGradesArray = response.data[userIds.find(userId => userId === internshipManager.toString())] || [];

      this.selectedGrades = [
      ...mentorGradesArray.map(grade => ({
        "Pažymio autorius": 'Mentorius',
        "Pažymys": grade.grade,
        galutinis: grade.is_final === 1 ? 'Taip' : 'Ne'
      })),
      ...managerGradesArray.map(grade => ({
        "Pažymio autorius": 'Praktikos vadovas',
        "Pažymys": grade.grade,
        galutinis: grade.is_final === 1 ? 'Taip' : 'Ne'
      }))
    ];
    } catch (error) {
      console.error("Error while fetching data:", error);
    } finally {
      this.loadingGrades = false;
    }
  },
},
  watch: {
    currentPage: 'fetchInternships',
  },

  created() {
    this.fetchInternships();
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
