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
          link: "/pages",
        },
        {
          name: "Medien",
          link: "/media",
        },
      ],
      buchhaltung: [
        {
          name: "Rechnungen",
          link: "/invoices",
        },
        {
          name: "Angebote",
          link: "/offers",
        },
        {
          name: "Kunden",
          link: "/customers",
        },
        {
          name: "Leistungen",
          link: "/services",
        },
        {
          name: "Firmendaten",
          link: "/company",
        },
      ],
    };
  },
};
</script>

<style>
</style>