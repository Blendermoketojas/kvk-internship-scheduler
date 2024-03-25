<template>

     <div class="fieldDiv">
        <div class="text-subtitle-1 text-bold-emphasis">Vardas Pavardė</div>
        <v-autocomplete
          v-model="selectedStudent"
          :items="students"
          item-title="fullName"
          item-value="id"
          @input="onStudentInput"
          multiple
          return-object
          label="Įrašykite vardą"
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
      selectedStudent: [],
      students: [],
      

    }
  },
  watch:{

    selectedStudent:{
      handler(newVal, oldVal){
this.sendData(newVal)

      }
    }
  },
  mounted() {
    this.debouncedSearchStudents = debounce((studentName) => {
      this.searchStudents(studentName);
    }, 500);

  },
  methods: {

sendData(value) {
  const selectedIds = value
    .filter(student => student && student.id)
    .map(student => student.id);
  this.$emit("send-student-id", selectedIds);
},
    onStudentInput(value) {
      const studentName = event.target.value;
      if (typeof studentName === "string" && studentName.trim() !== "") {
        this.debouncedSearchStudents(studentName);
      }
    },

    searchStudents(studentName) {
      if (typeof studentName !== "string") {
        console.error(
          "searchStudents called with non-string argument:",
          studentName
        );
        return;
      }
      if (studentName && studentName.trim() !== "") {
        apiClient
          .post("/search-profiles", { fullName: studentName })
          .then((response) => {
            this.students = response.data.map((student) => ({
              id: student.id,
              fullName: student.fullname,
            }));
          })
          .catch((error) => {
            console.error("Error searching for students:", error);
          });
      }
    },

    triggerSearchStudents() {
      this.debouncedSearchStudents(this.selectedStudent);
    },

  }
}



</script>

<style>
.fieldDiv{
  width: 500px;
}

</style>