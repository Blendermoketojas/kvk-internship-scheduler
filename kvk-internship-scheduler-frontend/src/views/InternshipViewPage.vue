<template>
    <custom-header></custom-header>
<div class="mainDiv">
    <div class="pageDescription">
        <h1>Praktikos peržiūra</h1>
        <h2>Čia galite peržiūrėti studentų praktiką</h2>
      </div>
      <div class="viewInformation">
<search-components  @update:selectedGroupId="handleSelectedGroupId"></search-components>
</div>
<v-data-table-server
:search="search"
:headers="headers"
:items="serverItems"
:loading="loading"
item-value="name"
>
<template v-slot:tfoot>
    <tr>
      <td>
        <v-text-field
          v-model="name"
          hide-details
          placeholder="Ieškoti pagal vardą"
          class="ma-2"
          density="compact"
        ></v-text-field>
        </td>
        </tr>
    </template>
    </v-data-table-server>

</div>

</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import searchComponents from "@/components/SearchComponents.vue";
import apiClient from "@/utils/api-client";


export default {
  name: "InternshipView",
  data() {
    return {
        headers: [
        {
          title: 'Vardas',
          align: 'start',
          sortable: true,
          key: 'name',
        },
        { title: 'Grupė', key: 'group', align: 'end' },
        { title: 'Data nuo', key: 'dateFrom', align: 'end' },
        { title: 'Data iki', key: 'dateTo', align: 'end' },
        { title: 'Kompanija', key: 'company', align: 'end' },
      ],
    };
  },
  components: {
    customHeader,
    searchComponents,
  },
  methods: {
    handleSelectedGroupId(groupId) {
        apiClient
          .post("/internships/student-group-active", { studentGroupId: groupId })
          .then((response) => {
            this.groups = response.data.map((group) => ({
              id: group.id,
            //   group_name: group.fullname,
            }));
          })
          .catch((error) => {
            console.error("Error searching for students:", error);
          });
      console.log(groupId);
    },
  },
}

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
    justify-content: space-between;
  }

</style>