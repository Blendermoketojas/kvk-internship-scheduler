<template>
  <custom-header></custom-header>
  <v-alert v-if="showSuccessAlert" color="success" icon="$success" title="Pavyko!" text="Vartotojas sukurtas!"></v-alert>
  <v-alert v-if="showErrorAlert" color="error" icon="$error" title="Kaida!" text="Vartotojas nebuvo sukurtas!"></v-alert>

  <div class="mainDiv">
    <h1>Naudotojų pridėjimas</h1>
    <h2>Čia galite sukurti naują naudotoją</h2>

    <div class="mainContent">
      <div class="selections">
        <v-tabs v-model="tab" color="black" align-tabs="center">
          <v-tab value="1">Pridėti naudotoją</v-tab>
          <v-tab value="2">Pridėti įmonę</v-tab>
        </v-tabs>
      </div>

      <v-window v-model="tab">
        <v-window-item value="1">
          <div class="text-subtitle-1 text-bold-emphasis">Vardas</div>
          <v-text-field v-model="firstName" label="Vardenis"></v-text-field>
          <div class="text-subtitle-1 text-bold-emphasis">Pavardė</div>
          <v-text-field v-model="lastName" label="Pavardenis"></v-text-field>
          <div class="text-subtitle-1 text-bold-emphasis">El.paštas</div>
          <v-text-field v-model="email" label="elpastas@gmail.com"></v-text-field>
          <div class="text-subtitle-1 text-bold-emphasis">Naudotojo tipas</div>
          <v-select
            v-model="selectedRole"
            :items="roles"
            item-title="name"
            item-value="value"
            label="Pasirinkite naudotojo tipą"
          ></v-select>
          <div v-if="selectedRole === 5">
            <group-search  @update:selectedGroupId="selectedGroupId = $event"></group-search>
          </div>
          <div v-if="selectedRole === 4">
            <div class="text-subtitle-1 text-bold-emphasis">Įmonė</div>
            <v-autocomplete
              v-model="selectedCompanyId"
              item-value="id"
              item-title="company_name"
              :items="companies"
              label="Pasirinkite kompanija"
            ></v-autocomplete>
          </div>

          <div class="bottomButtons">
            <v-btn @click="handleSubmit" color="#0D47A1" rounded="xl" variant="elevated" type="submit"
              >Išsaugoti</v-btn
            >
            <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
          </div>
        </v-window-item>
        <v-window-item value="2">
          <div class="text-subtitle-1 text-bold-emphasis">Įmonė</div>
          <v-text-field label="UAB 'Įmonė'"></v-text-field>

          <div class="bottomButtons">
            <v-btn  color="#0D47A1" rounded="xl" variant="elevated" type="submit">Išsaugoti</v-btn>
            >
            <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
          </div>
        </v-window-item>
      </v-window>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import groupSearch from "@/components/GroupSearch.vue";
import apiClient from "@/utils/api-client";
import { mapGetters } from "vuex";

export default {
  name: "UserCreation",
  data() {
    return {
      selectedCompanyId:null,
      tab: "1",
      showSuccessAlert: false,
      showErrorAlert:false,
      selectedRole: null,
      roles: [
        {
          name: "Prodekanas",
          value: 1,
        },
        {
          name: "Katedros vedėjas",
          value: 2,
        },
        {
          name: "Praktikos vadovas",
          value: 3,
        },
        {
          name: "Mentorius",
          value: 4,
        },
        {
          name: "Studentas",
          value: 5,
        },
      ],
      firstName: "",
      lastName: "",
      email: "",
      selectedGroupId: null,
      company_id: null,
    };
  },
  components: {
    customHeader,
    groupSearch,
  },

  methods: {
    handleSelectedGroupId(groupId) {
      this.selectedGroupId = groupId;
      console.log("Selected Group ID:", this.selectedGroupId);
    },

    handleSubmit() {
      console.log(this.selectedCompanyId);
      let userData;
      if (this.selectedRole === 4) {
    userData = {
      firstName: this.firstName,
      lastName: this.lastName,
      email: this.email,
      roleId: this.selectedRole,
      companyId: this.selectedCompanyId,
    };
  } else {
    userData = {
      firstName: this.firstName,
      lastName: this.lastName,
      email: this.email,
      roleId: this.selectedRole,
      studentGroupId: this.selectedGroupId, 
    };
  }
  const apiEndpoint = (this.selectedRole === 4 || this.selectedRole === 5)
    ? '/register-external'
    : '/register';

    apiClient.post(apiEndpoint, userData)
      .then(response => {
        console.log('User registered successfully', response);
        this.showSuccessAlert = true;
          setTimeout(() => (this.showSuccessAlert = false), 6000);
      })
      .catch(error => {
        console.error('Error registering user', error);
        this.showErrorAlert = true;
          setTimeout(() => (this.showErrorAlert = false), 6000);
      });
  }


  },
  mounted() {
    apiClient.get("/companies", { withCredentials: true }).then((response) => {
      
      this.companies = response.data;
      this.company_id = response.data.id;
    });
  },
  watch: {
  selectedGroupId(newVal) {
    console.log("Selected Group ID changed:", newVal);
  }
},
};
</script>

<style scoped>
.mainDiv {
  padding: 0 200px;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}

.bottomButtons {
  display: flex;
  justify-content: center;
}

.bottomButtons .v-btn {
  width: 200px;
  margin: 0 10px;
}
.mainContent {
  padding: 0 250px;
}
</style>
