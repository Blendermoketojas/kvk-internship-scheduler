<template>
  <custom-header></custom-header>
  <div class="mainProfile">
    <div class="pageDescription">
      <h1>Profilis</h1>
      <h2>Čia galite koreguoti asmeninę informaciją, mokslo duomenis</h2>
    </div>
    <div class="profileInformation">
    <div class="selections">
        <v-tabs
        v-model="tab"
        color="black"
        align-tabs="center"
      >
        <v-tab :value="1">Asmeninė informacija</v-tab>
        <v-tab :value="2">Mokslo duomenys</v-tab>
        <v-tab :value="3">Praktikos duomenys</v-tab>
      </v-tabs>
    </div>
    <div class="changePhotoDiv">
        <div class="photo">
      <img :src="userIcon" alt="" />
    </div>
    <div class="photoChangeBtn">
      <v-btn rounded="xl" variant="outlined">Keisti nuotrauką</v-btn>
      <h2>Nuotraukos reikalavimai: 256 x 256px PNG arba JPG formatas</h2>
    </div>
    </div>
    <div class="inputDiv">
        <div class="fieldDiv">
      <div class="text-subtitle-1 text-bold-emphasis">
       Vardas
      </div>
      <v-text-field
        density="compact"
        placeholder="Vardenis"
        variant="outlined"
        v-if="userData && userData[0].first_name !== undefined"
        v-model="userData[0].first_name"
      ></v-text-field>
    </div>
      
    <div class="fieldDiv">
      <div class="text-subtitle-1 text-bold-emphasis">
        Pavardė
      </div>
      <v-text-field
        density="compact"
        placeholder="Pavardenis"
        variant="outlined"
        v-if="userData && userData[0].last_name !== undefined"
        v-model="userData[0].last_name"
      ></v-text-field>
</div>

<div class="fieldDiv">
      <div class="text-subtitle-1 text-bold-emphasis">
       El. paštas
      </div>
      <v-text-field
        density="compact"
        placeholder="vardenis@kvk.lt"
        variant="outlined"
        v-if="userData && userData[0].user.email !== undefined"
        v-model="userData[0].user.email"
      ></v-text-field>
</div>


<div class="fieldDiv">
      <div class="text-subtitle-1 text-bold-emphasis">
        Adresas
      </div>
      <v-text-field
        density="compact"
        placeholder="Bijunų g. 10A"
        variant="outlined"
        v-if="userData && userData[0].address !== undefined"
        v-model="userData[0].address"
      ></v-text-field>
</div>

<div class="fieldDiv">
      <div class="text-subtitle-1 text-bold-emphasis">
        Šalis
      </div>
      <v-select
      disabled
      v-if="userData && userData[0].country !== undefined"
     v-model="userData[0].country"
      label="Lietuva"
    ></v-select>
</div>
<div class="fieldDiv">
  <div class="text-subtitle-1 text-bold-emphasis">
    Kompanija
  </div>
  <v-select

  label="UAB 'Kompanija'"
></v-select>
</div>

</div>
    <div class="text-subtitle-1 text-bold-emphasis">
        Aprašas
      </div>
      <v-textarea label="Aprašymas" v-model="userData[0].description"    v-if="userData && userData[0].description !== undefined"></v-textarea>
 

    <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated"  @click="saveChanges">Išsaugoti</v-btn>
        <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
    </div>
    </div>
  </div>
</template>

<script>
import userIcon from "@/assets/Photos/UserIcon.png";
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from '@/utils/api-client';

export default {
  name: "ProfileInfo",
  data() {
    return {
      userIcon,
      userData: null,
      countries: ["Lietuva", /* Add other countries as needed */],
      companies: ["UAB 'Kompanija'", /* Add other companies as needed */],
      editedUserData: {
        first_name: "",
        last_name: "",
        description: "",
        email: "",
        password: "",
        company_id: "",
        country: "",
        address: "",
        image_path: "",
      },
    };
  },
  components: {
    customHeader,
  },
  mounted() {
   
   this.fetchUserData();
  },
  methods: {
 fetchUserData() {
       apiClient.get('/profile',{ withCredentials: true }).then(response=>{ this.userData = response.data; this.editedUserData = { ...response.data[0], email: response.data[0].user.email  } });
   
 },
 saveChanges() {
      // Send editedUserData to the /profile/update endpoint
      apiClient.put('/profile/update', this.editedUserData, { withCredentials: true })
        .then(response => {
          console.log('Changes saved:', response.data);
        })
        .catch(error => {
          console.error('Error saving changes:', error);
        });
    },
  },
};
</script>

<style scoped>
.fieldDiv{
    width: 450px;
    display: inline-block;
}
.inputDiv{
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: space-between;
}

.mainProfile{
padding: 0 200px;

}
.bottomButtons{
    display: flex;
    justify-content: center
}
.bottomButtons .v-btn{
    width: 200px;
    margin: 0 10px;
}
.profileInformation{
    padding: 0 250px;
}
.photo{
height: 120px;
display: inline-block;
margin-right: 40px;
}
.photoChangeBtn{
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    align-content: flex-start;
    justify-content: space-evenly;
    align-items: flex-start;
    width: 300px;
}
.changePhotoDiv{
   display: flex;
}

img{
    height:90%;
}
h2{
    display: inline-block;
    font-size: 15px;
    color: rgb(170, 167, 167);
    font-weight: 400;
}
</style>
