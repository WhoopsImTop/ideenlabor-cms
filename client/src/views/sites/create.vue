<template>
  <div>
    <h1>Seite erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <div class="grid grid-cols-2 gap-16 py-4">
        <div class="col-span-1">
          <div class="flex flex-col">
            <input
              class="p-2 border-solid border-gray-300 border-2 rounded"
              type="text"
              v-model="title"
              placeholder="Seitentitel"
            />
          </div>
        </div>
        <div class="col-span-1">
          <div class="flex flex-col">
            <input
              class="p-2 border-solid border-gray-300 border-2 rounded"
              type="text"
              v-model="slug"
              placeholder="URL-Pfad"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Seiteninhalt</h3>
      <builderComponentRenderer></builderComponentRenderer>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Aktionen</h3>
      <div class="flex justify-end">
        <button class="btn px-4 py-2 rounded bg-gray-300 text-white mr-2">
          Abbrechen
        </button>
        <button class="btn px-4 py-2 rounded bg-blue-600 text-white">
          Speichern
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import builderComponentRenderer from "../../components/builderComponentRenderer.vue";
export default {
  data() {
    return {
      site: {
        title: "",
        slug: "",
        meta: {
          title: "",
          description: "",
          keywords: "",
        },
        content: [
          {
            type: "text",
            content: "",
          },
        ],
      },
    };
  },
  components: {
    builderComponentRenderer,
  },
};
</script>

<style>
</style>