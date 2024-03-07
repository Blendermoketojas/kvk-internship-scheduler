<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />
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
   loading-text="Kraunama informacija"
   items-per-page-text="Rodomas studentu skaičius"
 
  ></v-data-table-server>

</div>
</div>

</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import mobileNav from "@/components/MobileSidebar.vue";

  const FakeAPI = {
    async fetch ({ page, itemsPerPage }) {
      return new Promise(resolve => {
        setTimeout(() => {
          const start = (page - 1) * itemsPerPage
          const end = start + itemsPerPage
          const items = desserts.slice()

          if (sortBy.length) {
            const sortKey = sortBy[0].key
            const sortOrder = sortBy[0].order
            items.sort((a, b) => {
              const aValue = a[sortKey]
              const bValue = b[sortKey]
              return sortOrder === 'desc' ? bValue - aValue : aValue - bValue
            })
          }

          const paginated = items.slice(start, end)

          resolve({ items: paginated, total: items.length })
        }, 500)
      })
    },
  }


export default {
  data() {
    return {
      isDesktop: window.innerWidth > 950,
        itemsPerPage: 5,
      headers: [
        { title: 'Vardas Pavardė', key: 'fullname', align: 'start' },
        { title: 'Grupė', key: 'group_identifier', align: 'end' },
        { title: 'Mokslo rūšis', key: 'field_of_study', align: 'end' },
     
      ],
      serverItems: [],
      loading: true,
      totalItems: 0,


    };
  },
  components: {
    customHeader,
    mobileNav,
  },
  methods: {
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },

    loadItems ({ page, itemsPerPage, sortBy }) {
        this.loading = true

      apiClient.get(`/linked-students?`)
        .then(response => {
  
          this.serverItems = response.data.map(item => ({
            ...item,
            group_identifier: item.student_group.group_identifier,
            field_of_study: item.student_group.field_of_study,
        }));
          this.totalItems = response.data.length;
        })
        .catch(error => {
          console.error("Error fetching data:", error);
   
        })
        .finally(() => {
          this.loading = false;
        });
    },

  },
  mounted() {
     this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: [] });
  },
  created() {

    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },
};
</script>

<style>
@import "@/styles/InternshipStyle/ownedStudentList.css";

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
padding: 0 200px;
  }
</style>
