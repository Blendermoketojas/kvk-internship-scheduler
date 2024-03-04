<template>
  <div class="bodyDiv">
    <div class="header">
      <img :src="kvkLogo" alt="KVK Logo" />
    </div>
    <v-alert v-if="showErrorAlert" color="error" icon="$error" title="Kaida!" text="Neteisingi prisijungimo duomenys!"></v-alert>

    <div class="middleDiv">
      <v-card
        class="mx-auto pa-12 pb-8"
        elevation="8"
        max-width="448"
        rounded="lg"
      >
        <div class="text-subtitle-1 text-medium-emphasis">
          Prisijungimo vardas
        </div>

        <v-text-field
          density="compact"
          placeholder="El. paštas"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
          v-model="loginData.email"
          :rules="rules"
        ></v-text-field>

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Slaptažodis

          <a
            class="text-caption text-decoration-none text-blue"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
          >
            Pamiršai prisijungimą?</a
          >
        </div>

        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Įveskite slaptažodį"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          v-model="loginData.password"
          @click:append-inner="visible = !visible"
          :rules="rules"
        ></v-text-field>

        <v-btn
          block
          class="mb-8"
          color="white"
          size="large"
          variant="tonal"
          @click="login"
        >
          Prisijungti
          <v-progress-circular
          size="small"
          v-if="isLoading"
          indeterminate
          color="white"
          ></v-progress-circular>
        </v-btn>

        <v-card-text class="text-center">
          <a
            class="text-blue text-decoration-none"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
            @click="goToRegistration"
          >
            Registracija <v-icon icon="mdi-chevron-right"></v-icon>
          </a>
        </v-card-text>
      </v-card>
    </div>
    <footer>
      <div class="footerText">
        <h2 class="footerYears">©2023</h2>
        <a class="footerA" href="https://www.kvk.lt/"
          >Klaipėdos valstybinė kolegija</a
        >
      </div>
      <img class="footerLogo" :src="kvkLogo" alt="footerLogo" />
    </footer>
  </div>
</template>

<script>
import kvkLogo from "@/assets/Photos/KVKlogo.png";

export default {
  name: "UserLogin",
  data() {
    return {
      rules: [
        value => !!value || '*Reikalingas laukas.',
      ],
      kvkLogo,
      showErrorAlert:false,
      loginData: {
        email: "",
        password: "",
      },
      visible: false,
      isLoading: false,
    };
  },
  methods: {
    goToRegistration() {
      this.$router.push("/registration");
    },
    login() {
      try {
        this.isLoading = true;
        this.$axios
          .post("http://localhost:8000/api/v2/login", this.loginData, {
            withCredentials: true,
          })
          .then((response) => {
            if (response.data.success) {
              this.$store.commit('setUser', response.data.user);
              const { fullname, image_path} = response.data.user;

              localStorage.setItem(
                "user",
                JSON.stringify({
                  fullname,
                  image_path,
                })
              );

            this.redirectUserBasedOnRole();
              this.isLoading = false;
            }
          }).catch((error) => {
          this.isLoading = false;
          this.showErrorAlert = true;
          setTimeout(() => (this.showErrorAlert = false), 4000);
        });
       } catch (error) {
        this.isLoading = false;
     
        if (error.response && error.response.status === 401) {
      
        }
      }
    },
    redirectUserBasedOnRole() {
    const role_id = this.$store.getters.getRoleId;

    if (role_id === 3 || role_id === 4) {
      this.$router.push('/student-list');
    } else if (role_id === 1) {
      this.$router.push('/internship-management');
    } else if (role_id === 5) {
      this.$router.push('/calendar');
      
    } else if (role_id === 2) {
      this.$router.push('/statistics');
      
    }else {
      this.$router.push('/');
    }
  },
  },
};
</script>

<style scoped>

.bodyDiv{
  min-height: 620px;
}
.middleDiv {
  margin-top: 3%;
}

.footerYears {
  font-size: 15px;
  margin: 10px;
}
.header {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 6rem;
  background: linear-gradient(141deg, #58acfa 0%, #81bef7 51%, #a9d0f5 75%);
}
img {
  width: 10%;
  height: 70%;
  display: block;
  margin: 1% auto;
  min-width: 151px;
}

body {
  font-family: sans-serif;
  line-height: 3;
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

a {
  display: block;
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
  display: flex;
  align-items: center;
  justify-content: center;

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
@media screen and (max-width: 600px) {
  .footerLogo{
  display: none;
  }
  }
</style>
