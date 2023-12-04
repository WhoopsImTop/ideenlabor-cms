<template>
  <div
    class="absolute translate-x-1/2 right-1/2 top-12 bg-white shadow-sm border border-pink-200 rounded-lg w-[40vw] min-w-[600px]"
  >
    <div
      class="inline-flex items-center justify-between w-full p-3 border-b border-pink-200"
    >
      <h3>{{ Rechnungsnummer }}</h3>
      <div
        class="hover:bg-pink-50 hover:cursor-pointer w-7 h-7 rounded-full flex items-center justify-center"
        @click="$emit('close-popup')"
        >
        <img src="../../assets/close.svg" width="20" height="20" />
      </div>
    </div>

    <div class="p-3">
      <iframe :src="RechnungsdownloadUrl" width="100%" style="height: 80vh;"></iframe>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    invoice_number: {
      type: String,
      default: null,
    },
    invoice_path: {
      type: String,
      default: null,
    },
  },

  computed: {
    Rechnungsnummer() {
      return this.invoice_number ? this.invoice_number : "Rechnungsnummer";
    },
    RechnungsdownloadUrl() {
      if (this.invoice_path.includes("public/")) {
        return "https://ideenlabor-agentur.de/api/" + this.invoice_path;
      } else {
        return "https://ideenlabor-agentur.de/api/public/" + this.invoice_path;
      }
    },
  },
};
</script>

<style></style>
