<template>
  <header-nav></header-nav>
<div class="bodyDiv">
  <div class="mainPageDiv">
    <div class="pageDescription">
      <h1>Praktikų sąrašas</h1>
      <h2>Čia galite peržiūrėti vykstamas ir pasibaigusias praktikas</h2>
    </div>
    <div class="mainInternshipDiv">
      <div class="studentSearchInput" v-if="isRoleOne">
        <div class="fieldDiv">
          <div class="text-subtitle-1 text-bold-emphasis">Filtruoti pagal:</div>
          <v-select
            v-model="selectedFilter"
            :items="filterBy"
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
            no-data-text="Nėra ieškomo studento"
          ></v-autocomplete>
        </div>

        <group-search
          v-if="showGroupInput"
          @update:selectedGroupId="handleSelectedGroupId"
          @group-selected="handleGroupSelection"
        ></group-search>
        <!-- @update:selectedGroupId="handleSelectedGroupId" -->
      </div>
      <v-checkbox
      v-if="isRoleMentor"
        label="Neįvertitos praktikos"
        v-model="isCheckboxChecked"
      ></v-checkbox>

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

      <v-expansion-panels v-model="openedPanel">
        <v-expansion-panel
          v-for="internship in internships"
          :key="internship.internshipId"
        >
          <div v-if="isEvaluationModalVisible" class="modal">
            <div class="evaluation-modal-content">
              <h1>Įvertinkite studento praktiką:</h1>
              <div class="text-subtitle-1 text-bold-emphasis">Įvertinimas:</div>
              <v-select
                :items="['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']"
                v-model="evaluationGrade"
                label="Įvertinimas"
              ></v-select>
              <div class="text-subtitle-1 text-bold-emphasis">Komentaras</div>
              <v-textarea v-model="evaluationComment" label="Komentaras">
              </v-textarea>

              <div class="modalBtn">
                <v-btn
                  variant="tonal"
                  width="150px"
                  color="green"
                  rounded="lg"
                  @click="submitEvaluation"
                  >Išsaugoti</v-btn
                >
                <v-btn
                  variant="tonal"
                  width="150px"
                  rounded="lg"
                  @click="isEvaluationModalVisible = false"
                  >Atšaukti</v-btn
                >
              </div>
            </div>
          </div>
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
                <v-col v-if="!internship.isActive && isRoleFive" cols="2">
                  <v-btn @click="evaluateInternship(internship.internshipId)"
                    >Įsivertinti</v-btn
                  >
                </v-col>

                <v-col v-if="!internship.isActive && isRoleMentor" cols="2">
                  <v-btn @click="openEvaluationModal(internship.internshipId)"
                    >Įvertinti</v-btn
                  >
                </v-col>
                <v-col  cols="2">
                  <div class="iconButtons">
                    <button class="styleless-button" @click="handleUpload">
                      <v-icon icon="mdi-upload"></v-icon>
                    </button>

                    <button
                      v-if="isRoleOne"
                      class="styleless-button"
                      @click="handleEditInternship(internship.internshipId)"
                    >
                      <v-icon icon="mdi-pencil"></v-icon>
                    </button>

                    <button
                      v-if="isRoleOne"
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
            <v-container v-if="isLoading">
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
                <v-col cols="12">Nėra įvestų komentarų.</v-col>
              </v-row>
            </v-container>
          </v-expansion-panel-text>
        </v-expansion-panel>
      </v-expansion-panels>
    </div>
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
      evaluationGrade: null,
      evaluationComment: "",
      evaluationDate: "",
      selectedInternshipIdForEvaluation: null,
      isCheckboxChecked: false,
      internships: [],
      selectedInternshipId: null,
      selectedGroupId: null,
      selectedInternshipComments: [],
      selectedStudent: "",
      students: [],
      selectedFilter: null,
      filterBy: ["Pagal grupę", "Pagal Vardą Pavardę"],
      openedPanel: null,
      isModalVisible: false,
      isEvaluationModalVisible: false,
      isLoading: false,
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
    isCheckboxChecked(newVal) {
      if (newVal) {
        this.fetchNotEvaluatedInternships();
      } 
    },

    selectedGroupId(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.handleSelectedGroupId(newVal);
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
    handleGroupSelection(selectedGroup){
      console.log('Group selected:', selectedGroup);
    },

    submitEvaluation() {
      const payload = {
        grade: this.evaluationGrade,
        comment: this.evaluationComment,
        internship_id: this.selectedInternshipIdForEvaluation,
        date:'2023-12-12',
        is_final: true,
      };

      apiClient
        .post("/result/grade/create", payload)
        .then((response) => {
          console.log("Evaluation submitted successfully", response);
          this.isEvaluationModalVisible = false;
        })
        .catch((error) => {
          console.error("Error submitting evaluation:", error);
        });
    },

    fetchNotEvaluatedInternships() {
      this.isLoading = true;
      apiClient
        .get("/filter/not-evaluated-internships")
        .then((response) => {
          this.internships = response.data.map((internship) => {
            console.log(internship.duration_in_hours);
            const student =
              internship.user_profiles.length > 0
                ? internship.user_profiles[0]
                : null;

            return {
              internshipId: internship.id,
              title: internship.title,
              totalHours: internship.duration_in_hours,
              loggedHours: internship.logged_hours,
              companyId: internship.company_id,
              date_from: internship.date_from,
              date_to: internship.date_to,
              isActive: internship.is_active,
              isMentorEvaluated: internship.is_mentor_evaluated,
              isSelfEvaluated: internship.is_self_evaluated,
              isHeadOfInternshipEvaluated:
                internship.is_head_of_internship_evaluated,
              student_name: student ? student.fullname : "Unknown",
              company_name: internship.company
                ? internship.company.company_name
                : "Unknown",
              student_id: internship.user_profiles[0].id,
            };
          });
        })
        .catch((error) => {
          console.error("Error fetching not evaluated internships:", error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    evaluateInternship(internshipId) {
      this.$router.push({ name: "EvaluationDemo", params: { internshipId } });
    },

    handleEditInternship(internshipId) {
      console.log("edit", internshipId);
      this.$router.push({ name: "InternshipEdit", params: { internshipId } });
    },

    openDeleteModal(internshipId) {
      this.internshipToDelete = internshipId;
      this.isModalVisible = true;
    },

    openEvaluationModal(internshipId) {
      console.log("Opening evaluation modal for internship ID:", internshipId); 
      this.selectedInternshipIdForEvaluation = internshipId;
      this.isEvaluationModalVisible = true;
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
      this.$router.push("/document-upload");
    },

    handleSelectedGroupId(groupId) {
      console.log("Group ID received:", groupId);
      this.internships = [];
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
    fetchInternshipsForMentors() {
      this.isLoading = true;
      apiClient
        .get("/linked-students-internships-active")
        .then((response) => {
          if (Array.isArray(response.data)) {
            this.internships = response.data.map((internship) => {
              console.log(internship.is_active);
              const userProfile =
                internship.user_profiles && internship.user_profiles[0];
              return {
                internshipId: internship.id,
                title: internship.title,
                company_name: internship.company.company_name,
                date_from: internship.date_from,
                date_to: internship.date_to,
                loggedHours: internship.logged_hours,
                totalHours: internship.duration_in_hours,
                isActive: internship.is_active,
                student_id: userProfile ? userProfile.user_id : null,
                student_name: userProfile
                  ? userProfile.fullname
                  : "No name available",
              };
            });
          } else {
            console.error("Invalid response format.");
            this.internships = []; // Reset the internships array
          }
        })
        .catch((error) => {
          console.error("Error fetching internships for mentors:", error);
        })
        .finally(() => {
          this.isLoading = false; // Reset loading state
        });
    },

    fetchInternshipsForRoleFive() {
      this.isLoading = true; // Set loading state to true
      apiClient
        .get("/internships")
        .then((response) => {
          // The response contains an internships array and a user object
          if (response.data && Array.isArray(response.data.internships)) {
            const studentId = response.data.user.id;
            const studentName = response.data.user.fullname;
            console.log("studento id", studentId);
            this.internships = response.data.internships.map((internship) => {
              return {
                internshipId: internship.id,
                title: internship.title,
                company_name: internship.company.company_name,
                date_from: internship.date_from,
                date_to: internship.date_to,
                loggedHours: internship.logged_hours,
                totalHours: internship.duration_in_hours,
                isActive: internship.is_active,
                student_id: studentId,
                student_name: studentName,
              };
            });
          } else {
            console.error("Internships array is not present in the response.");
            this.internships = [];
          }
        })
        .catch((error) => {
          console.error("Error fetching internships:", error);
        })
        .finally(() => {
          this.isLoading = false; // Reset loading state
        });
    },

    handleStudentSelection(studentId) {
      if (!studentId) {
        console.error("No student ID provided for fetching internships.");
        return;
      } else {
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
      }
    },

    handleInternshipClick(internshipId) {
      this.isLoading = true;
      console.log("Clicked internship ID:", internshipId);
      localStorage.setItem("uploadInternshipId", `Internships|${internshipId}`);
      if (this.selectedInternshipId === internshipId) {
        this.selectedInternshipId = null;
        this.isLoading = false;
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
          this.isLoading = false;
        })
        .catch((error) => {
          console.error("Error fetching internship details:", error);
          this.selectedInternshipComments = [];
          this.isLoading = false;
        });
    },

    onStudentInput(event) {
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

    if (this.getUser.role_id === 4 || this.getUser.role_id === 3) {
      this.fetchInternshipsForMentors();
    }
  },
  computed: {
    ...mapGetters(["getUser"]),

    isRoleOne() {
      return this.getUser.role_id === 1;
    },
    isRoleFive() {
      return this.getUser.role_id === 5;
    },

    isRoleMentor() {
      return this.getUser.role_id === 4 || this.getUser.role_id === 3;
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
@import '@/styles/InternshipStyle/userInternship';

.evaluation-modal-content {
  height: 450px;

  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 40%;
  text-align: center;
  border: 1px solid rgb(121, 119, 119);
  align-items: center;
}
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
  width: 400px;
}

.studentSearchInput {
  display: flex;
  justify-content: space-around;
  flex-direction: row;
  align-items: center;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}

.comment-details {
  display: flex;
  border-bottom: 1px rgb(234, 225, 225) solid;
  padding: 20px 0;
}
</style>
