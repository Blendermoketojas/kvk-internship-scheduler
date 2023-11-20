// Composables
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    component: () => import("@/views/LoginPage.vue"),
  },
  {
    path: "/calendar",
    name:"calendar",
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
  
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
