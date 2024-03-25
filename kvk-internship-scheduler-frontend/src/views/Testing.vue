<template>
<mobile-sidebar></mobile-sidebar>

<user-search></user-search>
  </template>
  
  <script>

import MobileSidebar from '@/components/MobileSidebar.vue';
import userSearch from '@/components/SearchUserProfile.vue'

  export default {
    name: "AppLayout",
    data() {
      return {
        drawer: null,

      };
    },
    components: {
userSearch,
MobileSidebar,
  },

};
  </script>
  
  <style scoped>

  .main {
    height: 100px;
  }
  

  </style>
  