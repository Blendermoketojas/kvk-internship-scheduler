import apiClient from "@/utils/api-client"

function getInternshipTemplates(internshipId) {
    return apiClient.post('/result/internship/templates', { internshipId: internshipId }, { withCredentials: true })
}

function getTemplate(templateId) {
    return apiClient.post('/result/template/get', { id: templateId }, { withCredentials: true })
} 

function createTemplateResultService(templateId, internshipId, answers) {
    const parsedInternshipIdToInt = parseInt(internshipId)
    return apiClient.post('/result/answer/create', { template_id: templateId, internship_id: parsedInternshipIdToInt, answers: answers }, { withCredentials: true })
}

export default {
    getInternshipTemplates,
    getTemplate,
    createTemplateResultService
}