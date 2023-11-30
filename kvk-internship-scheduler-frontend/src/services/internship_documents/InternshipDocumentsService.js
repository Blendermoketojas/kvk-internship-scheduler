import apiClient from "@/utils/api-client";

function getAllUserInternshipDocuments() {
    return apiClient.get('/user/internship/documents', { withCredentials: true })
}

function downloadFile(fileId) {
    return apiClient.post('/internship/document-download', { fileId }, { withCredentials: true, responseType: 'blob' })
}

function deleteFile(fileId) {
    return apiClient.post('/file/delete', { fileId }, { withCredentials: true })
}

function uploadFiles(data) {
    return apiClient.post('/files/create', { ...data }, {
        withCredentials: true, headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
}

function getInternshipDocumentsWithFiles(documentId) {
    return apiClient.post('/internship/documents-files', { documentId }, {
        withCredentials: true
    })
}

export default {
    getAllUserInternshipDocuments,
    downloadFile,
    uploadFiles,
    getInternshipDocumentsWithFiles,
    deleteFile
}