<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Dokumentų sukūrimas aplinkoje...</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row class="mt-1">
                            <v-col cols="12" sm="12">
                                <v-select :items="userInternships" v-model="selectedInternshipId" item-title="title"
                                    item-value="id" label="Pasirinkti praktiką" required></v-select>
                            </v-col>
                            <span>Įkelti failus į egzistuojantį aplanką</span>
                            <v-col cols="12" sm="12">
                                <v-select v-model="selectedDocument" :items="internshipDocuments" item-title="title" item-value="id"
                                    label="Pasirinkti aplanką" required></v-select>
                            </v-col>
                            <span v-show="!selectedDocument">Kurti naują</span>
                            <v-col v-show="!selectedDocument" cols="12" sm="12">
                                <v-text-field label="Pavadinimas"></v-text-field>
                            </v-col>
                            <v-col v-show="!selectedDocument" cols="12">
                                <v-textarea label="Aprašymas"></v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*Privalomi laukai</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="dialog = false">
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
import IS from '@/services/internships/InternshipService.js'

export default {
    data() {
        return {
            dialog: false,
            userInternships: [],
            internshipDocuments: [],
            selectedInternshipId: null,
            selectedDocument: null
        }
    },
    watch: {
        selectedInternshipId(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.getInternshipDocuments(newVal);
            }
        }
    },
    methods: {
        openDialog() {
            this.dialog = true;
        },
        closeDialog() {
            this.dialog = false;
        },
        getInternshipDocuments(selectedInternshipId) {
            IS.getInternshipDocuments(selectedInternshipId).then(response => { this.internshipDocuments = response.data });
        },
        saveData() {
            this.$store.commit();
            this.dialog = false;
        }
    },
    mounted() {
        IS.getUserInternships()
            .then(response => this.userInternships = response.data)
            .catch(error => console.log('user internships request failed'))
    }
}
</script>

<style></style>