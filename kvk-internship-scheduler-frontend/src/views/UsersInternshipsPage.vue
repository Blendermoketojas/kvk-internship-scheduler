<template>
<header-nav></header-nav>

<div class="mainPageDiv">
<div class="pageDescription">
    <h1>Praktikos sąrašas</h1>
    <h2>Čia galite peržiūrėti vykstamas ir pasibaigusias praktikas</h2>
  </div>
  <div class="mainInternshipDiv">
<v-expansion-panels>
    <v-expansion-panel
      title="Nu viena is praktiku"
      text="Mire tiodars reikia grabo, o virs grabo virga kabo"
    >
    </v-expansion-panel>
    <v-expansion-panel
    title="Nu kita is praktiku"
    text="salia jos 3 eilėm"
  >
  </v-expansion-panel>

  <v-expansion-panel
  title="Nu dar kita is praktiku"
  text="mandavoske su gelem"
>
</v-expansion-panel>
  </v-expansion-panels>

</div>

</div>
</template>

<script>
import headerNav from '@/components/DesktopHeader.vue';
import apiClient from '@/utils/api-client';

export default {
  name: "UserInternships",
  data() {
    return {

    };
  },
  components: {
    headerNav,
  },

methods:{



},

mounted(){
apiClient.get('/internships').then((response) => {
          if (response.data && response.data.id) {
            this.internship_id = response.data.id;
            console.log("Active internship ID:", this.internship_id);
            this.getUserComments();
          } else {
            throw new Error("Internship ID not found in response");
          }
        });

},

}


</script>

<style>
h2 {
    display: inline-block;
    font-size: 15px;
    color: rgb(170, 167, 167);
    font-weight: 400;
  }
.mainPageDiv{
    padding: 0 200px;
}
.mainInternshipDiv{
    padding: 50px 100px;
}

</style>