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
      component: () => import("../views/invoices/invoices.vue"),
    },
    {
      path: "/invoices/edit/:invoice_number",
      name: "editInvoice",
      component: () => import("../views/invoices/editInvoice.vue"),
    },
    {
      path: "/create-invoice",
      name: "create-invoice",
      component: () => import("../views/invoices/createInvoice.vue"),
    },
    {
      path: "/send-invoice/:invoice_number",
      name: "send-invoice",
      component: () => import("../views/invoices/sendInvoice.vue"),
    },
    {
      path: "/offers",
      name: "offers",
      component: () => import("../views/offers/offers.vue"),
    },
    {
      path: "/offers/edit/:offer_number",
      name: "edit-offer",
      component: () => import("../views/offers/editOffer.vue"),
    },
    {
      path: "/create-offers",
      name: "create-offers",
      component: () => import("../views/offers/createOffer.vue"),
    },
    {
      path: "/send-offer/:offer_number",
      name: "send-offers",
      component: () => import("../views/offers/sendOffer.vue"),
    },
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
    {
      path: "/customers",
      name: "customersOverview",
      component: () => import("../views/customers/overview.vue"),
    },
    {
      path: "/services",
      name: "ServicesOverview",
      component: () => import("../views/services/overview.vue"),
    },
    {
      path: "/company",
      name: "Company",
      component: () => import("../views/company/overview.vue"),
    },
    {
      path: "/media",
      name: "ImageOverview",
      component: () => import("../views/images/overview.vue"),
    }
  ],
});

export default router;
