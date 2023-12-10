import apiClient from "@/utils/api-client";

function getAllUserInternshipDocuments() {
    return apiClient.get('/user/internship/documents', { withCredentials: true })
}

function getInternshipDocumentWithFiles(documentId) {
    return apiClient.post('/internship/documents-files', { documentId }, { withCredentials: true })
}

function getDocumentsByInternshipId(internshipId) {
    return apiClient.post('/documents/internship-by-id', { internshipId: internshipId }, { withCredentials: true })
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

function handleInternshipDocumentUploadService(title, description, internshipId) {
    return apiClient.post('/internship/upload-document-with-files', { title: title, description: description,
        internshipId: internshipId }, {
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

function deleteDocumentWithFilesService(documentId) {
    return apiClient.post('/internship/delete-document-with-files', { documentId: documentId }, {
        withCredentials: true
    })
}

export default {
    getAllUserInternshipDocuments,
    downloadFile,
    uploadFiles,
    getInternshipDocumentsWithFiles,
    deleteFile,
    getDocumentsByInternshipId,
    getInternshipDocumentWithFiles,
    handleInternshipDocumentUploadService,
    deleteDocumentWithFilesService
}