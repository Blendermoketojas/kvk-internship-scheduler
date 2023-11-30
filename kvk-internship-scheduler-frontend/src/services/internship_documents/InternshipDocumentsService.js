import apiClient from "@/utils/api-client";

function getAllUserInternshipDocuments() {
    return apiClient.get('/user/internship/documents', { withCredentials: true })
}

function downloadFile(fileId) {
    return apiClient.post('/internship/document-download', { fileId }, { withCredentials: true, responseType: 'blob' })
}

export default {
    getAllUserInternshipDocuments,
    downloadFile
}