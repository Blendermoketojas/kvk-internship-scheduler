<template>
  <custom-header></custom-header>
  <div class="mainManagement">
    <v-alert
      v-if="showSuccessAlert"
      color="success"
      icon="$success"
      title="Pavyko!"
      text="Praktika išsaugota!"
    ></v-alert>
    <div class="pageDescription">
      <h1>Praktikos priskyrimas</h1>
      <h2>Čia galite priskirti studento praktiką</h2>
    </div>
    <div class="managementInformation">
      <div class="selections"></div>

      <div class="inputDiv">
        <!-- <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Grupė</div>
          <v-autocomplete
            v-model="selectedGroup"
            :items="groups"
            item-title="group_identifier"
            item-value="id"
            @input="onGroupInput"
            return-object
            label="Įrašykite grupę"
          ></v-autocomplete>
        </div> -->

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Vardas Pavardė</div>
          <v-combobox
            v-model="selectedStudents"
            :items="students"
            item-title="fullName"
            item-value="id"
            multiple
            clearable
            chips
            @input="onStudentInput"
            return-object
            label="Įrašykite vardą"
          ></v-combobox>
        </div>

        <div class="fieldDivDate">
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Nuo:</div>
            <input type="date" v-model="dateFrom" />
          </div>
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Iki:</div>
            <input type="date" v-model="dateTo" />
          </div>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Kompanija</div>
          <v-autocomplete
            v-model="selectedCompany"
            :items="companies"
            @input="onCompanyInput"
            item-value="id"
            item-title="company_name"
            return-object
            label="Įrašykite kompaniją"
          ></v-autocomplete>
        </div>
      </div>

      <div class="bottomButtons">
        <v-btn
          color="#0D47A1"
          rounded="xl"
          variant="elevated"
          @click="submitInternship"
          >Išsaugoti</v-btn
        >

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
      selectedStudents: [],
      selectedGroup: "",
      students: [],
      groups: [],
      companies: [],
      selectedCompany: null,
      dateFrom: null,
      dateTo: null,
      showSuccessAlert: false,
    };
  },
  components: {
    customHeader,
  },

  mounted() {
    this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 500);

    this.debouncedSearchCompanies = debounce((companyName) => {
      this.searchCompanies(companyName);
    }, 500);
  },
  methods: {
    onStudentInput(value) {
      const studentName = event.target.value;
      if (typeof studentName === "string" && studentName.trim() !== "") {
        this.debouncedSearchStudents(studentName);
      }
    },

    onCompanyInput(event) {
      const companyName = event.target.value;
      if (typeof companyName === "string" && companyName.trim() !== "") {
        this.debouncedSearchCompanies(companyName);
      }
    },

    searchCompanies(companyName) {
      if (typeof companyName !== "string") {
        console.error(
          "searchCompanies called with non-string argument:",
          companyName
        );
        return;
      }

      if (companyName.trim() !== "") {
        apiClient
          .post("/search-companies", { companyName: companyName })
          .then((response) => {
            this.companies = response.data.map((company) => ({
              id: company.id,
              company_name: company.company_name,
            }));
          })
          .catch((error) => {
            console.error("Error searching for companies:", error);
          });
      }
    },

    searchStudents(studentName) {
      if (typeof studentName !== "string") {
        console.error(
          "searchStudents called with non-string argument:",
          studentName
        );
        return;
      }
      if (studentName && studentName.trim() !== "") {
        apiClient
          .post("/search-students", { fullName: studentName })
          .then((response) => {
            this.students = response.data.map((student) => ({
              id: student.id,
              fullName: student.fullname,
            }));
          })
          .catch((error) => {
            console.error("Error searching for students:", error);
          });
      }
    },

    triggerSearchStudents() {
      this.debouncedSearchStudents(this.selectedStudents);
    },
    triggerSearchGroups() {
      this.debouncedSearchGroups(this.selectedGroup);
    },
    submitInternship() {
      if (
        this.selectedCompany &&
        this.selectedStudents.length > 0 &&
        this.dateFrom &&
        this.dateTo
      ) {
        const userIds = this.selectedStudents.map(student => student.id);

        const payload = {
          companyId: this.selectedCompany.id,
          users: userIds,
          dateFrom: this.dateFrom,
          dateTo: this.dateTo,
        };

        apiClient
          .post("/internships", payload)
          .then((response) => {
            console.log("Internship saved:", response.data);
            this.showSuccessAlert = true;
            setTimeout(() => (this.showSuccessAlert = false), 6000);
          })
          .catch((error) => {
            console.error("Error saving internship:", error);
            // Handle error, show error message to user
          });
      } else {
        // Handle case where not all fields are filled out
        console.error("All fields are required");
      }
    },
  },
};
</script>

<style scoped>
.fieldDivDate {
  width: 450px;
  display: flex;
  justify-content: space-between;
  margin-bottom: 22px;
}
.dateInput {
  width: 49%;
}
.fieldDiv {
  width: 450px;
  display: inline-block;
}
.inputDiv {
  display: flex;
  flex-direction: column;
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
  width: 100%;
}

input[type="date"],
focus {
  color: #95a5a6;
  box-shadow: none;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
}
</style>
