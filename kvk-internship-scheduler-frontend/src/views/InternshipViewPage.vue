<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />
  <div class="mainDiv">
    <div class="pageDescription">
      <h1>Praktikos peržiūra</h1>
      <h2>Čia galite peržiūrėti studentų praktiką</h2>
    </div>
    <div class="viewInformation">
      <group-search
        @update:selectedGroupId="handleSelectedGroupId"
      ></group-search>
    </div>
    <v-data-table-server
      :search="search"
      :headers="headers"
      :items="internships"
      item-key="id"
      :loading="loading"
      item-value="name"
    >
      <template v-slot:tfoot>
        <tr>
          <td>
            <v-text-field
              v-model="search"
              hide-details
              placeholder="Ieškoti pagal vardą"
              class="ma-2"
              density="compact"
              @input="filterInternships"
            ></v-text-field>
          </td>
        </tr>
      </template>
    </v-data-table-server>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import groupSearch from "@/components/GroupSearch.vue";
import mobileNav from "@/components/MobileSidebar.vue";

export default {
  name: "InternshipView",
  data() {
    return {
      isDesktop: window.innerWidth > 950,
      internships: [],

      headers: [
        {
          title: "Vardas",
          align: "start",
          sortable: true,
          key: "name",
        },
        { title: "Grupė", key: "group", align: "end" },
        { title: "Data nuo", key: "dateFrom", align: "end" },
        { title: "Data iki", key: "dateTo", align: "end" },
        { title: "Kompanija", key: "company", align: "end" },
      ],
    };
  },
  components: {
    customHeader,
    groupSearch,
    mobileNav,
  },
  methods: {
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },

    handleSelectedGroupId(groupId) {
      apiClient
        .post("/internships/student-group-active", { studentGroupId: groupId })
        .then((response) => {
          this.internships = response.data.map((internship) => ({
            name: internship.user_profiles.fullname,
            group: internship.user_profiles.company_id,
            dateFrom: internship.date_from,
            dateTo: internship.date_to,
          }));
        })
        .catch((error) => {
          console.error("Error searching for students:", error);
        });
      console.log(groupId);
    },
  },
  created() {

    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },

};
</script>

<style scoped>
::v-deep .fieldDiv {
  width: 25%;
  display: inline-block;
}

.inputDiv {
  display: flex;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: space-between;
}
h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
.mainDiv {
  padding: 0 200px;
}
.viewInformation {
  padding: 30px 150px;
  display: flex;
  flex-direction: row;
  justify-content: center;
}
</style>
