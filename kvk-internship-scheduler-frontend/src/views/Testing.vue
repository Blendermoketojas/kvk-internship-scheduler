<template>
    <v-container fluid>
      <v-row align="center" class="main">
        <!-- Logo -->
        <v-col cols="auto">
          <v-img :src="layoutLogo" alt="KVK Logo" class="logo" contain></v-img>
        </v-col>
  
        <!-- Navigation Buttons -->
        <v-col cols="auto" v-for="(item, index) in navigationItems" :key="index">
          <v-menu open-on-hover :activator="`#menu-activator-${item.id}`">
            <template v-slot:activator="{ on, attrs }">
              <v-btn small text v-bind="attrs" v-on="on">
                <router-link class="redirectText" :to="item.route">{{ item.title }}</router-link>
              </v-btn>
            </template>
            <v-list>
              <v-list-item
                v-for="(subItem, subIndex) in item.subItems"
                :key="subIndex"
                :to="subItem.route"
                @click="subItem.action"
              >
                <v-list-item-title>{{ subItem.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
  
        <!-- User Profile -->
        <v-col cols="auto" class="ml-auto">
          <router-link class="redirectText" to="/profile-info">
            <v-img
              :src="user.image_path ? fullImagePath : 'https://freesvg.org/img/abstract-user-flat-4.png'"
              alt="User Image"
              class="userImg"
              contain
            ></v-img>
            {{ user.fullname }}
          </router-link>
        </v-col>
      </v-row>
    </v-container>
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
      console.log(this.getUser.role_id);
    },
  };
  </script>
  
  <style scoped>

  .main {
    height: 100px;
  }
  
  .logo {
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
  </style>
  