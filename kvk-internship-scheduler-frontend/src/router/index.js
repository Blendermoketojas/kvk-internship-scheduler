// Composables
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    component: () => import("@/views/LoginPage.vue"),
  },
  {
    path: "/calendar",
    name: "calendar",
    component: () => import("@/views/CalendarPage.vue"),
  },

  {
    path: "/profile-info",
    component: () => import("@/views/ProfileInfoPage.vue"),
  },
  {
    path: "/internship-management",
    component: () => import("@/views/InternshipManagementPage.vue"),
  },
  {
    path: "/registration",
    component: () => import("@/views/RegistrationPage.vue"),
  },
  {
    path: "/documents",
    component: () => import("@/views/DocumentsPage.vue"),
  },
  {
    path: "/internship-view",
    component: () => import("@/views/InternshipViewPage.vue"),
  },
  {
    path: "/document-upload",
    component: () => import("@/views/DocumentUploadPage.vue"),
  },
  {
    path: "/evaluation-creation",
    component: () => import("@/views/EvaluationCreationPage.vue"),
  },
  {
    path: "/evaluation-demo",
    component: () => import("@/views/EvaluationDemoPage.vue"),
  },

  {
    path: "/user-internships",
    component: () => import("@/views/UsersInternshipsPage.vue"),
  },
  {
    path: "/user-creation",
    component: () => import("@/views/UserCreationPage.vue"),
  },
  {
    path: "/evaluation",
    component: () => import("@/views/EvaluationPage.vue"),
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
