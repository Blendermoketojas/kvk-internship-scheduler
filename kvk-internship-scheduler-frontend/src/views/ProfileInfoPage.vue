<template>
  <custom-header></custom-header>
  <form @submit.prevent="saveChanges">
    <div class="mainProfile">
      <v-alert
        v-if="showSuccessAlert"
        color="success"
        icon="$success"
        title="Pavyko!"
        text="Profilio informacija buvo atnaujinta!"
      ></v-alert>
      <div class="pageDescription">
        <h1>Profilis</h1>
        <h2>Čia galite koreguoti asmeninę informaciją, mokslo duomenis</h2>
      </div>
      <div class="selections">
        <v-tabs v-model="tab" color="black" align-tabs="center">
          <v-tab value="1">Asmeninė informacija</v-tab>
          <v-tab value="2">Praktikos duomenys</v-tab>
        </v-tabs>
      </div>

      <v-window v-model="tab">
        <v-window-item value="1">
          <div class="profileInformation" value="1">
            <div class="changePhotoDiv">
              <div class="photo">
                <img :src="imagePreview ?? userIcon" alt="" />
              </div>
              <div class="photoChangeBtn">
                <input
                  type="file"
                  ref="fileInput"
                  style="display: none"
                  @change="handleFileChange"
                />
                <v-btn
                  :disabled="isStudentProfilePage"
                  rounded="xl"
                  variant="outlined"
                  @click="triggerFileInput"
                  >Keisti nuotrauką</v-btn
                >
                <h2>
                  Nuotraukos reikalavimai: 256 x 256px PNG arba JPG formatas
                </h2>
              </div>
            </div>
            <div class="inputDiv">
              <div class="fieldDiv">
                <div class="text-subtitle-1 text-bold-emphasis">Vardas</div>
                <v-text-field
                  density="compact"
                  placeholder="Vardenis"
                  variant="outlined"
                  v-if="userData && userData.first_name !== undefined"
                  v-model="userData.first_name"
                  :disabled="isStudentProfilePage"
                ></v-text-field>
              </div>

              <div class="fieldDiv">
                <div class="text-subtitle-1 text-bold-emphasis">Pavardė</div>
                <v-text-field
                  density="compact"
                  placeholder="Pavardenis"
                  variant="outlined"
                  v-if="userData && userData.last_name !== undefined"
                  v-model="userData.last_name"
                  :disabled="isStudentProfilePage"
                ></v-text-field>
              </div>

              <div class="fieldDiv">
                <div class="text-subtitle-1 text-bold-emphasis">El. paštas</div>
                <v-text-field
                  density="compact"
                  placeholder="vardenis@kvk.lt"
                  variant="outlined"
                  v-if="userData && userData.user.email !== undefined"
                  v-model="userData.user.email"
                  :disabled="isStudentProfilePage"
                ></v-text-field>
              </div>

              <div class="fieldDiv">
                <div class="text-subtitle-1 text-bold-emphasis">Adresas</div>
                <v-text-field
                  density="compact"
                  placeholder="Bijunų g. 10A"
                  variant="outlined"
                  v-if="userData && userData.address !== undefined"
                  v-model="userData.address"
                  :disabled="isStudentProfilePage"
                ></v-text-field>
              </div>

              <div class="fieldDiv">
                <div class="text-subtitle-1 text-bold-emphasis">Šalis</div>
                <v-select
                  disabled
                  v-if="userData && userData.country !== undefined"
                  v-model="userData.country"
                  density="compact"
                  label="Šalis"
                ></v-select>
              </div>
              <div class="fieldDiv" v-if="isRoleFour">
                <div class="text-subtitle-1 text-bold-emphasis">Įmonė</div>
                <v-autocomplete
                  v-model="company_name"
                  item-value="id"
                  item-title="company_name"
                  :items="companies"
                  label="Pasirinkite kompanija"
                  v-if="userData && userData.description !== undefined"
                  :disabled="isStudentProfilePage"
                ></v-autocomplete>
              </div>
            </div>
            <div class="text-subtitle-1 text-bold-emphasis">Aprašas</div>
            <v-textarea
              label="Aprašymas"
              v-model="userData.description"
              v-if="userData && userData.description !== undefined"
              :disabled="isStudentProfilePage"
            ></v-textarea>
          </div>
          <div class="bottomButtons">
            <v-btn color="#0D47A1" rounded="xl" variant="elevated" type="submit"
              >Išsaugoti</v-btn
            >
            <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
          </div>
        </v-window-item>
        <v-window-item value="2">
          <v-expansion-panels v-if="internships && internships.length > 0">
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
          <div v-else class="no-internships">Nėra praktikos informacijos.</div>
        </v-window-item>
      </v-window>
    </div>
  </form>
</template>

<script>
import userIcon from "@/assets/Photos/UserIcon.png";
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import { mapGetters } from "vuex";
import groupSearch from "@/components/GroupSearch.vue";

