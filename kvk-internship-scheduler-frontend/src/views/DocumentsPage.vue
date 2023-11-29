<template>
  <custom-header></custom-header>
  <main-content-container>
    <h1 class="mt-4">Užsakymų surinkimai</h1>
    <p>Reikiamų užsakymų atrinkimui prekių sąrašas</p>
  </main-content-container>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import MainContentContainer from "@/components/containers/MainContentContainer.vue";

export default {
  components: {
    customHeader,
    MainContentContainer
  },
  data() {
    return {

    };
  },
};

</script>


<style scoped></style>