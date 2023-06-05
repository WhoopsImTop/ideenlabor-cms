import { createRouter, createWebHistory } from "vue-router";
import dashboardView from "../views/dashboard.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "dashboard",
      component: dashboardView,
    },
    {
      path: "/invoices",
      name: "invoices",
      component: () => import("../views/invoices.vue"),
    },
    {
      path: "/create-invoice",
      name: "create-invoice",
      component: () => import("../views/createInvoice.vue"),
    }
  ],
});

export default router;
