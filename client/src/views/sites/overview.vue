<template>
  <div>
    <div class="flex justify-between">
      <h1>Seiten</h1>
      <router-link
        class="btn px-4 py-2 rounded bg-blue-600 text-white"
        to="/pages/create"
        >Seite erstellen</router-link
      >
    </div>
    <table class="mt-10 divide-y divide-gray-300 w-full">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-bold text-black">
            Titel
          </th>
          <th class="px-6 py-3 text-left text-xs font-bold text-black">
            URL-Pfad
          </th>
          <th class="px-6 py-3 text-left text-xs font-bold text-black">
            Aktionen
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="page in pages" :key="page.id">
          <td class="px-6 py-3 text-left text-xs font-bold text-black">
            {{ page.title }}
          </td>
          <td class="px-6 py-3 text-left text-xs font-bold text-black">
            {{ page.slug }}
          </td>
          <td class="px-6 py-3 text-left text-xs font-bold text-black">
            <router-link
              class="btn px-4 py-2 rounded bg-blue-600 text-white"
              :to="`/pages/${page.id}`"
              >Bearbeiten</router-link
            >
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      pages: [],
    };
  },
  methods: {
    async getPages() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/sites");
        this.pages = response.data.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
  beforeMount() {
    this.getPages();
  },
};
</script>

<style>
</style>