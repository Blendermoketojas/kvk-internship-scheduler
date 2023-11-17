// Composables
import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    component: () => import("@/views/Login.vue"),
  },
  {
    path: "/Calendar",
    component: () => import("@/views/Calendar.vue"),
  },
  {
    path: "/test",
    component: () => import("@/views/Home.vue"),
  },
  {
    path: "/profileinfo",
    component: () => import("@/views/ProfileInfo.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
