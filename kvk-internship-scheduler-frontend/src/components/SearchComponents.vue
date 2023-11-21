<template>


    <div class="fieldDiv">
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
      </div>

    <div class="fieldDiv">
        <div class="text-subtitle-1 text-bold-emphasis">Vardas Pavardė</div>
        <v-autocomplete
          v-model="selectedStudent"
          :items="students"
          item-title="fullName"
          item-value="id"
          @input="onStudentInput"
          return-object
          label="Įrašykite vardą"
        ></v-autocomplete>
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
          label="Įrašykite kompanija"
        ></v-autocomplete>
        </div>

</template>

<script>
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
      selectedStudent: "",
      selectedGroup: "",
      students: [],
      groups: [],
      companies: [],
      selectedCompany: null,

    }
  },
  mounted() {
    this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 500);

    this.debouncedSearchGroups = debounce((groupName) => {
      this.searchGroups(groupName);
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

    onGroupInput(event) {
      const groupName = event.target.value;
      if (typeof groupName === "string" && groupName.trim() !== "") {
        this.debouncedSearchGroups(groupName);
        this.$emit('update:selectedGroupId', this.selectedGroup.id);
      }
    },

    searchGroups(groupName) {
      if (typeof groupName !== "string") {
        console.error(
          "searchGroups called with non-string argument:",
          groupName
        );
        return;
      }
      if (groupName.trim() !== "") {
        apiClient
          .post("/search-student-groups", { groupIdentifier: groupName })
          .then((response) => {
            this.groups = response.data.map((group) => ({
              id: group.id,
              group_identifier: group.group_identifier,
            }));
          })
          .catch((error) => {
            console.error("Error searching for groups:", error);
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
      this.debouncedSearchStudents(this.selectedStudent);
    },
    triggerSearchGroups() {
      this.debouncedSearchGroups(this.selectedGroup);
    },
  }
}



</script>

<style>


</style>