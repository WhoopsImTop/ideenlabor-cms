<template>
  <div>
    <h1>Seite erstellen</h1>
    <div class="bg-white p-4 rounded shadow my-2">
      <div class="grid grid-cols-3 gap-16 py-4">
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
        <div class="col-span-1">
          <div class="flex flex-col">
            <select
              class="p-2 border-solid border-gray-300 border-2 rounded"
              type="text"
              v-model="site.collection"
              placeholder="Welcher Seite gehört diese Seite an?"
            >
              <option value="ideenlabor-waldkirch">Ideenlabor Waldkirch</option>
              <option value="ideenlabor-coworking">Ideenlabor Coworking</option>
              <option value="ideenlabor-agentur">Ideenlabor Agentur</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Seiteninhalt</h3>
      <builderComponentRenderer
        v-if="!dataLoading"
        :pageContent="site.pageContent"
        @updatePageContent="updatePageContent"
      ></builderComponentRenderer>
    </div>
    <div class="bg-white p-4 rounded shadow my-2">
      <h3>Aktionen</h3>
      <div class="flex justify-between">
        <select
          v-model="site.status"
          class="p-2 border-solid border-gray-300 border-2 rounded"
        >
          <option value="published">Veröffentlicht</option>
          <option value="draft">Entwurf</option>
        </select>
        <div>
          <button
            class="btn px-4 py-2 rounded bg-gray-300 text-white mr-2"
            @click="goBack()"
          >
            Abbrechen
          </button>
          <button
            class="btn px-4 py-2 rounded bg-blue-600 text-white"
            @click="savePageData"
          >
            {{ saveButtonText }}
          </button>
        </div>
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
      saveButtonText: "Aktualisieren",
    };
  },
  components: {
    builderComponentRenderer,
  },
  methods: {
    updatePageContent(pageContent) {
      this.site.pageContent = pageContent;
      //force update of pageContent
      this.site.pageContent = JSON.parse(JSON.stringify(this.site.pageContent));
    },
    savePageData() {
      this.saveButtonText = "Speichern...";
      axios
        .patch(
          "/api/sites/" + this.site.id,
          this.site
        )
        .then((response) => {
          this.saveButtonText = "Aktualisiert";
          setTimeout(() => {
            this.saveButtonText = "Aktualisieren";
          }, 1000);
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten: " + error);
        });
    },
    getPageData() {
      axios
        .get("/api/sites/" + this.$route.params.id)
        .then((response) => {
          this.site = response.data.data;
          this.dataLoading = false;
        })
        .catch((error) => {
          window.alert("Es ist ein Fehler aufgetreten: " + error);
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