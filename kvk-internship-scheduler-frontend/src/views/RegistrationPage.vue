<template>
  <header-nav></header-nav>
  <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="800" rounded="lg">

    <div class="inputFields">
      <div class="text-subtitle-1 text-medium-emphasis">Vardas</div>
      <v-text-field v-model="registrationData.first_name" density="compact" placeholder="Vardenis"
        variant="outlined"></v-text-field>
    </div>
    <div class="inputFields">
      <div class="text-subtitle-1 text-medium-emphasis">Pavardė</div>

      <v-text-field v-model="registrationData.last_name" density="compact" placeholder="Pavardenis"
        variant="outlined"></v-text-field>
    </div>
    <div class="inputFields">
      <div class="text-subtitle-1 text-bold-emphasis">Kompanija</div>
      <v-select v-model="registrationData.company_id" item-value="id" item-title="company_name" :items="companies"
        label="Pasirinkite kompanija"></v-select>
    </div>
    <div class="inputFields">
      <div class="text-subtitle-1 text-medium-emphasis">El. paštas</div>

      <v-text-field type="email" v-model="registrationData.email" density="compact" placeholder="vardas@kvk.lt"
        variant="outlined" min-height="0"></v-text-field>
    </div>
    <div class="inputFields">
      <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
        Slaptažodis
      </div>
      <v-text-field v-model="registrationData.password" :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'" density="compact" placeholder="Įveskite slaptažodį" variant="outlined"
        @click:append-inner="visible = !visible"></v-text-field>
    </div>
    <div class="inputFields">
      <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
        Patvirtinti Slaptažodį
      </div>
      <v-text-field v-model="repeatPassword" :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'" density="compact" placeholder="Pakartokite slaptažodį" variant="outlined"
        @click:append-inner="visible = !visible"></v-text-field>
    </div>

    <v-btn block class="mb-8" color="white" size="large" variant="tonal" @click="register">
      Registruotis
    </v-btn>
  </v-card>
</template>

<script>
import HeaderNav from "@/components/MobileHeader.vue";
import kvkLogo from "@/assets/Photos/KVKlogo.png";

export default {
  components: {
    HeaderNav,
  },
  data() {
    return {
      visible: false,
      kvkLogo,
      companies: [],
      registrationData: {
        company_id: 0,
        first_name: '',
        last_name: '',
        email: '',
        password: '',
      },
      repeatPassword: ''
    };
  },
  mounted() {
    this.$axios.get('http://localhost:8000/api/v2/companies', { withCredentials: true })
    .then(response => { this.companies = response.data; this.registrationData.company_id = response.data[0].id });

  },
  methods: {
    async register() {
    try {
      const response = await this.$axios.post('http://localhost:8000/api/v2/register', {
        ...this.registrationData,
        repeatPassword: this.repeatPassword,
      }, { withCredentials: true });
      console.log(response.data);

    } catch (error) {
      console.error('Registration failed', error);
   
    }
 
},
  },
};

</script>

<style scoped>
.v-card {
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: space-between;
}

.inputFields {
  width: 300px;
  display: inline-block;
}


button {
  width: 50%;
  background-color: #58acfa;
  color: #fff;
  float: right;
  padding: 0.5em 1em;
  border: none rgba(0, 0, 0, 0);
  line-height: normal;
}

.footerYears {
  font-size: 20px;
}

footer {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  text-align: center;
  height: 5rem;
  background: linear-gradient(141deg, #58acfa 0%, #81bef7 51%, #a9d0f5 75%);
  width: 100%;
  bottom: 0;
}

.footerLogo {
  position: absolute;
  margin-right: 2%;
  height: 4rem;
  margin: 1% 1%;
  right: 0;
  bottom: -6px;
  width: 10%;
}

.footerText {
  width: 70%;
  display: inline-block;
  text-align: center;
}

h2,
.footerA {
  display: inline;
  color: white;
}
</style>
