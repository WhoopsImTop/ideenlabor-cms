<template>
  <kunden-center-layout>
    <div v-if="customerCenter != null" class="min-h-[calc(100vh-118px)] py-6">
      <div
        class="flex items-center justify-between shadow-sm rounded-lg p-3 bg-white"
      >
        <div>
          <h2
            class="font-bold px-3 py-1 text-lg text-pink-950 rounded inline-block"
          >
            {{
              customerCenter.customer_name
                ? customerCenter.customer_name
                : customerCenter.customer_company_name
            }}
          </h2>
        </div>
        <router-link
          class="bg-pink-50 px-2 py-1 rounded text-pink-950 text-xs tracking-wider uppercase hover:bg-pink-100 flex items-center"
          to="/cms/customer-center"
        >
          Zurück zur Übersicht
        </router-link>
      </div>
      <div class="mt-3 grid grid-cols-3 gap-4">
        <div :class="customerCenter.customer_center.colors ? 'col-span-2' : 'col-span-3'">
          <div class="shadow-sm rounded-lg p-3 bg-white">
            <h3 class="text-sm font-bold tracking-wide uppercase">Dateien</h3>

            <nav
              class="flex px-3 py-2 mt-3 text-pink-950 border border-pink-200 rounded-lg"
              aria-label="Breadcrumb"
            >
              <ol
                class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
              >
                <li
                  v-for="(breadCrumb, index) in FolderBreadCrumb"
                  :key="breadCrumb.id"
                  class="inline-flex items-center"
                >
                  <span
                    @click="fetchFolder(breadCrumb.folder_hash)"
                    class="inline-flex items-center text-sm hover:cursor-pointer hover:bg-pink-50 rounded px-2 py-1"
                    :class="
                      index === FolderBreadCrumb.length - 1
                        ? ' text-pink-950 bg-pink-50'
                        : ' text-pink-950'
                    "
                  >
                    <svg
                      v-if="index === 0"
                      class="w-3 h-3 me-2.5"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                      />
                    </svg>
                    {{ breadCrumb.folder_name }}</span
                  >
                </li>
              </ol>
            </nav>

            <div class="mt-3 relative w-full">
              <div
                v-if="folderLoading"
                class="absolute bg-pink-900/10 left-0 bottom-0 top-10 right-0 min-h-12"
              >
                <div class="w-full flex items-center justify-center h-full">
                  <div role="status">
                    <svg
                      aria-hidden="true"
                      class="w-8 h-8 text-pink-200 animate-spin fill-pink-600"
                      viewBox="0 0 100 101"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor"
                      />
                      <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill"
                      />
                    </svg>
                    <span class="sr-only">Loading...</span>
                  </div>
                </div>
              </div>
              <table class="w-full rounded-lg overflow-hidden">
                <thead class="text-xs text-pink-950 uppercase bg-pink-50">
                  <tr>
                    <th scope="col" class="px-4 py-3 text-left">Name</th>
                    <th scope="col" class="px-4 py-3 text-right">
                      Zuletzt geändert
                    </th>
                    <th scope="col" class="px-4 py-3 text-right">Aktionen</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(folder, index) in currentFolder.folder_children"
                    :key="folder.id"
                    @click="fetchFolder(folder.folder_hash)"
                    class="hover:bg-pink-50 hover:cursor-pointer"
                  >
                    <td class="text-sm font-normal py-2 px-2">
                      <p class="flex items-center text-xs">
                        <img
                          src="./../../assets/folder.svg"
                          width="20"
                          alt="folder"
                          class="mr-2"
                        />
                        {{ folder.folder_name }}
                      </p>
                      <p v-html="folder.description" class="text-xs ml-7"></p>
                    </td>
                    <td class="px-4 py-3 text-right">
                      <span
                        class="text-xs text-pink-950 bg-pink-50 rounded border border-pink-200 px-2 py-1"
                        >{{
                          new Date(folder.updated_at).toLocaleString("de-DE")
                        }}</span
                      >
                    </td>
                    <td class="px-4 py-3 text-righ"></td>
                  </tr>
                  <tr
                    v-for="file in currentFolder.uploads"
                    :key="file.id"
                    class="hover:bg-pink-50"
                  >
                    <td class="font-normal py-2 px-2 text-xs">
                      <p class="flex items-center">
                        <img
                          src="./../../assets/file.svg"
                          width="20"
                          alt="folder"
                          class="mr-2"
                        />
                        {{ file.file_name }}
                      </p>
                      <p v-html="file.description" class="text-xs ml-7"></p>
                    </td>
                    <td class="px-4 py-3 text-right">
                      <span
                        class="text-xs text-pink-950 bg-pink-50 rounded border border-pink-200 px-2 py-1"
                      >
                        {{ new Date(file.updated_at).toLocaleString("de-DE") }}
                      </span>
                    </td>
                    <td class="px-4 py-3 text-right text-xs">
                      <a
                        :href="file.file_path"
                        :download="file.file_name"
                        target="_blank"
                        class="w-7 h-7 rounded-full inline-flex items-center justify-center hover:bg-pink-200"
                        ><img src="../../assets/download.svg" width="20"
                      /></a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div>
          <div
            v-if="customerCenter.customer_center.colors"
            class="shadow-sm rounded-lg p-3 bg-white mb-4"
          >
            <h3 class="text-sm font-bold tracking-wide uppercase text-pink-950">
              Farben
            </h3>
            <div
              class="grid grid-cols-3 lg:grid-cols-6 xl:grid-cols-9 gap-2 mt-3"
            >
              <div
                v-for="color in customerCenter.customer_center.colors"
                :key="color.id"
                @click="copyToClipboard(color.color_hex)"
                class="group relative"
              >
                <div
                  class="w-12 h-12 rounded-full shadow-sm border border-gray-200 hover:cursor-pointer"
                  :style="{ backgroundColor: color.color_hex }"
                ></div>
                <div
                  class="hidden group-hover:flex absolute -bottom-4 left-1/2 right-1/2 -translate-x-1/2 bg-white shadow-sm p-1 rounded text-xs w-full uppercase text-pink-950"
                >
                  {{ copyNotice ? "Kopiert" : "Kopieren" }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      v-else
      class="py-6 w-full h-[calc(100vh-118px)] flex flex-col items-center justify-center"
    >
      <div role="status">
        <svg
          aria-hidden="true"
          class="w-8 h-8 text-pink-200 animate-spin fill-pink-600"
          viewBox="0 0 100 101"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
            fill="currentColor"
          />
          <path
            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
            fill="currentFill"
          />
        </svg>
        <span class="sr-only">Loading...</span>
      </div>
      <span class="mt-3">Einen Moment bitte</span>
    </div>
  </kunden-center-layout>
</template>

<script>
import axios from "axios";
import kundenCenterLayout from "../../layouts/kundenCenterLayout.vue";
export default {
  components: { kundenCenterLayout },
  data() {
    return {
      customerCenter: null,
      currentFolder: null,
      FolderBreadCrumb: [],
      folderLoading: false,
      copyNotice: false,
      active_invoice: null,
    };
  },
  computed: {
    invoices() {
      return this.customerCenter.invoices
        .filter((invoice) => invoice.stauts != "entwurf")
        .sort((a, b) => {
          return new Date(b.invoice_date) - new Date(a.invoice_date);
        });
    },
  },
  methods: {
    copyToClipboard(text) {
      try {
        navigator.clipboard.writeText(text);
        this.copyNotice = true;
        setTimeout(() => {
          this.copyNotice = false;
        }, 1000);
      } catch (error) {
        window.alert(
          "Ihr Browser unterstützt diese Funktion nicht. Bitte kopieren Sie die Farbe manuell."
        );
      }
    },
    fetchFolder(folderHash) {
      this.folderLoading = true;
      axios
        .get("/api/folders/" + folderHash)
        .then((response) => {
          this.setFolderBreadcrumb(this.currentFolder);
          this.currentFolder = response.data.data;
          this.setFolderBreadcrumb(this.currentFolder);
          this.folderLoading = false;
        })
        .catch((error) => {
          window.alert(error.response.data.message);
        });
    },
    setFolderBreadcrumb(folder) {
      //check if folder is already in breadcrumb
      const isInBreadcrumb = this.FolderBreadCrumb.find(
        (breadCrumb) => breadCrumb.id === folder.id
      );

      if (!isInBreadcrumb) {
        this.FolderBreadCrumb.push(folder);
      } else {
        //remove all folders after the current folder
        const indexOfCurrentFolder = this.FolderBreadCrumb.findIndex(
          (breadCrumb) => breadCrumb.id === folder.id
        );
        this.FolderBreadCrumb.splice(indexOfCurrentFolder + 1);
      }
    },
  },
  beforeMount() {
    if (sessionStorage.getItem("customerCenter")) {
      this.$store.state.customerCenter = JSON.parse(
        sessionStorage.getItem("customerCenter")
      );
    }
    if (this.$store.state.customerCenter.loggedIn == false) {
      window.location.href = "/cms/customer-center/login";
    } else {
      console.log("logged in");
      sessionStorage.setItem(
        "customerCenter",
        JSON.stringify(this.$store.state.customerCenter)
      );
    }
  },
  mounted() {
    this.customerCenter = this.$store.state.customerCenter;
    this.customerCenter.customer_center.colors = JSON.parse(
      this.customerCenter.customer_center.colors
    );
    this.currentFolder = this.customerCenter.customer_center.folders[0];
    this.setFolderBreadcrumb(this.currentFolder);
  },
};
</script>

<style>
tr:not(:last-child) {
  @apply border-b border-pink-50;
}
</style>
