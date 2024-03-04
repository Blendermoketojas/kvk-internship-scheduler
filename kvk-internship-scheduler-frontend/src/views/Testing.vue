<template>
<mobile-sidebar></mobile-sidebar>
  </template>
  
  <script>

import MobileSidebar from '@/components/MobileSidebar.vue';


  export default {
    name: "AppLayout",
    data() {
      return {
        drawer: null,

      };
    },
    components: {

MobileSidebar,
  },

};
  </script>
  
  <style scoped>

  .main {
    height: 100px;
  }
  

  </style>
  