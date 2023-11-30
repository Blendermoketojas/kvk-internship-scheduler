<template>
  <custom-header></custom-header>
  <main-content-container>
    <h1 class="mt-4">Dokumentai</h1>
    <p>Čia galite peržiūrėti įkeltus dokumentus</p>
    <document-container v-for="internship in internships" :key="internship.id" :documents="internship.documents"
      :container-name="internship?.title"></document-container>
  </main-content-container>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import MainContentContainer from "@/components/containers/MainContentContainer.vue";
import DocumentContainer from "@/components/documents/DocumentContainer.vue";
import IDS from "@/services/internship_documents/InternshipDocumentsService.js"

export default {
  components: {
    customHeader,
    MainContentContainer,
    DocumentContainer
  },
  data() {
    return {
      internships: []
    };
  },
  mounted() {
    IDS.getAllUserInternshipDocuments()
      .then(response => this.internships = response.data)
      .catch(error => console.log('could not get'))
  }
};

</script>


<style scoped></style>