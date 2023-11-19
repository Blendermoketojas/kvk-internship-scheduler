<template>
  <custom-header></custom-header>
  <div class="mainManagement">
    <div class="pageDescription">
      <h1>Praktikos priskyrimas</h1>
      <h2>Čia galite priskirti studento praktiką</h2>
    </div>
    <div class="managementInformation">
      <div class="selections"></div>

      <div class="inputDiv">
        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Grupė</div>
          <v-autocomplete
            v-model="selectedGroup"
            :items="groups"
            @search="searchGroups"
            label="I 17-3"
          ></v-autocomplete>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Vardas Pavardė</div>
          <v-autocomplete
            v-model="selectedStudent"
            :items="students"
            @search="searchStudents"
            label="Vardenis Pavardenis"
          ></v-autocomplete>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Nuo:</div>
          <input type="date" />
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Iki:</div>
          <input type="date" />
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Kompanija</div>
          <v-autocomplete
            v-model="location"
            :items="locations"
            label="UAB 'Kompanija'"
          ></v-autocomplete>
        </div>
      </div>

      <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated">Išsaugoti</v-btn>
        <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import userIcon from "@/assets/Photos/UserIcon.png";
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";

export default {
  name: "ProfileInfo",
  data() {
    return {
      userIcon,
      selectedStudent: null,
      selectedGroup: null,
      students: [],
      groups: [],
    };
  },
  components: {
    customHeader,
  },
  
  mounted() {
    this.debouncedSearchStudents = debounce(this.searchStudents, 2000);
    this.debouncedSearchGroups = debounce(this.searchGroups, 2000);
  },
  methods: {
    searchStudents(query) {
      apiClient
        .post("/search-students", { fullName: query })
        .then((response) => {
          this.students = response.data;
        })
        .catch((error) => {
          console.error("Error searching for students:", error);
        });
    },
    searchGroups(query) {
      apiClient
        .post("/search-student-groups", { groupIdentifier: query })
        .then((response) => {
          this.groups = response.data;
        })
        .catch((error) => {
          console.error("Error searching for groups:", error);
        });
    },
    // Trigger search methods with debounce
    triggerSearchStudents(query) {
      this.debouncedSearchStudents(query);
    },
    triggerSearchGroups(query) {
      this.debouncedSearchGroups(query);
    },
  },
};
</script>

<style scoped>
.fieldDiv {
  width: 450px;
  display: inline-block;
}
.inputDiv {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
}

.mainManagement {
  padding: 0 200px;
}
.bottomButtons {
  display: flex;
  justify-content: center;
}
.bottomButtons .v-btn {
  width: 200px;
  margin: 0 10px;
}
.managementInformation {
  padding: 0 250px;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
input[type="date"]::-webkit-clear-button {
  display: none;
}

/* Removes the spin button */
input[type="date"]::-webkit-inner-spin-button {
  display: none;
}

/* Always display the drop down caret */
input[type="date"]::-webkit-calendar-picker-indicator {
  color: #2c3e50;
}

/* A few custom styles for date inputs */
input[type="date"] {
  appearance: none;
  -webkit-appearance: none;
  color: #95a5a6;
  font-family: "Helvetica", arial, sans-serif;
  font-size: 18px;
  border: 1px solid #ecf0f1;
  background: #ecf0f1;
  padding: 5px;
  display: inline-block !important;
  visibility: visible !important;
}

input[type="date"],
focus {
  color: #95a5a6;
  box-shadow: none;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
}
</style>
