<template>
    <div class="document-row-body d-flex direction-row">
        <img style="width: 45px;" :src="imageUrl" />
        <span class="ms-4 align-self-center fs-4">{{ fileName }}</span>
        <span class="ms-4 align-self-center document-type-text">Failo tipas: {{ fileType }}</span>
    </div>
</template>

<script>
import fileBlank from '@/assets/file_images/file_blank.png';
 
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
        filePath: {
            required: true,
            type: String,
            default: '/test'
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
</style>