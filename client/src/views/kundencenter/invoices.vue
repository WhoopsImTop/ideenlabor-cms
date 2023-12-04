<template>
  <kunden-center-layout>
    <rechnungs-popup
      v-if="active_invoice"
      :invoice_number="active_invoice.invoice_number"
      :invoice_path="active_invoice.invoice_path"
      @close-popup="active_invoice = null"
      class="z-10"
    ></rechnungs-popup>
    <div
      v-if="requireLogin"
      class="w-full flex items-center justify-center min-h-[calc(100vh-118px)]"
    >
      <div
        class="flex flex-col justify-between shadow-sm rounded-lg p-3 bg-white w-full max-w-[20vw] min-w-[350px]"
      >
        <img
          src="../../assets/kundencenter/protected.svg"
          width="100"
          alt="protected"
          class="mx-auto"
        />
        <h2 class="text-xl font-bold text-pink-950 mt-2 text-center">
          Wir brauchen ein Passwort
        </h2>
        <p class="text-xs text-pink-950 text-center">
          Sie müssen das von Ihnen gesetze Passwort eingeben.
        </p>
        <input
          type="password"
          class="w-full border border-pink-200 rounded-lg py-2 px-3 focus:outline-pink-600/50 mt-8"
          v-model="password"
          placeholder="Passwort"
        />
        <p class="text-xs text-pink-700" v-if="errorMessage != ''">
          {{ errorMessage }}
        </p>
        <button
          class="w-full bg-pink-50 hover:bg-pink-100 hover:cursor-pointer text-pink-950 text-xs uppercase rounded-lg py-2 px-3 font-bold mt-4"
          @click="checkPassword"
        >
          Anmelden
        </button>
      </div>
    </div>
    <div v-else-if="!requireLogin" class="min-h-[calc(100vh-118px)] py-6">
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
      <div class="mt-3 grid grid-cols-1 gap-4">
        <div>
          <div class="shadow-sm rounded-lg p-3 bg-white">
            <h3 class="text-sm font-bold tracking-wide uppercase">Dateien</h3>

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
                    v-for="invoice in invoices"
                    :key="invoice.id"
                    @click="active_invoice = invoice"
                    class="hover:bg-pink-50 hover:cursor-pointer"
                  >
                    <td class="text-sm font-normal py-2 px-2">
                      <p class="flex items-center text-xs">
                        <img
                          src="./../../assets/file.svg"
                          width="20"
                          alt="folder"
                          class="mr-2"
                        />
                        {{ invoice.invoice_number }}
                      </p>
                    </td>
                    <td class="px-4 py-3 text-right">
                      <span
                        class="text-xs text-pink-950 bg-pink-50 rounded border border-pink-200 px-2 py-1"
                        >{{
                          new Date(invoice.invoice_date).toLocaleString("de-DE")
                        }}</span
                      >
                    </td>
                    <td class="px-4 py-3 text-right">
                      <button class="inline-block">
                        <img
                          src="./../../assets/kundencenter/eye.svg"
                          width="20"
                          alt="eye"
                        />
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
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
import kundenCenterLayout from "../../layouts/kundenCenterLayout.vue";
import rechnungsPopup from "../../components/kundencenter/rechnungsPopup.vue";
import axios from "axios";
export default {
  components: { kundenCenterLayout, rechnungsPopup },
  data() {
    return {
      customerCenter: null,
      currentFolder: null,
      FolderBreadCrumb: [],
      folderLoading: false,
      copyNotice: false,
      active_invoice: null,
      requireLogin: false,
      password: "",
      errorMessage: "",
    };
  },
  computed: {
    invoices() {
      if (!this.customerCenter.invoices) return [];
      return this.customerCenter.invoices
        .filter((invoice) => invoice.stauts != "entwurf")
        .sort((a, b) => {
          return new Date(b.invoice_date) - new Date(a.invoice_date);
        });
    },
  },
  methods: {
    checkPassword() {
      axios
        .post(
          "/api/customerCenter/validatePassword/" + this.customerCenter.id,
          {
            customer_password: this.password,
          }
        )
        .then((res) => {
          if (res.data.success) {
            this.requireLogin = false;
            console.log(res.data.data);
            this.customerCenter.invoice = res.data.data.invoices;
            //force update ui
            this.$forceUpdate();
          } else {
            alert("Falsches Passwort!");
          }
        })
        .catch((err) => {
          console.log(err);
          this.errorMessage = "Passwort nicht korrekt";
        });
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
    if (this.$store.state.customerCenter.customer_password) {
      this.requireLogin = true;
    }
    this.customerCenter = this.$store.state.customerCenter;
  },
  mounted() {
    this.customerCenter.customer_center.colors = JSON.parse(
      this.customerCenter.customer_center.colors
    );
    this.currentFolder = this.customerCenter.customer_center.folders[0];
  },
};
</script>

<style>
tr:not(:last-child) {
  @apply border-b border-pink-50;
}
</style>
