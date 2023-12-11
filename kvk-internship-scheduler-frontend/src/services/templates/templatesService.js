import apiClient from "@/utils/api-client"

function getInternshipTemplates(internshipId) {
    return apiClient.post('/result/internship/templates', { internshipId: internshipId }, { withCredentials: true })
}

export default {
    getInternshipTemplates
}