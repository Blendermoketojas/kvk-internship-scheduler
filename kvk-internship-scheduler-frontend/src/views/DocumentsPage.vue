<template>
  <div>
    <custom-header></custom-header>
    <main-content-container>
      <h1 class="mt-4">Dokumentai</h1>
      <p>Čia galite peržiūrėti įkeltus dokumentus</p>
      <v-skeleton-loader v-if="isLoading" type="paragraph"></v-skeleton-loader>
      <div v-else>
        <span v-if="groupedInternships.length === 0">Nėra įkeltų dokumentų.</span>
        <div v-for="(documents, title) in groupedInternships" :key="title">
          <document-container :documents="documents" :container-name="title"></document-container>
        </div>
      </div>
    </main-content-container>
  </div>
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
      internships: [],
      isLoading: true
    };
  },
  computed: {
  groupedInternships() {
    const grouped = {};
    if (Array.isArray(this.internships)) {
      for (const internship of this.internships) {
        // Use the internship title as the key.
        const title = internship.title;

        // Initialize the array for this title if it doesn't already exist.
        if (!grouped[title]) {
          grouped[title] = [];
        }

        // Concatenate the documents into the array.
        grouped[title] = grouped[title].concat(internship.documents || []);
      }
    }
    return grouped;
  }
},
  mounted() {
  IDS.getAllUserInternshipDocuments()
    .then(response => {
      // If the array is wrapped in a 'data' object, make sure to access it like below:
      this.internships = response.data;
      this.isLoading = false;
    })
    .catch(error => {
      console.error('Could not get documents', error);
      this.isLoading = false;
    });
}
};
</script>

<style scoped>
/* Your styles here */
</style>