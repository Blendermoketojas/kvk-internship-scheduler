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
        :key="internship"
        @click="selectInternship(internship)"
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
        <v-data-table :items="selectedGrades" class="grades-table"></v-data-table>
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
    selectedInternship: null,
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
  selectInternship(internship) {
    this.selectedInternship = internship;
    this.showGrades = true;

    // Replace the following with actual logic to fetch documents from the backend
    // Update this.selectedGrades with the fetched data
    this.selectedGrades = [
      { documentType: "Resume", status: "Approved" },
      { documentType: "Cover Letter", status: "Pending" },
      // ... other documents for the selected internship
    ];
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