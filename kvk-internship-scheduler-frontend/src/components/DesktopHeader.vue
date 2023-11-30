<template>
  <div class="main">
    <div class="kvkLogoDiv">
      <img class="logo" :src="layoutLogo" alt="KVK Logo" />
    </div>
    <div class="btn" id="menu-activator-learning-material">
      <router-link class="redirectText" to="/calendar"
        >Praktika</router-link
      >
      <v-menu open-on-hover activator="#menu-activator-learning-material">
        <v-list>
          <v-list-item
            v-for="(internshipItem, index) in internshipItems"
            :key="index"
            :value="index"
          >
            <router-link class="redirectText" :to="internshipItem.route">
              <v-list-item-title>{{ internshipItem.title }}</v-list-item-title>
            </router-link>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <div class="btn" id="menu-activator">
      <router-link class="redirectText" to="/documents">Dokumentai</router-link>
      <v-menu open-on-hover activator="#menu-activator">
        <v-list>
          <v-list-item
            v-for="(documentItem, index) in documentItems"
            :key="index"
            :value="index"
          >
          <router-link @click="setupUpload" :to="documentItem.route">
            <v-list-item-title>{{ documentItem.title }}</v-list-item-title>
            </router-link>
          </router-link>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <div class="btn" id="menu-activator-results">
      <router-link class="redirectText" to="/my-results"
        >Mano rezultatai</router-link
      >
      <v-menu open-on-hover activator="#menu-activator-results">
        <v-list>
          <v-list-item
            v-for="(resultItem, index) in resultItems"
            :key="index"
            :value="index"
          >
          <router-link class="redirectText" :to="resultItem.route">
            <v-list-item-title>{{ resultItem.title }}</v-list-item-title>
            </router-link>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <div class="btn">
      <router-link class="redirectText" to="/learning-materials"
        >Mokymosi medžiaga</router-link
      >
    </div>
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
        { title: "Dokumentų įkėlimas", route: "/document-upload" },
      ],

      resultItems: [
        { title: "Rezultatų kūrimo forma", route: "/evaluation-creation" },
        { title: "Įsivertimas", route:'/empty'},
      ],

      internshipItems: [
        { title: "Kalendorius", route: "/calendar" },
        { title: "Praktikos priskirimas", route: "/internship-management" },
        { title: "Praktikos peržiūra", route: "/user-internships" },
      ],
    };
  },
  methods: {
    setupUpload() {
      this.$store.commit('setUploadAction', "Internship");
    }
  },
  created() {
    const userStored = localStorage.getItem("user");
    if (userStored) {
      this.user = JSON.parse(userStored);
    }
  },

  computed: {
    fullImagePath() {
      return this.user.image_path
        ? this.baseImageUrl + this.user.image_path
        : null;
    },

    ...mapGetters(["getUser"]),

    filteredDocumentItems() {
      if (this.getUser.role_id === 1) {
        return this.documentItems;
      } else if (this.getUser.role_id === 5) {
        return this.documentItems.slice(0, 1);
      }
    },
  },

  mounted() {
    console.log(this.getUser.role_id);
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
  margin-left: 40%;
}
.btn:last-of-type img {
  margin: 0 10px;
}
</style>
