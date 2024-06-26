<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />

  <div class="bodyDiv">
  <div class="mainManagement">
    <v-alert v-if="showSuccessAlert" color="success" icon="$success" title="Pavyko!" text="Praktika išsaugota!"></v-alert>
    <v-alert v-if="showErrorAlert" color="error" icon="$error" title="Kaida!" text="Praktika nebuvo priskirta!"></v-alert>

    <div class="pageDescription">
      <h1>Praktikos priskyrimas</h1>
      <h2>Čia galite priskirti studento praktiką</h2>
    </div>
    <div class="managementInformation">
      <div class="selections"></div>

      <div class="inputDiv">
        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">
            Praktikos pavadinimas*
          </div>
          <v-combobox v-model="selectedInternship" :items="internships" @input="onInternshipInput" item-value="id"
            item-title="internshipName" return-object label="Pasirinkite praktikos pavadinimą"></v-combobox>
        </div>
        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Mentorius*</div>
          <v-combobox v-model="selectedMentor" :items="mentors" item-title="fullName" item-value="id" multiple clearable
            chips @input="onMentorInput" return-object label="Vardas Pavardė"></v-combobox>
        </div>

        <div class="fieldDiv">
          <div class="multiple-divs">
            <div class="text-subtitle-1 text-bold-emphasis">
              Praktikos vadovas*
            </div>
            <v-combobox v-model="selectedCounselors" :items="counselors" item-title="fullName" item-value="id" multiple
              clearable chips @input="onCounsolerInput" return-object label="Vardas Pavardė"></v-combobox>
          </div>
          <div class="multiple-divs">
            <div class="text-subtitle-1 text-bold-emphasis">Studentai*</div>
            <v-combobox v-model="selectedStudents" :items="students" item-title="fullName" item-value="id" multiple
              clearable chips @input="onStudentInput" return-object label="Vardas Pavardė"></v-combobox>
          </div>
        </div>
        <div class="fieldDivDate">
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Nuo:*</div>
            <input type="date" v-model="dateFrom" />
          </div>
          <div class="d-inline-block dateInput">
            <div class="text-subtitle-1 text-bold-emphasis">Iki:*</div>
            <input type="date" v-model="dateTo" />
          </div>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Kompetencijų vertinimas</div>
          <v-combobox v-model="selectedForms" :items="forms" @input="onFormInput" multiple item-value="id"
            item-title="name" return-object label="Pasirinkite vertinimo formą"></v-combobox>
        </div>

        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Praktikos vieta*</div>
          <v-autocomplete v-model="selectedCompany" :items="companies" @input="onCompanyInput" item-value="id"
            item-title="company_name" return-object label="Pasirinkite praktikos vietą"></v-autocomplete>
        </div>
      </div>

      <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated" @click="submitInternship">Išsaugoti
          <v-progress-circular v-if="isLoading" size="small" indeterminate color="white"></v-progress-circular></v-btn>

        <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
      </div>

    </div>
  </div>
</div>

</template>

