<template>
  <custom-header></custom-header>
  <Teleport to="body">
    <upload-dialog @documentAdded="addCatalog" ref="uploadDialog"></upload-dialog>
  </Teleport>
  <div class="mainDiv">
    <div class="pageDescription">
      <div class="d-flex justify-content-between">
        <h1>Dokumentų įkėlimas</h1>
        <v-select label="Katalogas" v-model="selectedCatalog" item-title="title" item-value="id"
          :items="catalogs"></v-select>
        <v-btn @click="openDialog" color="#0D47A1" height="57px" variant="elevated">Naujas</v-btn>
        <v-btn v-if="selectedCatalog" @click="openDialog" color="red" height="57px" variant="elevated">Pašalinti</v-btn>
      </div>
      <h2>Čia galite įkelti reikalingus dokumentus</h2>
      <h1>Įkelti dokumentai</h1>
      <h2>Paspauskite ant dokumento, norėdami pašalinti</h2>
      <div class="uploadedFiles">
        <v-skeleton-loader v-if="isLoading" width="250px" type="paragraph"></v-skeleton-loader>
        <div class="uploadedFile" v-for="(file, index) in files" :key="index" @click="showModal(file, index)">
          <i :class="getFileIconClass(file.name)" style="font-size: 5rem"></i>
          <p>{{ file.name }}</p>
        </div>
      </div>
      <div v-if="isModalVisible" class="modal">
        <div class="modal-content">
          <h1>Ar norite pašalinti šį failą?</h1>
          <div class="modalBtn">
            <v-btn variant="tonal" width="150px" color="red" rounded="lg" @click="removeFile">Taip</v-btn>
            <v-btn variant="tonal" width="150px" rounded="lg" @click="isModalVisible = false">Ne</v-btn>
          </div>
        </div>
      </div>
      <div class="documentUploadDiv">
        <DxFileUploader id="file-uploader" :onInitialized="onUploaderInitialized" :multiple="true"
          :activeStateEnabled="false" :select-button-text="'Pasirinkite failą'" :label-text="'Arba nutempkite jį čia'"
          accept=".pdf, .doc, .docx, .rtf, .pptx, image/*" :upload-mode="'useButtons'"
          :upload-url="'your-upload-endpoint-url'" @valueChanged="onFilesSelected" height="250px"
          style="border: dashed rgb(153, 150, 150) 2px;" width="80%" />
      </div>
      <div class="bottomButtons">
        <v-btn color="#0D47A1" rounded="xl" variant="elevated" @click="uploadFiles">Išsaugoti</v-btn>
        <v-btn @click="abortAction" rounded="xl" variant="outlined">Atšaukti</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import customHeader from "@/components/DesktopHeader.vue";
import IDS from "@/services/internship_documents/InternshipDocumentsService";
import { DxFileUploader } from "devextreme-vue/file-uploader";
import { DxLoadPanel } from "devextreme-vue/load-panel";
import textUtils from "@/utils/text-utils";
import UploadDialog from '@/components/dialogs/UploadDialog.vue';

