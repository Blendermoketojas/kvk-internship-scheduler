<template>
  <div class="main">
    <div class="kvkLogoDiv">
      <img class="logo" :src="layoutLogo" alt="KVK Logo" />
    </div>
    <div class="btn" id="menu-activator-learning-material">
      <router-link class="redirectText" to="/user-internships">Praktika</router-link>
      <v-menu open-on-hover activator="#menu-activator-learning-material">
        <v-list>
          <v-list-item
            v-for="(internshipItem, index) in filteredInternshipItems"
            :key="index"
            :value="index"
            class="redirectText"
            :to="internshipItem.route"
          >
            <v-list-item-title>{{ internshipItem.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <div class="btn"  v-if="this.userRoleId!=2" id="menu-activator">
      <router-link class="redirectText"  to="/documents">Dokumentai</router-link>
      <v-menu open-on-hover activator="#menu-activator">
        <v-list>
          <v-list-item
            v-for="(documentItem, index) in filteredDocumentItems"
            :key="index"
            :value="index"
            class="redirectText"
            @click="setupUpload"
            :to="documentItem.route"
          >
            <v-list-item-title>{{ documentItem.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <div class="btn"  v-if="this.userRoleId!=2" id="menu-activator-results">
      <router-link class="redirectText" to="/evaluation"
        >Mano rezultatai</router-link
      >
      <v-menu open-on-hover activator="#menu-activator-results">
        <v-list>
          <v-list-item
            v-for="(resultItem, index) in filteredResultItems"
            :key="index"
            :value="index"
            class="redirectText"
            :to="resultItem.route"
          >
            <v-list-item-title>{{ resultItem.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <!-- <div class="btn" v-if="this.userRoleId!=2">
      <router-link class="redirectText" to="/learning-materials"
        >Mokymosi medžiaga</router-link
      >
    </div> -->
    <div class="btn">
      <router-link class="redirectText" to="/chat">Pokalbiai</router-link>
    </div>
    <div class="btn">
      <router-link class="redirectText" to="/help">Pagalba</router-link>
    </div>
    <div class="btn">
      <router-link
        class="redirectText"
        to="/profile-info"
        v-if="user.image_path"
      >
        <img class="userImg" :src="fullImagePath" alt="User Image" />
      </router-link>
      <router-link class="redirectText" to="/profile-info" v-else>
        <img
          src="https://freesvg.org/img/abstract-user-flat-4.png"
          alt="Default Image"
        />
      </router-link>
      <router-link class="redirectText" to="/profile-info">{{
        user.fullname
      }}</router-link>
      <router-link to="/">
        <v-icon :icon="'mdi mdi-logout'" size="35" color="black"></v-icon>
      </router-link>
    </div>
  </div>
</template>

<script>
import layoutLogo from "@/assets/Photos/KVKlogo.png";
import { mapGetters } from "vuex";

export default {
  name: "AppLayout",
  data() {
    return {
      layoutLogo,
      user: {
        fullname: null,
        image_path: null,
      },
      baseImageUrl: "http://localhost:8000",

      documentItems: [
        { title: "Dokumentų peržiūra", route: "/documents" },
      ],

      resultItems: [
        { title: "Rezultatų kūrimo forma", route: "/evaluation-creation" },
        { title: "Įsivertimas", route: "/evaluation-demo" },
        { title: "Įvertinimai", route: "/evaluation" },
      ],

      internshipItems: [
        { title: "Kalendorius", route: "/calendar" },
        { title: "Praktikos priskyrimas", route: "/internship-management" },
        { title: "Praktikos peržiūra", route: "/user-internships" },
        { title: "Mano studentai", route: "/student-list" },
        { title: "Užregistruoti vartotoją", route: "/user-creation" },
      ],
    };
  },
  methods: {
    setupUpload() {
      this.$store.commit("setUploadAction", "Internships");
    },
  },
  created() {
    const userStored = localStorage.getItem("user");
    if (userStored) {
      this.user = JSON.parse(userStored);
    }
  },

  computed: {
    ...mapGetters(["getUser"]),

    fullImagePath() {
      return this.user.image_path
        ? this.baseImageUrl + this.user.image_path
        : null;
    },
    userRoleId() {
      return this.getUser.role_id;
    },

    filteredInternshipItems() {
      let items = [];

      items.push({ title: "Praktikos peržiūra", route: "/user-internships" });

      if (this.userRoleId !== 5) {
      items.push({ title: "Statistika", route: "/statistics" });
    }

      if (this.userRoleId === 5) {
        items.push({ title: "Kalendorius", route: "/calendar" });
      }

      if (this.userRoleId === 1) {
        items.push({
          title: "Praktikos priskyrimas",
          route: "/internship-management",
        });
        items.push({
          title: "Užregistruoti vartotoją",
          route: "/user-creation",
        });
      }
      if (this.userRoleId === 3 || this.userRoleId === 4) {
        items.push({
          title: "Mano studentai",
          route: "/student-list",
        });
        items.push({
          title: "Praktikos priskyrimas",
          route: "/internship-management",
        });
      }
      return items;
    },

    filteredDocumentItems() {
      let items = [{ title: "Dokumentų peržiūra", route: "/documents" }];

      if (this.userRoleId !== 5) {
        items.push({ title: "Dokumentų įkėlimas", route: "/document-upload" });
      }

      return items;
    },

    filteredResultItems() {
      let items = [
        { title: "Įvertinimai", route: "/evaluation" },
      ];

      if (this.userRoleId === 1) {
        items.unshift({
          title: "Rezultatų kūrimo forma",
          route: "/evaluation-creation",
        });
      }
    
      return items;
    },
    
  },
  mounted() {
console.log(this.userRoleId);

  },
};
</script>

<style scoped>
.main {
  display: flex;
  align-items: center;
  height: 100px;
}

.kvkLogoDiv {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 15px;
}

img {
  height: 60%;
}

.redirectText {
  font-weight: 600;
  text-decoration: none;
  color: black;
}

.userImg {
  height: 40px;
  width: 40px;
  border-radius: 50%;
}

.btn:last-of-type {
  position: absolute;
  right: 0; 
  top: 10;
  margin-left: auto;
}

.btn:last-of-type img {
  margin: 0 10px;
}
</style>
