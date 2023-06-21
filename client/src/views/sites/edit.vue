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
              v-model="site.title"
              placeholder="Seitentitel"
            />
          </div>
        </div>
        <div class="col-span-1">
          <div class="flex flex-col">
            <input
              class="p-2 border-solid border-gray-300 border-2 rounded"
              type="text"
              v-model="site.slug"
              placeholder="URL-Pfad"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Seiteninhalt</h3>
      <builderComponentRenderer
        v-if="!dataLoading"
        :pageContent="site.pageContent"
      ></builderComponentRenderer>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Aktionen</h3>
      <div class="flex justify-end">
        <button class="btn px-4 py-2 rounded bg-gray-300 text-white mr-2" @click="goBack()">
          Abbrechen
        </button>
        <button
          class="btn px-4 py-2 rounded bg-blue-600 text-white"
          @click="savePageData"
        >
          Speichern
        </button>
      </div>
    </div>
  </div>
</template>
  
  <script>
import axios from "axios";
import builderComponentRenderer from "../../components/builderComponentRenderer.vue";
export default {
  data() {
    return {
      dataLoading: true,
      site: {
        title: "",
        slug: "",
        meta: {
          title: "",
          description: "",
          keywords: "",
        },
        pageContent: {
          components: [
            {
              type: "row",
              label: "Reihe",
              component: [
                {
                  type: "col",
                  label: "Spalte",
                  components: [],
                },
                {
                  type: "col",
                  label: "Spalte",
                  components: [],
                },
              ],
            },
          ],
        },
      },
    };
  },
  components: {
    builderComponentRenderer,
  },
  methods: {
    savePageData() {
      axios
        .patch("http://127.0.0.1:8000/api/sites/" + this.site.id, this.site)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getPageData() {
      axios
        .get("http://127.0.0.1:8000/api/sites/" + this.$route.params.id)
        .then((response) => {
          this.site = response.data.data;
          this.dataLoading = false;
        })
        .catch((error) => {
          window.alert(error);
        });
    },
    goBack() {
      this.$router.go(-1);
    },
  },
  beforeMount() {
    this.dataLoading = true;
    this.getPageData();
  },
};
</script>
  
  <style>
</style>