export default {
  name: "FileUploader",
  components: {
    customHeader,
    DxFileUploader,
    DxLoadPanel,
    UploadDialog
  },
  data() {
    return {
      files: [],
      newFiles: [],
      retrievedFiles: [],
      selectedFiles: [],
      uploaderInstance: null,
      wrapperClassName: "",
      requests: [],
      isModalVisible: false,
      fileToRemove: null,
      indexToRemove: null,
      catalogs: [],
      selectedCatalog: null,
      activityName: '',
      isLoading: false
    };
  },
  watch: {
    selectedCatalog: {
      handler(newVal, oldVal) {
        this.isLoading = true;
        IDS.getInternshipDocumentWithFiles(newVal).then(response => {
          this.isLoading = false;
          this.resetUploader();
          const formattedFiles = response.data.files.map(f => { return { id: f.id, name: f.file_name } })
          this.retrievedFiles = formattedFiles;
          this.files = formattedFiles;
        })
      }
    },
  },
  methods: {
    openDialog() {
      this.$refs.uploadDialog.openDialog();
    },
    uploadFiles() {
      IDS.uploadFiles({ files: this.newFiles, activityName: this.activityName, activityId: this.selectedCatalog }).then(response => console.log(response.status));
    },
    onUploaderInitialized(e) {
      this.uploaderInstance = e.component;
    },
    resetUploader() {
      if (this.uploaderInstance) {
        this.uploaderInstance.reset();
      }
    },
    addCatalog(e) {
      this.catalogs.push(e);
      this.selectedCatalog = this.catalogs.find(c => c.id === e.id).id;
    },
    showModal(file, index) {
      this.fileToRemove = file;
      this.indexToRemove = index;
      this.isModalVisible = true;
    },
    removeFile() {
      const file = this.files[this.indexToRemove];
      if (this.retrievedFiles.find(f => f.id === file.id)) {
        IDS.deleteFile(this.files[this.indexToRemove].id).then(response => {
          this.files.splice(this.indexToRemove, 1);
          const indexToRemove = this.retrievedFiles.findIndex(f => f.id === file.id)
          this.retrievedFiles.splice(indexToRemove, 1)
          this.isModalVisible = false;
        }).catch(error => console.log('failed to delete file'));
      } else {
        this.files.splice(this.indexToRemove, 1);
        this.isModalVisible = false;
      }
    },
    onFilesSelected(event) {
      this.newFiles = event.value;
      this.files = [...this.retrievedFiles, ...this.newFiles];
    },
    abortAction() {
      this.files = [];
      this.selectedFiles = []
    },
    getFileIconClass(fileName) {
      const ext = fileName
        .slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2)
        .toLowerCase();
      switch (ext) {
        case "pdf":
          return "pi pi-file-pdf";
        case "doc":
        case "docx":
        case "rtf":
          return "pi pi-file-word";
        case "xls":
        case "xlsx":
          return "pi pi-file-excel";
        default:
          return "pi pi-file";
      }
    },
  },
  mounted() {
    const fullString = localStorage.getItem('uploadInternshipId');
    const parts = textUtils.resolvePipeString(fullString);
    this.activityName = parts[0];
    const internshipId = parseInt(parts[1]);
    console.log(internshipId)
    IDS.getDocumentsByInternshipId(internshipId)
      .then(response => this.catalogs = response.data);
  }
};

</script>


<style scoped>
.modalBtn {
  display: flex;
  width: 100%;
  justify-content: space-evenly;
}

.modal {
  display: block;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);

}

.modal-content {
  display: flex;
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  height: 250px;
  text-align: center;
  border: 1px solid rgb(121, 119, 119);
  justify-content: space-evenly;
  align-items: center;
  flex-direction: column;
}

.uploadedFile {
  height: 100%;
  width: auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin: 0 10px;
}

p {
  text-align: center;
  margin-bottom: 0;
  padding-top: 5px;
}

.mainDiv {
  padding: 0 200px;
}

h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}

.documentUploadDiv {
  padding: 0 250px;
  display: flex;
  justify-content: center;
}

.uploadedFiles {
  height: 150px;
  border-radius: 10px;
  border: 2px solid black;
  display: flex;
  margin-bottom: 10px;
}

.bottomButtons {
  display: flex;
  justify-content: center;
  margin-top: 30px;
}

.bottomButtons .v-btn {
  width: 200px;
  margin: 0 10px;
}

#request-panel .parameter-info {
  display: flex;
}

.request-info .parameter-name {
  flex: 0 0 100px;
}

.request-info .parameter-name,
.request-info .parameter-value {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

::v-deep .dx-fileuploader-input-wrapper {
  height: 100%;

}

::v-deep .dx-fileuploader-files-container {
  display: none;

}
</style>
