import apiClient from "@/utils/api-client";

function getCurrentUserInternships() {
    return apiClient.get('/internships', { withCredentials: true })
}

function getInternshipDocuments(internshipId) {
    return apiClient.post('/internship/documents', { internshipId } ,{ withCredentials: true })
}

export default {
    getCurrentUserInternships,
    getInternshipDocuments
}