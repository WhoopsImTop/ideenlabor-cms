<template>
  <kunden-center-layout>
    <einstellungs-popup
      v-if="einstellungsPopup"
      @close-popup="einstellungsPopup = false"
    />
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
        <button
          class="bg-pink-50 px-2 py-1 rounded text-pink-950 text-xs tracking-wider uppercase hover:bg-pink-100 flex items-center"
          @click="einstellungsPopup = true"
        >
          <img
            src="../../assets/settings.svg"
            alt="einstellungen"
            title="Einstellungen"
            width="20"
            class="mr-2"
          />Einstellungen
        </button>
      </div>
      <div class="mt-3 grid grid-cols-3 gap-4">
        <router-link
          class="rounded shadow-sm p-8 pb-6 bg-white flex items-center flex-col hover:border border-pink-200 hover:cursor-pointer"
          to="/cms/customer-center/drive"
        >
          <img
            src="../../assets/kundencenter/folder.svg"
            width="100"
            alt="folder"
          />
          <h3 class="mt-3 font-bold text-xl text-pink-950">Corporate Identity Cloud</h3>
      </router-link>
        <div
          class="rounded shadow-sm p-8 pb-6 bg-white flex items-center flex-col hover:border border-pink-200 hover:cursor-pointer"
        >
          <img
            src="../../assets/kundencenter/mail.svg"
            width="100"
            alt="mail"
          />
          <h3 class="mt-3 font-bold text-xl text-pink-950">
            Email-Signatur-Generator
          </h3>
        </div>
        <router-link
          class="rounded shadow-sm p-8 pb-6 bg-white flex items-center flex-col hover:border border-pink-200 hover:cursor-pointer"
          to="/cms/customer-center/invoices"
        >
          <img
            src="../../assets/kundencenter/invoices.svg"
            width="99"
            alt="invoices"
          />
          <h3 class="mt-3 font-bold text-xl text-pink-950">Rechnungen</h3>
      </router-link>
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
import einstellungsPopup from "../../components/kundencenter/einstellungsPopup.vue";

export default {
  components: { kundenCenterLayout, einstellungsPopup },
  data() {
    return {
      customerCenter: null,
      einstellungsPopup: false,
    };
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
    console.log(this.$store.state.customerCenter);
  },
  mounted() {
    this.customerCenter = this.$store.state.customerCenter;
    this.customerCenter.customer_center.colors = JSON.parse(
      this.customerCenter.customer_center.colors
    );
  },
};
</script>

<style>
tr:not(:last-child) {
  @apply border-b border-pink-50;
}
</style>
