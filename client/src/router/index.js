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
    },
    /* {
      path: "/edit-invoice/:id",
      name: "edit-invoice",
      component: () => import("../views/editInvoice.vue"),
    }, */
    {
      path: "/pages",
      name: "page-overview",
      component: () => import("../views/sites/overview.vue"),
    },
    {
      path: "/pages/create",
      name: "page-create",
      component: () => import("../views/sites/create.vue"),
    },
    {
      path: "/pages/:id",
      name: "page",
      component: () => import("../views/sites/edit.vue"),
    },
  ],
});

export default router;
