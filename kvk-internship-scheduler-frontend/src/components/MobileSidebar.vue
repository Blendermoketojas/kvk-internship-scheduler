<template>
  <div class="header">
    <v-btn
      color="primary"
      class="menu-button"
      style="height: 100%"
      @click.stop="drawer = !drawer"
    >
      <v-icon icon="mdi-menu" size="x-large"></v-icon>
    </v-btn>

    <img class="logo" :src="layoutLogo" alt="KVK Logo" />
  </div>
  <v-card style="position: static">
    <v-layout>
      <v-navigation-drawer v-model="drawer" temporary>
        <v-list-item
          class="user-name-item"
          :prepend-avatar="fullImagePath"
          :title="user.fullname"
          to="/profile-info"
        >
        </v-list-item>

        <v-divider></v-divider>

        <v-list dense>
          <v-list-group value="praktika">
            <template v-slot:activator="{ props }">
              <v-list-item
                v-bind="props"
                prepend-icon="mdi-menu"
                title="Praktika"
              ></v-list-item>
            </template>

            <v-list-item
              v-for="(internshipItem, index) in filteredInternshipItems"
              :key="index"
              :value="index"
              class="redirectText"
              :to="internshipItem.route"
            >
              <v-list-item-title>{{ internshipItem.title }}</v-list-item-title>
            </v-list-item>
          </v-list-group>

          <v-list-item
            v-if="this.userRoleId != 2"
            prepend-icon="mdi mdi-file-document-outline"
            title="Dokumentai"
            to="/documents"
            link
          ></v-list-item>

          <v-list-item
            v-if="this.userRoleId != 2"
            prepend-icon="mdi-chart-line"
            title="Rezultatai"
            to="/evaluation"
            link
          >
          </v-list-item>
          <v-list-item
            prepend-icon="mdi-message-text"
            title="Pokalbiai"
            to="/chat-room"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-exit-to-app"
            title="Atsijungti"
            to="/"
          >
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
    </v-layout>
    <Header style="width: 100%"> </Header>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import layoutLogo from "@/assets/Photos/KVKlogo.png";

export default {
  data: () => ({
    drawer: null,
    layoutLogo,
    user: {
      fullname: null,
      image_path: null,
    },
    baseImageUrl: "http://localhost:8000",
    documentItems: [{ title: "Dokumentų peržiūra", route: "/documents" }],

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
  }),
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
      let items = [{ title: "Įvertinimai", route: "/evaluation" }];

      if (this.userRoleId === 1) {
        items.unshift({
          title: "Rezultatų kūrimo forma",
          route: "/evaluation-creation",
        });
      }

      return items;
    },
  },
  created() {
    const userStored = localStorage.getItem("user");
    if (userStored) {
      this.user = JSON.parse(userStored);
    }
  },
};
</script>

<style scoped>
.v-divider {
  margin-top: 0;
}

.user-name-item {
  padding-top: 16px;
  padding-bottom: 16px;
}
.header {
  display: flex;
  justify-content: right;
  align-items: center;
  height: 7rem;
  background: linear-gradient(141deg, #58acfa 0%, #81bef7 51%, #a9d0f5 75%);
}
.logo {
  position: absolute;
  margin-right: 2%;
  height: 4rem;
  margin: 1% 1%;
  top: 1;
  left: 50%;
  transform: translateX(-50%);
  width: 10%;
}

.header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100px;
}

.menu-button {
  position: relative;
  height: 100%;
  width: 100px;
}

.logo {
  width: auto;
  max-height: 80%;
  margin: 0 auto;
}

@media (max-width: 600px) {
  .logo {
    max-width: 50%;
  }
}

@media (max-width: 400px) {
  .menu-button {
    width: 45px;
  }
}
</style>
