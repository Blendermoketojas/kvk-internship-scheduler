<template>
    <div class="document-container mb-1">
        <div class="document-container-title">
            <button @click="toggleBody" class="styleless-button d-flex align-items-center">
                <v-icon id="containerArrow" :icon="collapsed ? 'mdi-chevron-down' : 'mdi-chevron-up'" size="70"></v-icon>
                <div class="text-container">
                    <span id="containerName" class="fs-2">{{ containerName }}</span>
                </div>
            </button>
        </div>
        <transition name="accordion">
            <div v-show="!collapsed" class="document-container-body">
                <div class="document-vertical-line"></div>
                <div class="d-flex flex-column">
                    <document-section v-for="document in documents" :key="document.id" :description="document.description" :section-name="document?.title"
                        :files="document.files"></document-section>
                    <span class="ms-1" v-if="documents.length === 0">Nėra įkeltų dokumentų.</span>
                </div>
            </div>
        </transition>
    </div>
</template>


<script>
import DocumentRow from './DocumentRow.vue';
import DocumentSection from './DocumentSection.vue';

export default {
    components: {
        DocumentRow,
        DocumentSection
    },
    data() {
        return {
            collapsed: true
        }
    },
    props: {
        containerName: {
            required: true,
            type: String,
            default: 'IT sistemų praktika'
        },
        documents: {
            required: false,
            type: Array,
        }
    },
    methods: {
        toggleBody() {
            this.collapsed = !this.collapsed;
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
    flex-grow: 1;
    /* Optional: It helps the text container take up the remaining space */
}

.document-container-title {
    font-weight: 700;
}


.accordion-enter-active,
.accordion-leave-active {
    transition: opacity 0.5s, max-height 0.5s ease;
    overflow: hidden;
}

.accordion-enter-from,
.accordion-leave-to {
    opacity: 0;
    max-height: 0;
}

.accordion-leave-from,
.accordion-enter-to {
    opacity: 1;
    max-height: 1000px;
    /* Adjust as necessary for your content's actual height */
}

@media (max-width:600px){
    #containerName{
font-size: 15px !important;
    }
    #containerArrow{
width: 45px !important;
height: 45px !important;
font-size: 45px !important;
    }
}

</style>
