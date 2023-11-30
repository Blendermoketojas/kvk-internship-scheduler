<template>
  <header-nav></header-nav>

  <div class="mainPageDiv">
    <div class="pageDescription">
      <h1>Praktikų sąrašas</h1>
      <h2>Čia galite peržiūrėti vykstamas ir pasibaigusias praktikas</h2>
    </div>
    <div class="mainInternshipDiv">
      <div class="studentSearchInput">

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

      </div>
      <v-expansion-panels>
        <v-expansion-panel
          v-for="internship in internships"
          :key="internship.id"
        >
          <v-expansion-panel-title
            class="panelHeader"
            @click="handleInternshipClick(internship.id)"
          >
            <v-container>
              <v-row no-gutters>
                <v-col cols="3">
                  {{ internship.company.company_name }}
                </v-col>
                <v-col cols="3">
                  <div>Nuo: {{ internship.date_from }}</div>
                </v-col>
                <v-col cols="3">
                  <div>Iki: {{ internship.date_to }}</div>
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
    };
  },
  components: {
    headerNav,
    searchStudent,
  },
  watch: {
  selectedStudent(newVal) {
    if (newVal && newVal.id) {
      this.handleStudentSelection(newVal.id);
    }
  },
},

  methods: {
   
    handleStudentSelection(studentId) {
    apiClient
      .post(`/user/internships`,  {userId: studentId} )
      .then(response => {
        console.log("Internships for student:", response.data);
        this.internships=response.data;
      })
      .catch(error => {
        console.error("Error fetching internships for selected student:", error);
      });
  },


    handleInternshipClick(internshipId) {
      if (this.selectedInternshipId === internshipId) {
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

    apiClient
      .get("/internships")
      .then((response) => {
        this.internships = response.data;
        this.internships.forEach((internship) => {});
      })
      .catch((error) => {
        console.error("Error fetching internships:", error);
      });


      this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 500);


  },
  computed: {
    ...mapGetters(["getUser"]),
  },
};
</script>

<style>

.fieldDiv{
    width: 500px;
  }

.studentSearchInput {
  display: flex;
  justify-content: center;
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
