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
      <div class="profileInformation">
        <div class="selections">
          <v-tabs v-model="tab" color="black" align-tabs="center">
            <v-tab :value="1">Asmeninė informacija</v-tab>
            <v-tab :value="2">Mokslo duomenys</v-tab>
            <v-tab :value="3">Praktikos duomenys</v-tab>
          </v-tabs>
        </div>
        <div class="changePhotoDiv">
          <div class="photo">
            <img :src="imagePreview ?? userIcon" alt="" />
          </div>
          <div class="photoChangeBtn">
            <input type="file" ref="fileInput" style="display: none;" @change="handleFileChange" />
            <v-btn rounded="xl" variant="outlined" @click="triggerFileInput">Keisti nuotrauką</v-btn>
            <h2>Nuotraukos reikalavimai: 256 x 256px PNG arba JPG formatas</h2>
          </div>
        </div>
        <div class="inputDiv">
          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">Vardas</div>
            <v-text-field density="compact" placeholder="Vardenis" variant="outlined"
              v-if="userData && userData.first_name !== undefined" v-model="userData.first_name"></v-text-field>
          </div>

          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">Pavardė</div>
            <v-text-field density="compact" placeholder="Pavardenis" variant="outlined"
              v-if="userData && userData.last_name !== undefined" v-model="userData.last_name"></v-text-field>
          </div>

          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">El. paštas</div>
            <v-text-field density="compact" placeholder="vardenis@kvk.lt" variant="outlined"
              v-if="userData && userData.user.email !== undefined" v-model="userData.user.email"></v-text-field>
          </div>

          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">Adresas</div>
            <v-text-field density="compact" placeholder="Bijunų g. 10A" variant="outlined"
              v-if="userData && userData.address !== undefined" v-model="userData.address"></v-text-field>
          </div>

          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">Šalis</div>
            <v-select disabled v-if="userData && userData.country !== undefined" v-model="userData.country"
              label="Lietuva"></v-select>
          </div>
          <div class="fieldDiv">
            <div class="text-subtitle-1 text-bold-emphasis">Kompanija</div>
            <v-autocomplete v-model="company_name" item-value="id" item-title="company_name" :items="companies"
              label="Pasirinkite kompanija" v-if="userData && userData.description !== undefined"></v-autocomplete>
          </div>
        </div>
        <div class="text-subtitle-1 text-bold-emphasis">Aprašas</div>
        <v-textarea label="Aprašymas" v-model="userData.description"
          v-if="userData && userData.description !== undefined"></v-textarea>

        <div class="bottomButtons">
          <v-btn color="#0D47A1" rounded="xl" variant="elevated" type="submit">Išsaugoti</v-btn>
          <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
        </div>
      </div>
    </div>
  </form>
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
      userData: null,
      company_id: 0,
      companies: [],
      selectedImage: null,
      showSuccessAlert: false,
    };
  },
  components: {
    customHeader,
  },
  mounted() {
    apiClient
      .get("/companies", { withCredentials: true })
      .then((response) => {
        this.companies = response.data;
        this.registrationData.company_id = response.data.id;
        this.company_id = response.data.id;
      });

    this.fetchUserData();
  },
  methods: {
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
      setTimeout(() => this.showSuccessAlert = false, 6000);
        })
        .catch((error) => {
          console.error("Error saving changes:", error);
        });

      if (this.selectedImage) {
        let formData = new FormData();
        formData.append('image', this.selectedImage);

        apiClient
          .post("/profile/update-picture", formData, {
            withCredentials: true,
            headers: {
              'Content-Type': 'multipart/form-data'
            }
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
    }
  },
  computed: {
    imagePreview() {
      return this.selectedImage ? URL.createObjectURL(this.selectedImage) : null;
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
