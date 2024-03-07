<template>
    <div class="document-section-container">
        <span id="sectionName" class="m-2 fs-5 font-bold">{{ sectionName }}</span>
        <span id="sectionDescription" class="text-break">{{ description }}</span>
        <document-row v-for="file in files" :file-path="file.file_path" :file-name="resolveFileName(file)" :file-type="resolveFileType(file)"
            :key="file.id" :id="file.id"></document-row>
    </div>
</template>

<script>
import DocumentRow from './DocumentRow.vue';

export default {
    components: {
        DocumentRow
    },
    props: {
        sectionName: {
            required: true,
            type: String,
            default: 'Svarbus dokumentai'
        },
        description: {
            required: false,
            type: String
        },
        files: {
            required: false,
            type: Array
        }
    },
    methods: {
        resolveFileType(file) {
            return file.file_name.split('.')[1];
        },
        resolveFileName(file) {
            return file.file_name.split('.')[0];
        }
    }
}
</script>

<style>
.document-section-container {
    border: 2px solid black;
    border-radius: 5px;
    margin: 7px;
}

@media (max-width:600px){
#sectionName{
font-size:10px !important;
}
#sectionDescription{
    font-size:10px !important;
}

}
</style>