<script>
import userIcon from "@/assets/Photos/UserIcon.png";
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import mobileNav from "@/components/MobileSidebar.vue";

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
      isDesktop: window.innerWidth > 950,
      showErrorAlert:false,
      selectedForms: [],
      forms: [],
      userIcon,
      selectedStudents: [],
      selectedGroup: "",
      selectedCounselors: [],
      students: [],
      counselors: [],
      groups: [],
      companies: [],
      internships: [],
      selectedCompany: null,
      dateFrom: null,
      dateTo: null,
      showSuccessAlert: false,
      companyName: null,
      internshipName: null,
      selectedInternship: null,
      mentors: [],
      mentorName: null,
      selectedMentor: [],
      isLoading: false,
      isError: false,
      errorMessage: '',
    };
  },
  components: {
    customHeader,
    mobileNav,
  },

  mounted() {
    if (this.$route.path.includes("/students-internship")) {
      const internshipId = this.$route.params.internshipId;
      this.fetchInternshipData(internshipId);
    }

    this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 500);

    this.debouncedSearchCompanies = debounce((companyName) => {
      this.searchCompanies(companyName);
    }, 500);

    this.debouncedSearchCounsolers = debounce((counselorName) => {
      this.searchCounselors(counselorName);
    }, 500);

    this.debouncedSearchInternships = debounce((internshipName) => {
      this.searchInternships(internshipName);
    }, 500);

    this.debouncedSearchMentors = debounce((mentorName) => {
      this.searchMentors(mentorName);
    }, 500);

    this.debouncedSearchForms = debounce((formName) => {
      this.searchForms(formName);
    }, 500);
  },
  methods: {
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },

    fetchInternshipData(internshipId) {
      apiClient
        .post(`/internship`, { internshipId: internshipId })
        .then((response) => {
          let fetchedInternships = response.data;
          this.internshipsLoaded = true;
          this.populateInternshipFields(fetchedInternships);
        })
        .catch((error) => {
          console.error("Error fetching internships: ", error);
        });
    },
    populateInternshipFields(data) {
      console.log("Internship data received:", data);

      this.selectedInternship = {
        internshipName: data[0].title,
        id: data[0].id,
      };
      console.log(this.selectedInternship);
      this.selectedMentor = data[0].user_profiles
        .filter((user) => user.role_id === 4)
        .map((user) => ({ fullName: user.fullname, id: user.id }));
      console.log(this.selectedMentor);
      this.selectedCounselors = data[0].user_profiles
        .filter((user) => user.role_id === 3)
        .map((user) => ({ fullName: user.fullname, id: user.id }));
      console.log(this.selectedCounselors);
      this.selectedStudents = data[0].user_profiles
        .filter((user) => user.role_id === 5)
        .map((user) => ({ fullName: user.fullname, id: user.id }));

      this.selectedCompany = {
        company_name: data[0].company.company_name,
        id: data[0].company.id,
      };
      this.dateFrom = data[0].date_from;
      console.log(this.dateFrom);
      this.dateTo = data[0].date_to;
      this.selectedForms = data[0].templates.map((template) => ({
        name: template.name,
        id: template.id,
      }));
      this.selectedCompany = {
        company_name: data[0].company.company_name,
        id: data[0].company.id,
      };
    },
    onStudentInput(event) {
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

    onCounsolerInput(event) {
      const counselorName = event.target.value;
      if (typeof counselorName === "string" && counselorName.trim() !== "") {
        this.debouncedSearchCounsolers(counselorName);
      }
    },

    onInternshipInput(event) {
      const internshipName = event.target.value;
      if (typeof internshipName === "string" && internshipName.trim() !== "") {
        this.debouncedSearchInternships(internshipName);
      }
    },

    onMentorInput(event) {
      const mentorName = event.target.value;
      if (typeof mentorName === "string" && mentorName.trim() !== "") {
        this.debouncedSearchMentors(mentorName);
      }
    },

    onFormInput(event) {
      const formName = event.target.value;
      if (typeof formName === "string" && formName.trim() !== "") {
        this.debouncedSearchForms(formName);
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

    searchForms(formName) {
      if (typeof formName !== "string") {
        console.error("formName called with non-string argument:", formName);
        return;
      }
      if (formName && formName.trim() !== "") {
        apiClient
          .post("/result/search/template", { name: formName })
          .then((response) => {
            this.forms = response.data.map((form) => ({
              id: form.id,
              name: form.name,
            }));
          })
          .catch((error) => {
            console.error("Error searching for forms:", error);
          });
      }
    },

    searchCounselors(counselorName) {
      if (typeof counselorName !== "string") {
        console.error(
          "counselorName called with non-string argument:",
          counselorName
        );
        return;
      }
      if (counselorName && counselorName.trim() !== "") {
        apiClient
          .post("/search-profiles-role", { fullName: counselorName, roleId: 3 })
          .then((response) => {
            this.counselors = response.data.map((counselor) => ({
              id: counselor.id,
              fullName: counselor.fullname,
            }));
          })
          .catch((error) => {
            console.error("Error searching for counselors:", error);
          });
      }
    },

    searchMentors(mentorName) {
      if (typeof mentorName !== "string") {
        console.error(
          "mentorName called with non-string argument:",
          mentorName
        );
        return;
      }
      if (mentorName && mentorName.trim() !== "") {
        apiClient
          .post("/search-profiles-role", { fullName: mentorName, roleId: 4 })
          .then((response) => {
            this.mentors = response.data.map((mentor) => ({
              id: mentor.id,
              fullName: mentor.fullname,
            }));
          })
          .catch((error) => {
            console.error("Error searching for mentors:", error);
          });
      }
    },

    searchInternships(internshipName) {
      if (typeof internshipName !== "string") {
        console.error(
          "internshipName called with non-string argument:",
          internshipName
        );
        return;
      }
      if (internshipName && internshipName.trim() !== "") {
        apiClient
          .post("/search/internship/titles", { searchString: internshipName })
          .then((response) => {
            this.internships = response.data;
          })
          .catch((error) => {
            console.error("Error searching for counselors:", error);
          });
      }
    },

    triggerSearchStudents() {
      this.debouncedSearchStudents(this.selectedStudents);
    },
    triggerSearchGroups() {
      this.debouncedSearchGroups(this.selectedGroup);
    },
    triggerSearchCounselors() {
      this.debouncedSearchGroups(this.selectedCounselors);
    },
    triggerSearchMentors() {
      this.debouncedSearchGroups(this.selectedMentor);
    },
    triggerSearchForms() {
      this.debouncedSearchForms(this.selectedForms);
    },

    submitInternship() {
      this.isError = false;
      this.isLoading = true;
      let internshipName =
        this.selectedInternship?.internshipName || this.selectedInternship;
      if (
        this.selectedCompany &&
        (this.selectedStudents.length > 0 ||
          this.selectedCounselors.length > 0 ||
          this.selectedMentor.length > 0) &&
        this.dateFrom &&
        this.dateTo &&
        internshipName
      ) {
        const studentIds = this.selectedStudents.map((student) => student.id);
        const counselorIds = this.selectedCounselors.map(
          (counselor) => counselor.id
        );
        const mentorIds = this.selectedMentor.map((mentor) => mentor.id);
        const userIds = [...studentIds, ...counselorIds, ...mentorIds];

        const payload = {
          companyId: this.selectedCompany.id,
          users: userIds,
          dateFrom: this.dateFrom,
          dateTo: this.dateTo,
          title: internshipName,
         // forms: this.selectedForms.id,
         forms: this.selectedForms.map(form => form.id), 
         ...(this.$route.path.includes("/students-internship") && {
            internshipId: this.$route.params.internshipId,
          }),
        };
console.log(payload);
        const apiEndpoint = this.$route.path.includes("/students-internship")
          ? "/internship-update"
          : "/internships";
        const method = this.$route.path.includes("/students-internship")
          ? "put"
          : "post";

        apiClient[method](apiEndpoint, payload)
          .then((response) => {
            this.isLoading = false;
            this.isError = false;
            console.log("Internship saved:", response.data);
            this.showSuccessAlert = true;
            setTimeout(() => (this.showSuccessAlert = false), 6000);

          })
          .catch((error) => {
            this.isLoading = false;
            this.errorMessage = error;
            this.isError = true;
            console.error("Error saving internship:", error);
            this.showErrorAlert = true;
            setTimeout(() => (this.showErrorAlert = false), 6000);
          });
      } else {
        this.isLoading = false;
        this.errorMessage = "Yra neužpildytų laukų";
        this.isError = true;
        console.error("All fields are required");
        this.showErrorAlert = true;
            setTimeout(() => (this.showErrorAlert = false), 6000);
      }
    },
  },
  created() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },
};
</script>

<style scoped>
@import '@/styles/InternshipStyle/internshipCreation.css';


.multiple-divs {
  min-width: 220px;
}
@media (min-width:500px){
.fieldDivDate {
  width: 450px;
  display: flex;
  justify-content: space-between;
  margin-bottom: 22px;
}
.fieldDiv {
  width: 450px;
  display: inline-block;
}

}

.dateInput {
  width: 49%;
}


@media (min-width: 950px){
.inputDiv {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  margin: 0 10px
}
.bottomButtons .v-btn {
  width: 200px;
  margin: 0 10px;
}

}

.bottomButtons {
  display: flex;
  justify-content: center;
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


input[type="date"]::-webkit-inner-spin-button {
  display: none;
}


input[type="date"]::-webkit-calendar-picker-indicator {
  color: #2c3e50;
}


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