<template>
  <div>
    <custom-header></custom-header>
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
          <v-data-table :items="selectedGrades" class="grades-table">
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
      currentPage: 1,
      pageSize: 3,
      totalInternshipPages: 1,
      paginatedInternships: [],
      selectedGrades: [],
      showGrades: false,
    };
  },

  methods: {
    async fetchInternships() {
      try {
        // Fetch internships from the service
        const response = await internshipService.getCurrentUserInternships();

        // Update paginatedInternships based on the response, currentPage, and pageSize
        const startIndex = (this.currentPage - 1) * this.pageSize;
        const endIndex = startIndex + this.pageSize;

        this.paginatedInternships = response.data.slice(startIndex, endIndex);

        this.totalInternshipPages = Math.ceil(response.data.length / this.pageSize);
      } catch (error) {
        console.error("Error fetching internships:", error);
      }
    },

    async selectInternship(internshipId) {
      // Simulate fetching documents for the selected internship
      this.showGrades = true;

      // Fetch grades for the selected internship
      try {
        const response = await internshipService.getCurrentUserInternshipGrades(internshipId);
        const internshipManager = 3;
        const mentor = 4;
        const gradesArray = response.data[internshipManager];
        // Replace the following with actual logic to handle the fetched grades
        this.selectedGrades = gradesArray && gradesArray.length > 0 ? [{ "Pažymio tipas": 'Example Internship', "Pažymys": gradesArray[0].grade }] : [];
      } catch (error) {
        console.error("Error fetching grades:", error);
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
