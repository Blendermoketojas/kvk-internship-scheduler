<template>
  <custom-header></custom-header>

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
    <v-text-field label="Vardenis"></v-text-field>
    <div class="text-subtitle-1 text-bold-emphasis">Pavardė</div>
    <v-text-field label="Pavardenis"></v-text-field>
    <div class="text-subtitle-1 text-bold-emphasis">Naudotojo tipas</div>
    <v-select
    v-model="selectedRole"
    :items="roles"
    item-title="name"
    item-value="value"
    label="Pasirinkite naudotojo tipą"
    ></v-select>

    <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated" type="submit"
          >Išsaugoti</v-btn
        >
        <v-btn rounded="xl" variant="outlined">Atšaukti</v-btn>
      </div>
</v-window-item>
<v-window-item value="2">
    <div class="text-subtitle-1 text-bold-emphasis">Įmonė</div>
    <v-text-field label="UAB 'Įmonė'"></v-text-field>

    <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated" type="submit"
          >Išsaugoti</v-btn
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

export default {
  name: "UserCreation",
  data() {
    return {
        tab: "1",
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
    };
  },
  components: {
    customHeader,
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
  .mainContent{
    padding: 0 250px;
  }
  
</style>
