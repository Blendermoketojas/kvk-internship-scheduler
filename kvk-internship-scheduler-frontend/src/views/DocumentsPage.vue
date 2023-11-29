<template>
  <custom-header></custom-header>
  <main-content-container>
    <h1 class="mt-4">Dokumentai</h1>
    <p>Čia galite peržiūrėti įkeltus dokumentus</p>
    <document-container>
      
    </document-container>
  </main-content-container>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import MainContentContainer from "@/components/containers/MainContentContainer.vue";
import DocumentContainer from "@/components/documents/DocumentContainer.vue";

export default {
  components: {
    customHeader,
    MainContentContainer,
    DocumentContainer
  },
  data() {
    return {

    };
  },
};

</script>


<style scoped></style>