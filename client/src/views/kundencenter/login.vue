<template>
  <kunden-center-layout>
    <div class="h-[calc(100vh-118px)] flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-sm w-96 p-6 mb-16">
        <h1 class="font-bold text-2xl text-center mb-4">Anmelden</h1>

        <label
          for="price"
          class="block text-sm font-medium leading-6 text-pink-950"
          >Kundennummer</label
        >
        <input
          type="text"
          v-model="customer_number"
          class="block w-full rounded-md border-0 py-1.5 px-3 text-pink-950 ring-1 ring-inset outline-none ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
          placeholder="Kundennummer"
        />

        <label
          for="price"
          class="mt-4 block text-sm font-medium leading-6 text-pink-950"
          >PIN</label
        >
        <input
          type="password"
          v-model="customer_pin"
          class="block w-full rounded-md border-0 py-1.5 px-3 text-pink-950 ring-1 ring-inset outline-none ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
          placeholder="PIN"
        />

        <div class="mt-6">
          <button
            class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded w-full"
            @click="login"
          >
            Anmelden
          </button>
        </div>
      </div>
    </div>
  </kunden-center-layout>
</template>

<script>
import axios from "axios";
import kundenCenterLayout from "../../layouts/kundenCenterLayout.vue";
export default {
  components: {
    kundenCenterLayout,
  },
  data() {
    return {
      customer_number: "",
      customer_pin: "",
    };
  },
  methods: {
    login() {
      axios
        .post("/api/customerCenter/login", {
          customer_number: this.customer_number,
          customer_pin: this.customer_pin,
        })
        .then((res) => {
          if (res.data.success) {
            this.$store.state.customerCenter = res.data.data;
            this.$router.push({ name: "customerCenterView" });
          } else {
            alert("Anmeldung fehlgeschlagen");
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style></style>
