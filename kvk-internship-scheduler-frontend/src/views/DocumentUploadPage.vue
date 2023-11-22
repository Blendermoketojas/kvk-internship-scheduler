<template>
<custom-header></custom-header>



</template>


<script>
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";



export default {
  name: "ProfileInfo",
  data() {
    return {

    }
},
components:{
    customHeader,
}
}

</script>

<style scoped>

</style>