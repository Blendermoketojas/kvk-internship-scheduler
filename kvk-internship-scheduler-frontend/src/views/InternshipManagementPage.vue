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
          <v-text-field
            v-model="selectedGroup"
            :items="groups"
            @update:modelValue="triggerSearchGroups"
            label="I 17-3"
          ></v-text-field>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Vardas Pavardė</div>
          <v-autocomplete
            v-model="selectedStudent"
            :items="students"
            @update:modelValue="triggerSearchStudents"
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
            v-model="selectedCompany"
            :items="companies"
            item-value="id" 
            item-title="company_name"
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

const debounce = (func, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func(...args);
    }, delay);
  };
};

export default {
  name: "ProfileInfo",
  data() {
    return {
      userIcon,
      selectedStudent: '',
      selectedGroup: '',
      students: [],
      groups: [],
      companies: [],
      selectedCompany: null,
    };
  },
  components: {
    customHeader,
  },

  mounted() {
    this.debouncedSearchStudents = debounce(this.searchStudents, 2000);
    this.debouncedSearchGroups = debounce(this.searchGroups, 2000);
    
    this.$axios
      .get("http://localhost:8000/api/v2/companies", { withCredentials: true })
      .then((response) => {
        this.companies = response.data;
        this.registrationData.company_id = response.data.id;
        this.company_id = response.data.id;
      });
  },
  methods: {
    searchGroups() {
    apiClient
      .post("/search-student-groups", { groupIdentifier: this.selectedGroup })
      .then((response) => {
        this.groups = response.data;
      })
      .catch((error) => {
        console.error("Error searching for groups:", error);
      });
  },
     searchStudents() {
    apiClient
      .post("/search-students", { fullName: this.selectedStudent })
      .then((response) => {
        this.students = response.data;
      })
      .catch((error) => {
        console.error("Error searching for students:", error);
      });
      
  },
  
    triggerSearchStudents() {
      this.debouncedSearchStudents(this.selectedStudent);
    },
    triggerSearchGroups() {
      this.debouncedSearchGroups(this.selectedGroup);
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