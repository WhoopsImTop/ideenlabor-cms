<template>
  <div class="container p-6">
    <h1 class="font-bold text-lg">Ideenlabor CMS</h1>

    <div class="mt-10">
      <h4 class="font-bold text-sm">Verwaltung</h4>

      <router-link
        v-for="seite in verwaltung"
        :key="seite.link"
        :to="seite.link"
        class="block p-2 my-1 rounded hover:bg-blue-100"
        >{{ seite.name }}</router-link
      >
    </div>

    <div class="mt-10">
      <h4 class="font-bold text-sm">Buchhaltung</h4>

      <router-link
        v-for="seite in buchhaltung"
        :key="seite.link"
        :to="seite.link"
        class="block p-2 my-1 rounded hover:bg-blue-100"
        >{{ seite.name }}</router-link
      >
    </div>
    
    <div class="mt-10">
      <h4 class="font-bold text-sm">Kundencenter</h4>

      <router-link
        v-for="seite in kundenCenter"
        :key="seite.link"
        :to="seite.link"
        class="block p-2 my-1 rounded hover:bg-blue-100"
        >{{ seite.name }}</router-link
      >
    </div>
  </div>
</template>

<script>
export default {
  name: "sidebar",
  data() {
    return {
      verwaltung: [
        {
          name: "Seiten",
          link: "/cms/pages",
        },
        {
          name: "Medien",
          link: "/cms/media",
        },
      ],
      buchhaltung: [
        {
          name: "Rechnungen",
          link: "/cms/invoices",
        },
        {
          name: "Angebote",
          link: "/cms/offers",
        },
        {
          name: "Serienrechnungen",
          link: "/cms/recurring-invoices",
        },
        {
          name: "Kunden",
          link: "/cms/customers",
        },
        {
          name: "Leistungen",
          link: "/cms/services",
        },
        {
          name: "Firmendaten",
          link: "/cms/company",
        },
      ],
      kundenCenter: [
        {
          name: "Alle Kundencenter",
          link: "/cms/customer-center",
        },
      ],
    };
  },
};
</script>

<style>
</style>