<template>
  <header-nav></header-nav>

  <div class="mainPageDiv">
    <div class="pageDescription">
      <h1>Praktikų sąrašas</h1>
      <h2>Čia galite peržiūrėti vykstamas ir pasibaigusias praktikas</h2>
    </div>
    <div class="mainInternshipDiv">
      <div class="studentSearchInput" v-if="!isRoleFive">
        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Filtruoti pagal:</div>
          <v-select
            v-model="selectedFilter"
            :items="filterBy"
            density="comfortable"
            label="Pasirinkite filtrą"
          ></v-select>
        </div>

        <div class="fieldDiv" v-if="showStudentInput">
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

        <group-search
          v-if="showGroupInput"
          @update:selectedGroupId="handleSelectedGroupId"
        ></group-search>
      </div>
      <v-expansion-panels v-model="openedPanel">
        <v-expansion-panel
          v-for="internship in internships"
          :key="internship.id"
        >
          <v-expansion-panel-title
            class="panelHeader"
            @click="handleInternshipClick(internship.internshipId)"
          >
            <v-container>
              <v-row no-gutters>
                <v-col cols="3">
                  <div><b>Studentas:</b> {{ internship.student_name }}</div>
                </v-col>
                <v-col cols="3">
                  <b>Įmonė:</b> {{ internship.company_name }}
                </v-col>
                <v-col cols="3">
                  <div><b>Nuo:</b> {{ internship.date_from }}</div>
                </v-col>
                <v-col cols="3">
                  <div><b>Iki:</b> {{ internship.date_to }}</div>
                </v-col>
                <v-col class="d-flex justify-end" cols="3"> </v-col>
              </v-row>
            </v-container>
          </v-expansion-panel-title>
          <v-expansion-panel-text>
            <v-container v-if="selectedInternshipComments === null">
              Kraunama informacija...
            </v-container>

            <v-container v-else>
              <v-row v-if="selectedInternshipComments.length > 0">
                <v-col
                  v-for="comment in selectedInternshipComments"
                  :key="comment.id"
                  cols="12"
                >
                  <v-row>
                    <v-col cols="4" class="comment-details">
                      Nuo: {{ comment.date_from }}
                    </v-col>
                    <v-col cols="4" class="comment-details">
                      Iki: {{ comment.date_to }}
                    </v-col>
                    <v-col cols="4" class="comment-details">
                      Aprašymas: {{ comment.comment }}
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <v-row v-else>
                <v-col cols="12">Kraunama informacija...</v-col>
              </v-row>
            </v-container>
          </v-expansion-panel-text>
        </v-expansion-panel>
      </v-expansion-panels>
    </div>
  </div>
</template>

<script>
import headerNav from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import searchStudent from "@/components/StudentSearch.vue";
import { mapGetters } from "vuex";
import groupSearch from "@/components/GroupSearch.vue";

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
  name: "UserInternships",
  data() {
    return {
      internships: [],
      selectedInternshipId: null,
      selectedInternshipComments: [],
      selectedStudent: "",
      students: [],
      selectedFilter: null,
      filterBy: ["Pagal grupę", "Pagal Vardą Pavardę"],
      openedPanel: null,
    };
  },
  components: {
    headerNav,
    searchStudent,
    groupSearch,
  },
  watch: {
    selectedStudent(newVal) {
      if (newVal && newVal.id) {
        this.handleStudentSelection(newVal.id);
      }
    },

    selectedFilter(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.internships = [];
        this.selectedStudent = "";
        this.selectedGroupId = null;
        this.selectedInternshipComments = [];
        this.openedPanel = null;
      }
    },
  },

  methods: {


    handleSelectedGroupId(groupId) {
      apiClient
        .post("/internships/student-group-active", { studentGroupId: groupId })
        .then((response) => {
          if (response.data) {
            const formattedInternships = response.data.map((internship) => {
              const studentName =
                internship.user_profiles && internship.user_profiles.length > 0
                  ? internship.user_profiles[0].fullname
                  : "No name available";

              return {
                internshipId:internship.id,
                company_name: internship.company.company_name,
                date_from: internship.date_from,
                date_to: internship.date_to,
                student_name: studentName,
              };
            });

            this.internships = formattedInternships;

            formattedInternships.forEach((internship) => {
              console.log(
                `Internship for ${internship.student_name}: Company - ${internship.company_name}, from ${internship.date_from} to ${internship.date_to}`
              );
            });
          } else {
            console.log("No internships found in response.");
          }
        })
        .catch((error) => {
          console.error("Error searching for students:", error);
        });
    },

    fetchInternshipsForRoleFive() {
      apiClient
        .get("/internships")
        .then((response) => {
          this.internships = response.data;
        })
        .catch((error) => {
          console.error("Error fetching internships:", error);
        });
    },

    handleStudentSelection(studentId) {
      apiClient
        .post(`/user/internships`, { userId: studentId })
        .then((response) => {
          if (response.data.internships && response.data.userProfile) {
            const formattedInternships = response.data.internships.map(
              (internship) => {
                return {
                  internshipId:internship.id,
                  company_name: internship.company.company_name,
                  date_from: internship.date_from,
                  date_to: internship.date_to,
                  student_name: response.data.userProfile.fullname,
                };
              }
            );

            this.internships = formattedInternships;
            formattedInternships.forEach((internship) => {
              console.log(
                `Internship for ${internship.student_name}: Company - ${internship.company_name}, from ${internship.date_from} to ${internship.date_to}`
              );
            });
          } else {
            console.log("No internships or user profile found in response.");
          }
        })
        .catch((error) => {
          console.error(
            "Error fetching internships for selected student:",
            error
          );
        });
    },

    handleInternshipClick(internshipId) {
      console.log("Clicked internship ID:", internshipId);
      if (this.selectedInternshipId === internshipId) {
        this.selectedInternshipId = null;
        return;
      }
      this.selectedInternshipId = internshipId;
      this.selectedInternshipComments = [];

      apiClient
        .post(`/internship/comments`, {
          internshipId: this.selectedInternshipId,
        })
        .then((response) => {
          this.selectedInternshipComments = response.data;
          console.log(this.selectedInternshipComments.date_from);
        })
        .catch((error) => {
          console.error("Error fetching internship details:", error);
          this.selectedInternshipComments = [];
        });
    },

    onStudentInput(value) {
      const studentName = event.target.value;
      if (typeof studentName === "string" && studentName.trim() !== "") {
        this.debouncedSearchStudents(studentName);
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
  },

  mounted() {
    console.log(this.getUser.role_id);
    this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 50);

    if (this.getUser.role_id === 5) {
      this.fetchInternshipsForRoleFive();
    }
  },
  computed: {
    ...mapGetters(["getUser"]),

    isRoleFive() {
      return this.getUser.role_id === 5;
    },

    showStudentInput() {
      return this.selectedFilter === "Pagal Vardą Pavardę";
    },

    showGroupInput() {
      return this.selectedFilter === "Pagal grupę";
    },
  },
};
</script>

<style>
.fieldDiv {
  width: 500px;
}

.studentSearchInput {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
.mainPageDiv {
  padding: 0 200px;
}
.mainInternshipDiv {
  padding: 50px 100px;
}
.comment-details {
  display: flex;
  border-bottom: 1px rgb(234, 225, 225) solid;
  padding: 20px 0;
}
</style>
