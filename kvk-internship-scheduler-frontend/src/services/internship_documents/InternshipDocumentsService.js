import apiClient from "@/utils/api-client";

function getAllUserInternshipDocuments() {
    return apiClient.get('/user/internship/documents', { withCredentials: true })
}

function downloadFile(fileId) {
    return apiClient.post('/internship/document-download', { fileId }, { withCredentials: true, responseType: 'blob' })
}

function uploadFiles(data) {
    return apiClient.post('/files/create', { ...data }, {
        withCredentials: true, headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
}

export default {
    getAllUserInternshipDocuments,
    downloadFile,
    uploadFiles
}