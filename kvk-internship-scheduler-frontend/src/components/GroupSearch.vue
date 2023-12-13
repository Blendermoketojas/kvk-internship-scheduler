<template>
  <div class="fieldDiv">
    <div class="text-subtitle-1 text-bold-emphasis">Grupė</div>
    <v-autocomplete
      v-model="selectedGroup"
      :items="groups"
      item-title="group_identifier"
      item-value="id"
      @input="onGroupInput"
      @change="onGroupChange"
      no-data-text="Nėra ieškomos grupės"
      return-object
      label="Įrašykite grupę"
    ></v-autocomplete>
  </div>
</template>

<script>
import apiClient from "@/utils/api-client";

const debounce = (func, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func(...args);
    }, delay);
  };
};

export default {
  name: "ProfileInfo",
  data() {
    return {
      selectedGroup: null,
      groups: [],
    };
  },
  mounted() {
    this.debouncedSearchGroups = debounce((groupName) => {
      this.searchGroups(groupName);
    }, 500);
  },
  methods: {
    onGroupInput(event) {
      const groupName = event.target.value;
      if (typeof groupName === "string" && groupName.trim() !== "") {
        this.debouncedSearchGroups(groupName);
      }
    },
    // onGroupChange(value) {
    //   if (value && value.id) {
    //     this.$emit("update:selectedGroupId", value.id);
    //   }
    // },

    searchGroups(groupName) {
      if (typeof groupName !== "string") {
        console.error(
          "searchGroups called with non-string argument:",
          groupName
        );
        return;
      }
      if (groupName.trim() !== "") {
        apiClient
          .post("/search-student-groups", { groupIdentifier: groupName })
          .then((response) => {
            this.groups = response.data.map((group) => ({
              id: group.id,
              group_identifier: group.group_identifier,
            }));
          })
          .catch((error) => {
            console.error("Error searching for groups:", error);
          });
      }
    },

    triggerSearchGroups() {
      if (this.selectedGroup && this.selectedGroup.group_identifier) {
        this.debouncedSearchGroups(this.selectedGroup.group_identifier);
      }
    },
  },
  watch:{
    selectedGroup:{
      handler(newVal,oldVal){
        this.$emit('update:selectedGroupId', newVal.id)
      }
    }
  }
};
</script>

<style></style>
