<template>
    <div class="document-container">
        <div class="d-flex align-items-center document-container-title">
            <v-icon icon="mdi-chevron-down" size="70"></v-icon>
            <div class="text-container">
                <span class="fs-2">{{ containerName }}</span>
            </div>
        </div>
        <div class="document-vertical-line"></div>
        <div class="d-flex flex-column">
            <document-row v-for="i in 5"></document-row>
        </div>
    </div>
</template>

<script>
import DocumentRow from './DocumentRow.vue';

export default {
    components: {
        DocumentRow,
    },
    props: {
        containerName: {
            required: true,
            type: String,
            default: 'IT sistemų praktika'
        }
    }
}
</script>

<style>
.document-container {
    border: 2px solid black;
    border-radius: 5px;
}

.document-vertical-line {
    border-bottom: 2px solid black;
}

.text-container {
    display: flex;
    align-items: center;
    flex-grow: 1; /* Optional: It helps the text container take up the remaining space */
}

.document-container-title {
    font-weight: 700;
}

.document-container-body {

}

/* Other styles */
</style>
