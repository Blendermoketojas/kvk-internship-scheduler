<template>
<customHeader></customHeader>
<v-file-input label="File input"></v-file-input>

</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";



export default {
  name: "Documents",
  data() {
    return {

    };
  },
  components: {
    customHeader,
  },
};

</script>


<style scoped>


</style>