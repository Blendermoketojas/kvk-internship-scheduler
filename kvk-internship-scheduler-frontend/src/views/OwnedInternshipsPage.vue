<template>
<custom-header></custom-header>
<div class="mainPage">
<h1>Priskirti studentai</h1>
<h2>Čia galite matyti jusų priskirtų studentų sąrašą</h2>
<div class="contentDiv">
    
    <v-data-table-server
    v-model:items-per-page="itemsPerPage"
    :headers="headers"
    :items-length="totalItems"
    :items="serverItems"
    :loading="loading"
    item-value="name"
    @update:options="loadItems"
  ></v-data-table-server>

</div>
</div>

</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
const desserts = [
    {
      name: 'Frozen Yogurt',
      calories: 159,
      fat: 6.0,
      carbs: 24,
      protein: 4.0,
      iron: '1',
    },
    {
      name: 'Jelly bean',
      calories: 375,
      fat: 0.0,
      carbs: 94,
      protein: 0.0,
      iron: '0',
    },
  ]

export default {
  data() {
    return {};
  },
  components: {
    customHeader,

  },
  methods: {





  },
};
</script>

<style>
.mainPage{
   padding:0 200px; 
}
  
  h2 {
    display: inline-block;
    font-size: 15px;
    color: rgb(170, 167, 167);
    font-weight: 400;
  }

  .contentDiv{
padding: 0 150px;
  }
</style>