export default {
  name: "ProfileInfo",
  data() {
    return {
      userIcon,
      userIdFromUrl: null,
      userData: null,
      company_id: 0,
      companies: [],
      selectedImage: null,
      showSuccessAlert: false,
      tab: "1",
      internships: [],
      selectedInternshipId: null,
      selectedInternshipComments: [],
      selectedStudent: "",
      students: [],
      internshipsLoaded: false,
    };
  },
  components: {
    customHeader,
    groupSearch,
  },
  mounted() {
    this.handleDataFetching();
    if (this.$route.path.includes("/student-profile-info")) {
      const userId = this.$route.params.userId;
      this.fetchInternshipData(userId);
    } else {
      this.fetchUserData();
    }

    apiClient.get("/companies", { withCredentials: true }).then((response) => {
      this.companies = response.data;
      this.registrationData.company_id = response.data.id;
      this.company_id = response.data.id;
    });

    if (!this.isStudentProfilePage) {
      this.fetchUserData();
    }

    if (this.getUser.role_id === 5) {
      this.fetchInternshipsForRoleFive();
    }
  },
  methods: {
    fetchInternshipData(userId) {
      apiClient
        .post(`/user/internships`, { userId: userId })
        .then((response) => {
          this.internships = response.data.internships;
          this.internshipsLoaded = true;
        })
        .catch((error) => {
          console.error("Error fetching internships: ", error);
        });
    },
    handleDataFetching() {
      if (this.$route.path.includes('/student-profile-info')) {
        const userId = this.$route.params.userId; 
        this.fetchProfileData(userId);
      } else {
        this.fetchUserData();
      }
    },
    fetchProfileData(userId) {
      apiClient.post(`/profile/id`, {userId : userId})
        .then(response => {
  this.userData=response.data;
        })
        .catch((error) => {
          console.error(
            "Error fetching internships for selected student:",
            error
          );
        });
    },

    handleStudentSelection(studentId) {
      apiClient
        .post(`/user/internships`, { userId: studentId })
        .then((response) => {
          console.log("Internships for student:", response.data);
          this.internships = response.data;
        })
        .catch((error) => {
          console.error(
            "Error fetching internships for selected student:",
            error
          );
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

    fetchInternshipsForRoleFive() {
      apiClient
        .get("/internships")
        .then((response) => {
          this.internships = response.data;
          this.internshipsLoaded = true;
        })
        .catch((error) => {
          console.error("Error fetching internships:", error);
        });
    },

    // fetchInternshipsForRoleFive() {
    //   apiClient
    //     .get("/internships")
    //     .then((response) => {
    //       this.internships = response.data;
    //       this.internshipsLoaded = true;
    //     })
    //     .catch((error) => {
    //       console.error("Error fetching internships:", error);
    //     });
    // },

    fetchUserData() {
      apiClient.get("/profile", { withCredentials: true }).then((response) => {
        this.userData = response.data;
        this.userIcon = "http://localhost:8000" + response.data.image_path;
      });
    },
    saveChanges() {
      const userDataWithPassword = {
        ...this.userData,
        email: this.userData.user.email,
        password: "password",
      };

      apiClient
        .put("/profile/update", userDataWithPassword, { withCredentials: true })
        .then((response) => {
          console.log("Changes saved:", response.data);
          this.showSuccessAlert = true;
          setTimeout(() => (this.showSuccessAlert = false), 6000);
        })
        .catch((error) => {
          console.error("Error saving changes:", error);
        });

      if (this.selectedImage) {
        let formData = new FormData();
        formData.append("image", this.selectedImage);

        apiClient
          .post("/profile/update-picture", formData, {
            withCredentials: true,
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            console.log("Image changes saved:", response.data);
          })
          .catch((error) => {
            console.error("Error saving changes:", error);
          });
      }
    },
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileChange(event) {
      this.selectedImage = event.target.files[0];
    },
  },
  created() {
    const userId = this.$route.query.userId;
    console.log("Received user ID:", userId);
  },
  watch: {
    tab(newVal) {
      if (newVal === "2" && !this.internshipsLoaded) {
        this.fetchInternshipsForRoleFive();
      }
    },
  },
  computed: {
    isStudentProfilePage() {
      return this.$route.path.includes("/student-profile-info");
    },
    metodas() {
      console.log("URL Tinka", isStudentProfilePage);
    },
    imagePreview() {
      return this.selectedImage
        ? URL.createObjectURL(this.selectedImage)
        : null;
    },
    ...mapGetters(["getUser"]),
    isRoleFive() {
      return this.getUser.role_id === 5;
    },
    isRoleFour() {
      return this.getUser.role_id === 4;
    },
  },
};
</script>

<style scoped>
.no-internships {
  text-align: center;
  padding: 20px;
  color: #757575;
}

.v-expansion-panels {
  border: 1px solid rgb(234, 229, 229);
  border-radius: 5px;
}

.comment-details {
  display: flex;
  border-bottom: 1px rgb(234, 225, 225) solid;
  padding: 20px 0;
}

.selections {
  margin: 10px 0;
}
.fieldDiv {
  width: 450px;
  display: inline-block;
}

.inputDiv {
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: space-between;
}

.mainProfile {
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

.profileInformation {
  padding: 0 250px;
}

.photo {
  height: 120px;
  display: inline-block;
  margin-right: 40px;
}

.photoChangeBtn {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  align-content: flex-start;
  justify-content: space-evenly;
  align-items: flex-start;
  width: 300px;
}

.changePhotoDiv {
  display: flex;
}

img {
  height: 90%;
  width: 120px;
  height: 110px;
  border-radius: 50%;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
</style>
