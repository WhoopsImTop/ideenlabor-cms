import { createRouter, createWebHistory } from "vue-router";
import dashboardView from "../views/dashboard.vue";

const router = createRouter({
  base: "/cms",
  history: createWebHistory(),
  routes: [
    {
      path: "/cms/",
      name: "dashboard",
      component: dashboardView,
    },
    {
      path: "/cms/login",
      name: "login",
      component: () => import("../views/login.vue"),
    },
    {
      path: "/cms/invoices",
      name: "invoices",
      component: () => import("../views/invoices/invoices.vue"),
    },
    {
      path: "/cms/invoices/edit/:invoice_number",
      name: "editInvoice",
      component: () => import("../views/invoices/editInvoice.vue"),
    },
    {
      path: "/cms/create-invoice",
      name: "create-invoice",
      component: () => import("../views/invoices/createInvoice.vue"),
    },
    {
      path: "/cms/create-recurring-invoice",
      name: "create-recurring-invoice",
      component: () =>
        import("../views/recurringInvoices/createRecurringInvoice.vue"),
    },
    {
      path: "/cms/recurring-invoices",
      name: "recurring-invoices",
      component: () =>
        import("../views/recurringInvoices/recurringInvoices.vue"),
    },
    {
      path: "/cms/edit-recurring-invoices/:id",
      name: "edit-recurring-invoice",
      component: () =>
        import("../views/recurringInvoices/editRecurringInvoice.vue"),
    },
    {
      path: "/cms/send-invoice/:invoice_number",
      name: "send-invoice",
      component: () => import("../views/invoices/sendInvoice.vue"),
    },
    {
      path: "/cms/offers",
      name: "offers",
      component: () => import("../views/offers/offers.vue"),
    },
    {
      path: "/cms/offers/edit/:offer_number",
      name: "edit-offer",
      component: () => import("../views/offers/editOffer.vue"),
    },
    {
      path: "/cms/create-offers",
      name: "create-offers",
      component: () => import("../views/offers/createOffer.vue"),
    },
    {
      path: "/cms/send-offer/:offer_number",
      name: "send-offers",
      component: () => import("../views/offers/sendOffer.vue"),
    },
    {
      path: "/cms/pages",
      name: "page-overview",
      component: () => import("../views/sites/overview.vue"),
    },
    {
      path: "/cms/pages/create",
      name: "page-create",
      component: () => import("../views/sites/create.vue"),
    },
    {
      path: "/cms/pages/:id",
      name: "page",
      component: () => import("../views/sites/edit.vue"),
    },
    {
      path: "/cms/customers",
      name: "customersOverview",
      component: () => import("../views/customers/overview.vue"),
    },
    {
      path: "/cms/services",
      name: "ServicesOverview",
      component: () => import("../views/services/overview.vue"),
    },
    {
      path: "/cms/company",
      name: "Company",
      component: () => import("../views/company/overview.vue"),
    },
    {
      path: "/cms/media",
      name: "ImageOverview",
      component: () => import("../views/images/overview.vue"),
    },
    {
      path: "/cms/customer-center/overview",
      name: "customerCenter",
      component: () => import("../views/customerCenter/overview.vue"),
    },
    {
      path: "/cms/customer-center/create",
      name: "createCustomerCenter",
      component: () => import("../views/customerCenter/create.vue"),
    },
    {
      path: "/cms/customer-center/:id",
      name: "editCustomerCenter",
      component: () => import("../views/customerCenter/edit.vue"),
    },
    {
      path: "/cms/customer-center",
      name: "customerCenterView",
      component: () => import("../views/kundencenter/overview.vue"),
    },
    {
      path: "/cms/customer-center/drive",
      name: "customerCenterDrive",
      component: () => import("../views/kundencenter/drive.vue"),
    },
    {
      path: "/cms/customer-center/invoices",
      name: "customerCenterInvoice",
      component: () => import("../views/kundencenter/invoices.vue"),
    },
    {
      path: "/cms/customer-center/login",
      name: "customerCenterLogin",
      component: () => import("../views/kundencenter/login.vue"),
    }
  ],
});

export default router;
