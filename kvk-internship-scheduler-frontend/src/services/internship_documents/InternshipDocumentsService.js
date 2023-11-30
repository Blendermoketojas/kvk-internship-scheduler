import apiClient from "@/utils/api-client";

function getAllUserInternshipDocuments() {
    return apiClient.get('/user/internship/documents', { withCredentials: true })
}

export default {
    getAllUserInternshipDocuments
}