<template>
    <div class="document-row-body">
        <button @click="downloadFile" class="styleless-button d-flex direction-row">
            <img class='fileTypeIcon' style="width: 45px;" :src="imageUrl" />
            <span id="fileName" class="ms-4 align-self-center fs-4">{{ fileName }}</span>
            <span id="fileType" class="ms-4 align-self-center document-type-text">Failo tipas: {{ fileType }}</span>
        </button>
    </div>
</template>

<script>
import fileBlank from '@/assets/file_images/file_blank.png';
import IDS from '@/services/internship_documents/InternshipDocumentsService';

export default {
    data() {
        return {
            fileBlank,
        }
    },
    props: {
        id: {
            required: true,
            type: Number,
        },
        imageUrl: {
            required: true,
            type: Image,
            default: fileBlank
        },
        fileName: {
            required: true,
            type: String,
            default: "Praktikos planas"
        },
        fileType: {
            required: true,
            type: String,
            default: 'word dokumentas'
        },
    },
    methods: {
        downloadFile() {
            console.log('triggerd')
            IDS.downloadFile(this.id).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `${this.fileName}.${this.fileType}`);
                document.body.appendChild(link);
                link.click();
                link.parentNode.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
        }
    }
}

</script>

<style>
.document-row-body {
    border: 1px solid black;
    border-radius: 5px;
    padding: 10px;
    margin: 10px;
}

.document-type-text {
    color: grey;
    font-size: smaller;
}

@media (max-width:600px){
    #fileName{
        font-size: 10px !important;
    }
    #fileType{
        font-size: 10px !important; 
    }
    .fileTypeIcon{
        width: 30px !important;
    }
}
</style>