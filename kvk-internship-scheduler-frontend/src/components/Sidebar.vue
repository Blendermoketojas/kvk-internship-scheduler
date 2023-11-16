<template>
    
    <v-card style="position:static">
      <v-layout >
        <v-navigation-drawer
          v-model="drawer"
          temporary
        >
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/men/78.jpg"
            title="John Leider"
          ></v-list-item>
  
          <v-divider></v-divider>
  
          <v-list density="compact" nav>
            <v-list-item prepend-icon="mdi-view-dashboard" title="Home" value="home"></v-list-item>
            <v-list-item prepend-icon="mdi-forum" title="About" value="about"></v-list-item>
          </v-list>
        </v-navigation-drawer>
  
   
            <v-btn
              color="primary"
              @click.stop="drawer = !drawer"
              style="position: absolute;  height:12%; width:100px;"
            >
            <v-icon  icon="mdi-menu" size="x-large"></v-icon>
            </v-btn>
      </v-layout>
      <Header style="width: 100%;">
    </Header>
    </v-card>
    
  </template>
  
  <script>
    import Header from "@/components/HeaderForMobile.vue";
    export default {
        components:{
            Header,
        },
      data () {
        return {
          drawer: null,
        }
      },
    }
  </script>

  <style>

</style>