import apiClient from "@/utils/api-client"

function getInternshipTemplates(internshipId) {
    return apiClient.post('/result/internship/templates', { internshipId: internshipId }, { withCredentials: true })
}

function getTemplate(templateId) {
    return apiClient.post('/result/template/get', { id: templateId }, { withCredentials: true })
} 

export default {
    getInternshipTemplates,
    getTemplate
}