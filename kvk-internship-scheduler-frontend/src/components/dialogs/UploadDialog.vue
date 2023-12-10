<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Naujas katalogas</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row class="mt-1">
                            <v-col cols="12" sm="12">
                                <v-text-field v-model="formData.title" label="Pavadinimas*"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model="formData.description" label="Aprašymas*"></v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*Privalomi laukai</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="closeDialog">
                        Uždaryti
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="saveData">
                        Išsaugoti
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import IDS from '@/services/internship_documents/InternshipDocumentsService';
import textUtils from '@/utils/text-utils';

export default {
    emits: ['documentAdded'],
    data() {
        return {
            dialog: false,
            userInternships: [],
            internshipDocuments: [],
            selectedDocument: null,
            selectedInternshipId: null,
            formData: {
                title: '',
                description: ''
            }
        }
    },
    methods: {
        openDialog() {
            this.dialog = true;
        },
        closeDialog() {
            this.dialog = false;
            this.selectedDocument = null;
        },
        saveData() {
            console.log('hasdasd')
            IDS.handleInternshipDocumentUploadService(this.formData.title, this.formData.description, this.selectedInternshipId).then(response => {
                this.$emit('documentAdded', {
                    id: response.data.document.id,
                    title: response.data.document.title,
                    description: response.data.document.description,
                }); 
                this.dialog = false;
            })
        }
    },
    mounted() {
        const fullString = localStorage.getItem('uploadInternshipId');
        const parts = textUtils.resolvePipeString(fullString);
        this.selectedInternshipId = parseInt(parts[1]);
    }
}
</script>

<style></style>