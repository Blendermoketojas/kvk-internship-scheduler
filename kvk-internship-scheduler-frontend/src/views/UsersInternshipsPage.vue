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
        <div v-if="isModalVisible" class="modal">
          <div class="modal-content">
            <h1>Ar norite pašalinti šią praktiką?</h1>
            <div class="modalBtn">
              <v-btn
                variant="tonal"
                width="150px"
                color="red"
                rounded="lg"
                @click="confirmDelete"
                >Taip</v-btn
              >
              <v-btn
                variant="tonal"
                width="150px"
                rounded="lg"
                @click="isModalVisible = false"
                >Ne</v-btn
              >
            </div>
          </div>
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
          :key="internship.internshipId"
        >
          <v-expansion-panel-title
            class="panelHeader"
            @click="handleInternshipClick(internship.internshipId)"
          >
            <v-container>
              <v-row no-gutters>
                <v-col cols="2">
                  <div>
                    <div><b>Studentas:</b></div>
                    <br />
                    <router-link
                      :to="{
                        name: 'StudentProfile',
                        params: { userId: internship.student_id },
                      }"
                      @click="checkStudentId(internship.student_id)"
                      class="student-name"
                      >{{ internship.student_name }}</router-link
                    >
                  </div>
                </v-col>
                <v-col cols="2">
                  <div><b>Įmonė: </b></div>
                  <br />{{ internship.company_name }}
                </v-col>
                <v-col cols="2">
                  <div>
                    <div><b>Nuo: </b></div>
                    <br />{{ internship.date_from }}
                  </div>
                </v-col>
                <v-col cols="2">
                  <div>
                    <div><b>Iki: </b></div>
                    <br />{{ internship.date_to }}
                  </div>
                </v-col>
                <v-col cols="2"
                  ><div><b>Valandos: </b></div>
                  <br />{{ internship.loggedHours }}/{{
                    internship.totalHours
                  }}</v-col
                >
                <v-col cols="2">
                  <div class="iconButtons">
                    <button class="styleless-button" @click="handleUpload">
                      <v-icon icon="mdi-upload"></v-icon>
                    </button>

                    <button
                      class="styleless-button"
                      @click="handleEditInternship(internship.internshipId)"
                    >
                      <v-icon icon="mdi-pencil"></v-icon>
                    </button>

                    <button
                      class="styleless-button"
                      @click="openDeleteModal(internship.internshipId)"
                    >
                      <v-icon icon="mdi-delete"></v-icon>
                    </button>
                  </div>
                </v-col>
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
      isModalVisible: false,
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
    handleEditInternship(internshipId) {
      console.log("edit", internshipId);
      this.$router.push({ name: "InternshipEdit", query: { internshipId } });
    },

    openDeleteModal(internshipId) {
      this.internshipToDelete = internshipId;
      this.isModalVisible = true;
    },

    confirmDelete() {
      console.log("confirmation", this.internshipToDelete);
      if (this.internshipToDelete) {
        apiClient
          .post(
            `/internship-delete`,
            { internshipId: this.internshipToDelete },
            { withCredentials: true }
          )
          .then((response) => {
            console.log("Internship deleted successfully");
            this.isModalVisible = false;
            this.internships = this.internships.filter(
              (internship) =>
                internship.internshipId !== this.internshipToDelete
            );
            this.internshipToDelete = null;
          })
          .catch((error) => {
            console.error("Error deleting internship:", error);
          });
      }
    },

    checkStudentId(studentId) {
      console.log("Student ID:", studentId);
    },
    handleUpload() {
      localStorage.setItem("uploadInternshipId", this.selectedInternshipId);
      this.$router.push("/document-upload");
    },
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
                internshipId: internship.id,
                loggedHours: internship.logged_hours,
                totalHours: internship.duration_in_hours,
                company_name: internship.company.company_name,
                date_from: internship.date_from,
                date_to: internship.date_to,
                student_name: studentName,
                student_id:
                  internship.user_profiles &&
                  internship.user_profiles.length > 0
                    ? internship.user_profiles[0].user_id
                    : null,
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
            const studentId = response.data.userProfile.id;
            const formattedInternships = response.data.internships.map(
              (internship) => {
                return {
                  internshipId: internship.id,
                  loggedHours: internship.logged_hours,
                  totalHours: internship.duration_in_hours,
                  company_name: internship.company.company_name,
                  date_from: internship.date_from,
                  date_to: internship.date_to,
                  student_name: response.data.userProfile.fullname,
                  student_id: studentId,
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
.modalBtn {
  display: flex;
  width: 100%;
  justify-content: space-evenly;
}

.modal {
  display: block;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  display: flex;
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  height: 250px;
  text-align: center;
  border: 1px solid rgb(121, 119, 119);
  justify-content: space-evenly;
  align-items: center;
  flex-direction: column;
}

.iconButtons button {
  margin: 0 10px;
}

.student-name {
  position: relative;
  z-index: 2000;
}

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
