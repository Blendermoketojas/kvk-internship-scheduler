<template>
  <div>
    <custom-header v-if="isDesktop"></custom-header>
    <mobile-nav v-if="!isDesktop"/>

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
import mobileNav from "@/components/MobileSidebar.vue";
import MainContentContainer from "@/components/containers/MainContentContainer.vue";
import DocumentContainer from "@/components/documents/DocumentContainer.vue";
import IDS from "@/services/internship_documents/InternshipDocumentsService.js"

export default {
  components: {
    customHeader,
    MainContentContainer,
    DocumentContainer,
    mobileNav,
  },
  data() {
    return {
      isDesktop: window.innerWidth > 950,
      internships: [],
      isLoading: true
    };
  },
  methods:{
    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },

  },

  computed: {
  groupedInternships() {
    const grouped = {};
    if (Array.isArray(this.internships)) {
      for (const internship of this.internships) {

        const title = internship.title;


        if (!grouped[title]) {
          grouped[title] = [];
        }

        grouped[title] = grouped[title].concat(internship.documents || []);
      }
    }
    return grouped;
  }
},
  mounted() {
  IDS.getAllUserInternshipDocuments()
    .then(response => {

      this.internships = response.data;
      this.isLoading = false;
    })
    .catch(error => {
      console.error('Could not get documents', error);
      this.isLoading = false;
    });
},
created() {

    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {

    window.removeEventListener('resize', this.handleResize);
  },
};
</script>

<style scoped>

</style>