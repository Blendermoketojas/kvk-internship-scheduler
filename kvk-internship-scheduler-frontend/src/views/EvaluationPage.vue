<template>
    <div>
    <custom-header></custom-header>
    <div class="mainDiv">
      <div class="pageDescription">
        <h1 class="heading">Įvertinimai</h1>
        <h2>Čia peržiūrėti savo įvertinimus</h2>
      </div>

          <!-- Pagination controls for semesters -->
          <v-pagination
        v-model="currentPage"
        :length="totalSemesterPages"
        @input="fetchSemesters"
      ></v-pagination>

      <!-- Paginated list of semesters -->
      <v-list>
        <v-list-item
          v-for="semester in paginatedSemesters"
          :key="semester"
          @click="selectSemester(semester)"
        >
          <v-list-item-content>
            <v-list-item-title>{{ semester }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <!-- Animated grades section -->
      <v-expand-transition>
        <div v-if="showGrades" class="animated-grades">
          <!-- Animated content for grades -->
          <v-data-table :items="selectedGrades" class="grades-table"></v-data-table>
        </div>
      </v-expand-transition>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";

export default {
  components: { customHeader },

  data() {
    return {
      currentPage: 1,
      pageSize: 5,
      totalSemesterPages: 1,
      paginatedSemesters: [],
      selectedSemester: null,
      selectedGrades: [],
      showGrades: false,
    };
  },
  methods: {
    fetchSemesters() {
      // Implement logic to fetch all semesters from backend
      const allSemesters = ["Semester 1", "Semester 2", "Semester 3", /* ... */];

      // Calculate the start and end indexes for the current page
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;

      // Slice the semesters based on the current page and page size
      this.paginatedSemesters = allSemesters.slice(startIndex, endIndex);

      // Calculate the total number of pages based on the page size
      this.totalSemesterPages = Math.ceil(allSemesters.length / this.pageSize);
    },
    selectSemester(semester) {
      // Simulate fetching grades for the selected semester
      this.selectedSemester = semester;
      this.showGrades = true;

      // Replace the following with actual logic to fetch grades from the backend
      // Update this.selectedGrades with the fetched data
      this.selectedGrades = [
        { subject: "Math", grade: "A" },
        { subject: "English", grade: "B" },
        { subject: "Science", grade: "A-" },
        // ... other grades for the selected semester
      ];
    },
  },
  created() {
    this.fetchSemesters();
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